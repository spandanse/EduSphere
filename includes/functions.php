<?php
// includes/functions.php
require_once __DIR__ . '/db.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* --- Authentication --- */
function login_user($email, $password) {
    global $conn;
    $email = $conn->real_escape_string($email);
    $q = $conn->query("SELECT * FROM users WHERE email='$email' LIMIT 1");
    if (!$q || $q->num_rows === 0) return false;
    $user = $q->fetch_assoc();

    // If stored password is a bcrypt/argon2 hash, use password_verify
    if (password_needs_rehash($user['password'], PASSWORD_DEFAULT) || password_verify($password, $user['password'])) {
        // if plain text stored (not hashed), password_verify will fail; check fallback:
        if (!password_verify($password, $user['password'])) {
            // fallback: plain-text match
            if ($user['password'] !== $password) {
                return false;
            }
            // plain match -> upgrade to hashed password
            $newHash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET password=? WHERE id=?");
            $stmt->bind_param("si", $newHash, $user['id']);
            $stmt->execute();
        }
        // set session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['role'] = $user['role'];
        return true;
    }
    return false;
}

/* Check login */
function require_login() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: /EduSphere/index.php");
        exit;
    }
}

/* Role check */
function require_role($role) {
    require_login();
    if ($_SESSION['role'] !== $role) {
        http_response_code(403);
        echo "Access denied.";
        exit;
    }
}

/* Get subjects for a faculty */
function get_subjects_by_faculty($faculty_id) {
    global $conn;
    $faculty_id = (int)$faculty_id;
    $res = $conn->query("SELECT * FROM subjects WHERE faculty_id=$faculty_id");
    return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
}

/* Attendance percentage for a student per subject */
function attendance_stats_for_student($student_id) {
    global $conn;
    $student_id = (int)$student_id;
    $sql = "
      SELECT s.id AS subject_id, s.name,
             COUNT(a.id) AS total_classes,
             SUM(CASE WHEN a.status='present' THEN 1 ELSE 0 END) AS presents
      FROM subjects s
      LEFT JOIN attendance a ON a.subject_id = s.id AND a.student_id = $student_id
      GROUP BY s.id
      ORDER BY s.name";
    $res = $conn->query($sql);
    return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
}

/* Marks summary for a student */
function marks_summary_for_student($student_id) {
    global $conn;
    $student_id = (int)$student_id;
    $sql = "
      SELECT s.id AS subject_id, s.name,
                m.internal1,
                m.internal2,
                m.internal3
      FROM subjects s
      LEFT JOIN marks m ON m.subject_id = s.id AND m.student_id = $student_id
      ORDER BY s.name";
    $res = $conn->query($sql);
    return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
}

/* Faculty: record attendance */
function record_attendance_bulk($subject_id, $attendance_array, $date=null) {
    global $conn;
    $subject_id = (int)$subject_id;
    $date = $date ?: date('Y-m-d');
    $stmt = $conn->prepare("INSERT INTO attendance (student_id, subject_id, date, status) VALUES (?, ?, ?, ?)");

    foreach ($attendance_array as $student_id => $status) {
        $sid = (int)$student_id;
        $st = $status === 'present' ? 'present' : 'absent';
        $stmt->bind_param("iiss", $sid, $subject_id, $date, $st);
        $stmt->execute();
    }
    return true;
}

/* Faculty: upsert marks */
function upsert_marks($student_id, $subject_id, $i1, $i2, $i3) {
    global $conn;
    $student_id = (int)$student_id;
    $subject_id = (int)$subject_id;
    $i1=(int)$i1; $i2=(int)$i2; $i3=(int)$i3;

    // check exists
    $q = $conn->query("SELECT id FROM marks WHERE student_id=$student_id AND subject_id=$subject_id LIMIT 1");
    if ($q && $q->num_rows) {
        $conn->query("UPDATE marks SET internal1=$i1, internal2=$i2, internal3=$i3 WHERE student_id=$student_id AND subject_id=$subject_id");
    } else {
        $conn->query("INSERT INTO marks (student_id, subject_id, internal1, internal2, internal3) VALUES ($student_id,$subject_id,$i1,$i2,$i3)");
    }
    return true;
}

/* Pre-exam checklist: low attendance and incomplete submissions */
function preexam_flags($student_id) {
    global $conn;
    $student_id = (int)$student_id;
    // Low attendance
    $sql = "
      SELECT s.name,
             COALESCE(total,0) AS total_classes,
             COALESCE(presents,0) AS presents,
             (CASE WHEN COALESCE(total,0)=0 THEN 0 ELSE ROUND((presents/total)*100,2) END) AS attendance_percent
      FROM subjects s
      LEFT JOIN (
        SELECT subject_id,
          COUNT(*) AS total,
          SUM(CASE WHEN status='present' THEN 1 ELSE 0 END) AS presents
        FROM attendance a
        WHERE a.student_id=$student_id
        GROUP BY subject_id
      ) t ON t.subject_id = s.id
      ORDER BY s.name";
    $res = $conn->query($sql);
    $low = [];
    if ($res) {
        while ($r = $res->fetch_assoc()) {
            if ((float)$r['attendance_percent'] < 75.0) {
                $low[] = $r;
            }
        }
    }
    // Incomplete submissions
    $subRes = $conn->query("SELECT s.name AS subject, sub.assignment_title, sub.due_date FROM submissions sub JOIN subjects s ON sub.subject_id=s.id WHERE sub.student_id=$student_id AND sub.submitted=0");
    $incomplete = $subRes ? $subRes->fetch_all(MYSQLI_ASSOC) : [];

    return ['low_attendance'=>$low, 'incomplete'=>$incomplete];
}

