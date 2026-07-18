<?php
require_once "includes/functions.php";
require_login();
$role = $_SESSION['role'];
$uid = $_SESSION['user_id'];

// For students: prepare attendance and marks arrays for Chart.js
$attendanceData = [];
$marksData = [];
$labels = [];

if ($role === 'student') {
    $att = attendance_stats_for_student($uid); // array of subjects with total/presents
    foreach ($att as $r) {

    $subjectCode = '';

    switch(trim($r['name'])) {
        case 'Machine Learning':
            $subjectCode = 'PEC-CS701E';
            break;

        case 'Cyber Security':
            $subjectCode = 'PEC-CS702E';
            break;

        case 'Operations Research':
            $subjectCode = 'OECCS701A';
            break;

        case 'Project Management and Entrepreneurship':
            $subjectCode = 'HSMC701';
            break;
    }

    $labels[] = $r['name'];

    $p = $r['total_classes']
        ? round(($r['presents'] / $r['total_classes']) * 100, 2)
        : 0;

    $attendanceData[] = $p;
}

$marks = marks_summary_for_student($uid);

foreach ($marks as $m) {

    $subjectCode = '';

    switch(trim($m['name'])) {
        case 'Machine Learning':
            $subjectCode = 'PEC-CS701E';
            break;

        case 'Cyber Security':
            $subjectCode = 'PEC-CS702E';
            break;

        case 'Operations Research':
            $subjectCode = 'OECCS701A';
            break;

        case 'Project Management and Entrepreneurship':
            $subjectCode = 'HSMC701';
            break;
    }

    $marksData[] = [
        'subject' => $m['name'],
        'i1' => $m['internal1'] !== null ? (int)$m['internal1'] : null,
        'i2' => $m['internal2'] !== null ? (int)$m['internal2'] : null,
        'i3' => $m['internal3'] !== null ? (int)$m['internal3'] : null,
    ];
}
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>EduSphere — Dashboard</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
</head>
<body>
<header class="navbar">
  <div class="container">

    <div class="nav-left">
      EduSphere
    </div>

    <div class="nav-right">
      <span>Hello, <?=htmlspecialchars($_SESSION['user_name'])?></span>
      <a href="logout.php" class="logout-btn">Logout</a>
    </div>

  </div>
</header>
  <div class="container">
    <?php if ($role === 'student'): ?>
      <div class="card">
        <h3>Your Dashboard</h3>
<div class="top-cards">

  <!-- Overall Attendance -->
  <div class="stats-card" id="attendanceCard" style="cursor:pointer;">
  <h4>Overall Attendance (average)</h4>
    <?php
    $avg = 0;
    if (count($attendanceData)) $avg = round(array_sum($attendanceData)/count($attendanceData),2);
    ?>
    <div class="value"><?=$avg?>%</div>
  </div>

  <!-- Notice Board -->
<div class="stats-card">
  <h4>Notice Board</h4>

  <?php
  $notices = get_student_notices($uid);

  $newNoticeCount = 0;

  foreach($notices as $n){
      if(!$n['is_read']){
          $newNoticeCount++;
      }
  }
  ?>

  <div class="value">
    <?=$newNoticeCount?>
  </div>

  <div style="margin-top:5px;font-size:14px;">
    New Notices
  </div>

  <a class="btn" href="student_notices.php" style="margin-top:8px;">
    View
  </a>
</div>

  <!-- Pre-Exam Flags -->
  <div class="stats-card">
    <h4>Pre-Exam Checklist</h4>
    <?php
    $flags = preexam_flags($uid);
    $flagCount = count($flags['low_attendance']) + count($flags['low_marks']);
    ?>
    <div class="value"><?=$flagCount?></div>
    <a class="btn" href="preexam_checklist.php" style="margin-top:8px;">View</a>
  </div>

      <div class="stats-card">

  <h4>Study Repository</h4>

  <div class="value">
    📚
  </div>

  <a
  class="btn"
  href="study_repository.php"
  style="margin-top:8px;"
  >
    Open
  </a>

</div>

</div>



        <!-- Add Query button -->
        <div style="margin-top:12px;">
          <a class="btn" href="student_queries.php">
            Send Query
          </a>
        </div>

      </div>

      <div class="chart-grid">

   <div class="card" id="attendanceSection">
  <h4>Attendance (%) by subject</h4>
  <canvas id="attendanceChart"></canvas>

  <!-- 🔽 Smart Predictor Inline -->
  <div style="margin-top:20px;">
    <h4>Detailed Attendance</h4>

    <table class="table">
      <tr>
        <th>Subject</th>
        <th>Classes Attended / Total</th>
        <th>Current %</th>
        <th>Recommendation</th>
        <th>View</th>
      </tr>

      <?php 
      $predictions = attendance_prediction($uid);
      foreach($predictions as $p): 
      ?>
      <tr>
  <td>
<?=
htmlspecialchars(
    $p['subject'] == 'Machine Learning'
        ? 'Machine Learning (PEC-CS701E)'
        : (
            $p['subject'] == 'Cyber Security'
            ? 'Cyber Security (PEC-CS702E)'
            : (
                $p['subject'] == 'Operations Research'
                ? 'Operations Research (OECCS701A)'
                : (
                    $p['subject'] == 'Project Management and Entrepreneurship'
                    ? 'Project Management and Entrepreneurship (HSMC701)'
                    : $p['subject']
                )
            )
        )
)
?>
</td>

  <td><?=$p['attended']?> / <?=$p['total']?></td>

  <td style="
    <?= $p['current_percent'] < 60 
        ? 'color:red;font-weight:bold;' 
        : ($p['current_percent'] < 75 
            ? 'color:#ffc107;font-weight:600;' 
            : 'color:green;font-weight:bold;') ?>
  ">
    <?=$p['current_percent']?>%
  </td>

  <td>
    <?php if($p['current_percent'] < 75): ?>
      <span style="color:red;font-weight:bold;">
        Must attend next <?=$p['required_classes']?> classes for 75%
      </span>
    <?php else: ?>
      <span style="color:green;font-weight:bold;">
        You have above 75%. Keep it up!
      </span>
    <?php endif; ?>
  </td>

  <!-- NEW BUTTON -->
  <td>
    <button class="btn" onclick="loadStudentAttendance('<?=$p['subject']?>')">
      View
    </button>
  </td>
</tr>
      <?php endforeach; ?>
    </table>
  </div>
</div>
</div>

<div class="card">
  <h4>Continuous Assessment(CA) Performance (out of 25)</h4>
  
  <!-- Line Chart -->
  <canvas id="marksChart"></canvas>

  <div style="margin-top:20px;">
  <h4>Absent in Continuous Assessments</h4>

  <table class="table">
    <tr>
      <th>Subject</th>
      <th>CA</th>
    </tr>

    <?php
    $hasAbsent = false;

    foreach($marksData as $md){

        if($md['i1'] === null){
            $hasAbsent = true;
            echo "<tr>
                    <td>".htmlspecialchars($md['subject'])."</td>
                    <td>CA1</td>
                  </tr>";
        }

        if($md['i2'] === null){
            $hasAbsent = true;
            echo "<tr>
                    <td>".htmlspecialchars($md['subject'])."</td>
                    <td>CA2</td>
                  </tr>";
        }

        if($md['i3'] === null){
            $hasAbsent = true;
            echo "<tr>
                    <td>".htmlspecialchars($md['subject'])."</td>
                    <td>CA3</td>
                  </tr>";
        }
    }

    if(!$hasAbsent){
        echo "<tr>
                <td colspan='2' style='text-align:center;color:green;font-weight:bold;'>
                  No absences recorded
                </td>
              </tr>";
    }
    ?>
  </table>
</div>

  <!-- Subject-wise Average Table -->
  <div style="margin-top:25px;">
    <h4>Subject-wise Average (CA1, CA2, CA3)</h4>

    <table class="table">
      <tr>
        <th>Subject</th>
        <th>Average (out of 25)</th>
      </tr>

      <?php foreach($marksData as $md): 
        $values = array_filter([$md['i1'], $md['i2'], $md['i3']], fn($v) => $v !== null);
        $avg = count($values) ? round(array_sum($values)/count($values), 2) : 0;
      ?>
      <tr>
        <td><?=htmlspecialchars($md['subject'])?></td>

        <td style="
          <?= $avg < 15 
    ? 'color:red;font-weight:bold;' 
    : ($avg < 20 
        ? 'color:#ffc107;font-weight:600;' 
        : 'color:green;font-weight:bold;') ?>
        ">
          <?=$avg?>
        </td>
      </tr>
      <?php endforeach; ?>

    </table>
  </div>
</div>

    <?php elseif ($role === 'faculty'): ?>

<?php
$unreadCount = get_unread_query_count($_SESSION['user_id']);
$pendingReplyCount = get_pending_reply_count($_SESSION['user_id']);
?>
      <div class="card">
       <h3>Faculty Dashboard</h3>

<div class="top-cards">

  <div class="stats-card">
    <h4>Unread Queries</h4>

    <div class="value">
      <?=$unreadCount?>
    </div>

    <div style="margin-top:5px;font-size:14px;">
      New Queries
    </div>

    <a class="btn" href="faculty_queries.php" style="margin-top:8px;">
      View
    </a>
  </div>

  <div class="stats-card">
    <h4>Pending Replies</h4>

    <div class="value">
      <?=$pendingReplyCount?>
    </div>

    <div style="margin-top:5px;font-size:14px;">
      Awaiting Response
    </div>

    <a class="btn" href="faculty_queries.php" style="margin-top:8px;">
      View
    </a>
  </div>

</div>

<a class="btn" href="attendance.php">Manage Attendance</a>
<a class="btn" href="marks.php" style="margin-left:8px">Manage Marks</a>
<a class="btn" href="create_notice.php" style="margin-left:8px">Notice Board</a>
<a class="btn" href="upload_material.php" style="margin-left:8px">Study Repository</a>
      </div>

      <div class="card">
        <h4>Your Subjects</h4>
        <ul>
          <?php
            $subs = get_subjects_by_faculty($_SESSION['user_id']);
            foreach($subs as $s) {
              echo "<li>".htmlspecialchars($s['name'])." (ID: ".$s['id'].")</li>";
            }
          ?>
        </ul>
      </div>

      <div class="card">
  <h4>Student Attendance Overview</h4>

  <table class="table">
    <tr>
      <th>Student Name</th>
      <th>Classes (Attended / Total)</th>
      <th>Attendance %</th>
      <th>View</th>
    </tr>

    <?php
    $students = get_students_attendance_overview($_SESSION['user_id']);

    foreach($students as $s):
  $percent = $s['total'] ? round(($s['attended']/$s['total'])*100,2) : 0;
?>
<tr>
  <td><?=htmlspecialchars($s['name'])?></td>

  <td><?=$s['attended']?> / <?=$s['total']?></td>

  <td style="
    <?= $percent < 60 
        ? 'color:red;font-weight:bold;' 
        : ($percent < 75 
            ? 'color:orange;font-weight:600;' 
            : 'color:green;font-weight:bold;') ?>
  ">
    <?=$percent?>%
  </td>

  <!-- 🔥 ADD THIS COLUMN -->
 <td>
  <button class="btn"
    data-id="<?=$s['student_id']?>"
    data-name="<?=htmlspecialchars($s['name'], ENT_QUOTES)?>"
    onclick="loadStudentFullAttendance(this)">
    View
  </button>
</td>

</tr>
<?php endforeach; ?>

  </table>
</div>

<div class="card">
  <h4>Student CA Performance Overview</h4>

  <table class="table">
    <tr>
      <th>Student Name</th>
      <th>Average (out of 25)</th>
    </tr>

    <?php
$marks = get_students_marks_overview($_SESSION['user_id']);

foreach($marks as $m):
  $values = array_filter([$m['internal1'], $m['internal2'], $m['internal3']], fn($v) => $v !== null);
  $avg = count($values) ? round(array_sum($values)/count($values), 2) : 0;
?>
<tr>
  <td><?=htmlspecialchars($m['student_name'])?></td>

  <td style="
    <?= $avg < 15 
        ? 'color:red;font-weight:bold;' 
        : ($avg < 20 
            ? 'color:#ffc107;font-weight:600;' 
            : 'color:green;font-weight:bold;') ?>
  ">
    <?=$avg?>
  </td>
</tr>
<?php endforeach; ?>

  </table>
</div>

    <?php else: /* admin */ ?>
      <div class="card">
      <h3>Admin Dashboard</h3>
      <p>Welcome, Admin.</p>
      
      <div class="stats-box">
        <div class="stats-card">
          <h4>Total Users</h4>
          <div class="value">
            <?=$conn->query("SELECT COUNT(*) AS c FROM users")->fetch_assoc()['c']?>
          </div>
        </div>

        <div class="stats-card">
          <h4>Total Subjects</h4>
          <div class="value">
            <?=$conn->query("SELECT COUNT(*) AS c FROM subjects")->fetch_assoc()['c']?>
          </div>
        </div>
      </div>

      <div style="margin-top:20px;">
        <a href="admin_add_user.php" class="btn" style="margin-right:10px;">
          Add New User
        </a>
        <a href="admin_manage_users.php" class="btn">
          Manage Users
        </a>
        <a href="create_notice.php" class="btn">Manage Notices</a>
      </div>
    </div>
    <?php endif; ?>

  </div>

<script>
<?php if ($role === 'student'): ?>
  const attLabels = <?=json_encode($labels)?>;
  const attData = <?=json_encode($attendanceData)?>;
  const marksData = <?=json_encode($marksData)?>;

  const colors = [
  'rgba(26,115,232,1)',   // ML (blue)
  'rgba(220,53,69,1)',    // Cyber (red)
  'rgba(40,167,69,1)',    // OR (green)
  'rgba(255,193,7,1)'     // PMAE (yellow)
];

  // Attendance bar chart
  const aCtx = document.getElementById('attendanceChart').getContext('2d');

  
  Chart.register(ChartDataLabels);

new Chart(aCtx, {
  type: 'bar',
  data: {
    labels: attLabels,
    datasets: [{
      label: 'Attendance %',
      data: attData,
      borderWidth: 1,
      backgroundColor: attLabels.map((_, index) => colors[index % colors.length])
    }]
  },
  options: {
  scales: {
    x: {
      ticks: {
        callback: function(value) {
          const label = this.getLabelForValue(value);

          if (label.includes('Machine Learning')) {
            return ['Machine Learning', 'PEC-CS701E'];
          }

          if (label.includes('Cyber Security')) {
            return ['Cyber Security', 'PEC-CS702E'];
          }

          if (label.includes('Operations Research')) {
            return ['Operations Research', 'OECCS701A'];
          }

          if (label.includes('Project Management and Entrepreneurship')) {
            return ['PMAE', 'HSMC701'];
          }

          return label;
        }
      }
    },

    y: {
      beginAtZero: true,
      max: 100
    }
  },

    plugins: {
  legend: { display: false },   // ✅ remove blue box

  tooltip: { enabled: false },

  datalabels: {
    anchor: 'end',
    align: 'top',
    formatter: (value) => value + '%',
    font: { weight: 'bold' }
  }
},

    interaction: { mode: null },
    hover: { mode: null },
    events: []   // 🚫 disables all hover/click
  }
});

  // Marks line chart (three internals)
 // NEW: CA-wise X-axis (better visualization)
const labels = ['CA1', 'CA2', 'CA3'];



const datasets = marksData.map((m, index) => ({
  label: m.subject,
  data: [
    m.i1 === null ? 0 : m.i1,
    m.i2 === null ? 0 : m.i2,
    m.i3 === null ? 0 : m.i3
  ],
  original: [m.i1, m.i2, m.i3],
  borderWidth: 2,
  fill: false,
  borderColor: colors[index],
  backgroundColor: colors[index],
}));

const mCtx = document.getElementById('marksChart').getContext('2d');
Chart.register(ChartDataLabels);

new Chart(mCtx, {
  type: 'line',
  data: {
    labels: ['CA1','CA2','CA3'],
    datasets: datasets
  },
  options: {
    scales: {
      y: {
        beginAtZero: true,
        max: 25
      }
    },

    plugins: {
      tooltip: {
        enabled: true
      },

      datalabels: {
        display: false
      }
    },

    interaction: {
      mode: 'nearest',
      intersect: true
    }
  }
});
<?php endif; ?>

<?php if ($role === 'faculty' && $unreadCount > 0): ?>
document.addEventListener("DOMContentLoaded", function() {
  const popup = document.getElementById('queryPopup');
  const countEl = document.getElementById('queryCount');

  if (popup && countEl) {
    countEl.innerText = "<?=$unreadCount?>";
    popup.style.display = 'block';

    setTimeout(() => {
      popup.style.display = 'none';
    }, 6000);
  }
});
<?php endif; ?>



function loadStudentAttendance(subject) {
  fetch('get_student_attendance.php?subject=' + encodeURIComponent(subject))
    .then(res => res.json())
    .then(data => {

      let html = `
        <tr>
          <th>Date</th>
          <th>Status</th>
        </tr>
      `;

      data.forEach(r => {
        html += `
          <tr>
            <td>${r.date}</td>
            <td style="color:${r.status === 'present' ? 'green' : 'red'};font-weight:bold;">
              ${r.status.toUpperCase()}
            </td>
          </tr>
        `;
      });

      document.getElementById('studentAttendanceTable').innerHTML = html;
      document.getElementById('modalTitle').innerText = subject + " - Attendance";

      document.getElementById('studentModal').style.display = 'flex';
    });
}


function loadStudentFullAttendance(btn) {

  const studentId = btn.getAttribute('data-id');
  const studentName = btn.getAttribute('data-name');

  fetch('get_student_full_attendance.php?student_id=' + studentId + '&faculty_id=<?= $_SESSION['user_id'] ?>')
    .then(res => res.json())
    .then(data => {

      let html = `
  <tr>
    <th>Date</th>
    <th>Status</th>
  </tr>
`;

data.forEach(r => {
  html += `
    <tr>
      <td>${r.date}</td>
      <td style="color:${r.status === 'present' ? 'green' : 'red'};font-weight:bold;">
        ${r.status.toUpperCase()}
      </td>
    </tr>
  `;
});

      document.getElementById('facultyAttendanceTable').innerHTML = html;
      document.getElementById('facultyModalTitle').innerText =
        studentName + " - Full Attendance";

      document.getElementById('facultyModal').style.display = 'flex';
    });
}

function closeModal() {
  document.getElementById('studentModal').style.display = 'none';
}

function closeFacultyModal() {
  document.getElementById('facultyModal').style.display = 'none';
}

document.getElementById('attendanceCard')?.addEventListener('click', function() {

    document.getElementById('attendanceSection').scrollIntoView({
        behavior: 'smooth',
        block: 'start'
    });

});

</script>


<div id="queryPopup" style="
  display:none;
  position:fixed;
  top:20px;
  right:20px;
  background:#fff;
  border-left:6px solid #007bff;
  padding:15px 20px;
  box-shadow:0 5px 20px rgba(0,0,0,0.2);
  border-radius:8px;
  z-index:9999;
  animation: slideIn 0.5s ease;
">
  <strong style="color:#007bff;">📩 New Student Queries!</strong>
  <p style="margin:5px 0;">
    You have <span id="queryCount"></span> new queries.
  </p>
  <a href="faculty_queries.php" class="btn" style="margin-top:5px;">View Now</a>
</div>

<style>
@keyframes slideIn {
  from { transform: translateX(100%); opacity:0; }
  to { transform: translateX(0); opacity:1; }
}
</style>

<div id="studentModal" style="display:none;" class="modal">
  <div class="modal-content">
    <h4 id="modalTitle"></h4>
    <table class="table" id="studentAttendanceTable"></table>
    <button onclick="closeModal()" class="btn">Close</button>
  </div>
</div>

<div id="facultyModal" style="display:none;" class="modal">
  <div class="modal-content">
    <h4 id="facultyModalTitle"></h4>
    <table class="table" id="facultyAttendanceTable"></table>
    <button onclick="closeFacultyModal()" class="btn">Close</button>
  </div>
</div>


</body>
</html>