function check_admin() {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        header("Location: dashboard.php");
        exit;
    }
}

/* Smart Attendance Predictor */
function attendance_prediction($uid) {
    global $conn;

    $sql = "
        SELECT s.name AS subject,
               COUNT(a.id) AS total,
               SUM(CASE WHEN a.status='present' THEN 1 ELSE 0 END) AS attended
        FROM subjects s
        LEFT JOIN attendance a 
            ON a.subject_id = s.id AND a.student_id = $uid
        GROUP BY s.id
    ";

    $res = $conn->query($sql);
    $out = [];

    while($row = $res->fetch_assoc()) {

        $total = (int)$row['total'];
        $attended = (int)$row['attended'];

        $percent = $total ? round(($attended/$total)*100,2) : 0;

        // required classes to reach 75%
        $required = 0;
        if ($percent < 75 && $total > 0) {
            $required = ceil((0.75*$total - $attended) / (1 - 0.75));
        }

        $out[] = [
            'subject' => $row['subject'],
            'attended' => $attended,   // ✅ ADD THIS
            'total' => $total,         // ✅ ADD THIS
            'current_percent' => $percent,
            'required_classes' => $required
        ];
    }

    return $out;
}

/* Send Query */
function send_query($student_id, $faculty_id, $subject, $message) {

    global $conn;

    $stmt = $conn->prepare("
        INSERT INTO queries (student_id, faculty_id, subject, message)
        VALUES (?, ?, ?, ?)
    ");

    $stmt->bind_param("iiss", $student_id, $faculty_id, $subject, $message);
    $stmt->execute();
}


/* Get queries for student */
function get_student_queries($student_id) {

    global $conn;

    $stmt = $conn->prepare("
        SELECT q.*, u.name AS faculty_name
        FROM queries q
        JOIN users u ON q.faculty_id = u.id
        WHERE q.student_id=?
        ORDER BY q.created_at DESC
    ");

    $stmt->bind_param("i", $student_id);
    $stmt->execute();

    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}


/* Get queries for faculty */
function get_faculty_queries($faculty_id) {

    global $conn;

    $stmt = $conn->prepare("
        SELECT q.*, u.name AS student_name
        FROM queries q
        JOIN users u ON q.student_id = u.id
        WHERE q.faculty_id=?
        ORDER BY q.created_at DESC
    ");

    $stmt->bind_param("i", $faculty_id);
    $stmt->execute();

    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}


/* Reply to query */
function reply_query($query_id, $reply) {

    global $conn;

    $stmt = $conn->prepare("
        UPDATE queries
        SET reply=?, status='replied'
        WHERE id=?
    ");

    $stmt->bind_param("si", $reply, $query_id);
    $stmt->execute();
}

function get_students_attendance_overview($faculty_id) {
    global $conn;

    $sql = "
    SELECT u.id AS student_id,
       u.name,
       COUNT(a.id) AS total,
       SUM(CASE WHEN a.status='present' THEN 1 ELSE 0 END) AS attended
FROM users u
LEFT JOIN attendance a 
    ON u.id = a.student_id
    AND a.subject_id IN (
        SELECT id FROM subjects WHERE faculty_id = ?
    )
WHERE u.role = 'student'
GROUP BY u.id, u.name
";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $faculty_id);
    $stmt->execute();
    $res = $stmt->get_result();

    return $res->fetch_all(MYSQLI_ASSOC);
}

function get_students_marks_overview($faculty_id) {
    global $conn;

    $sql = "
        SELECT u.name AS student_name,
               m.internal1,
               m.internal2,
               m.internal3
        FROM marks m
        JOIN users u ON m.student_id = u.id
        JOIN subjects s ON m.subject_id = s.id
        WHERE s.faculty_id = ?
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $faculty_id);
    $stmt->execute();
    $res = $stmt->get_result();

    return $res->fetch_all(MYSQLI_ASSOC);
}

function get_unread_query_count($faculty_id) {
    global $conn;

    $stmt = $conn->prepare("
        SELECT COUNT(*) AS c
        FROM queries
        WHERE faculty_id = ? AND seen = 0
    ");

    $stmt->bind_param("i", $faculty_id);
    $stmt->execute();
    $res = $stmt->get_result();

    return $res->fetch_assoc()['c'];
}

function get_student_subject_attendance($student_id, $subject_name) {
    global $conn;

    $stmt = $conn->prepare("
        SELECT a.date, a.status
        FROM attendance a
        JOIN subjects s ON a.subject_id = s.id
        WHERE a.student_id = ? AND s.name = ?
        ORDER BY a.date DESC
    ");

    $stmt->bind_param("is", $student_id, $subject_name);
    $stmt->execute();

    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function get_faculty_subject_attendance($faculty_id, $subject_id) {
    global $conn;

    $stmt = $conn->prepare("
        SELECT u.id, u.name AS student_name, a.date, a.status
        FROM attendance a
        JOIN users u ON a.student_id = u.id
        JOIN subjects s ON a.subject_id = s.id
        WHERE a.subject_id = ? 
        AND s.faculty_id = ?
        ORDER BY a.date DESC
    ");

    $stmt->bind_param("ii", $subject_id, $faculty_id);
    $stmt->execute();

    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}