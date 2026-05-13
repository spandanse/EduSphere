<?php
require_once "includes/functions.php";

require_login();

if($_SESSION['role'] != 'faculty'){
    die("Access denied");
}

$uid = $_SESSION['user_id'];

$msg = "";

$subjects = get_subjects_by_faculty($uid);

if($_SERVER['REQUEST_METHOD'] == "POST"){

    $title = $_POST['title'];
    $description = $_POST['description'];
    $subject_id = $_POST['subject_id'];

    if(isset($_FILES['file'])){

        $file = $_FILES['file'];

        $allowed = [
            'pdf',
            'doc',
            'docx',
            'ppt',
            'pptx',
            'jpg',
            'jpeg',
            'png',
            'zip'
        ];

        $ext = strtolower(
            pathinfo($file['name'], PATHINFO_EXTENSION)
        );

        if(in_array($ext, $allowed)){

            $newName =
                time() . "_" .
                basename($file['name']);

            $target =
                "uploads/study_materials/" .
                $newName;

            move_uploaded_file(
                $file['tmp_name'],
                $target
            );

            upload_study_material(
                $title,
                $description,
                $newName,
                $subject_id,
                $uid
            );

            $msg = "Study material uploaded successfully.";
        }
        else{
            $msg = "Invalid file type.";
        }
    }
}
?>

<!doctype html>
<html>
<head>

<meta charset="utf-8">

<title>Upload Material</title>

<link rel="stylesheet" href="assets/css/style.css">

</head>

<body>

<div class="container">

<a href="dashboard.php" class="btn">
← Back to Dashboard
</a>

<h2 style="margin-top:20px;">
Upload Study Material
</h2>

<?php if($msg): ?>
<div class="alert success">
<?=$msg?>
</div>
<?php endif; ?>

<div class="card">

<form method="POST" enctype="multipart/form-data">

<label>Title</label>
<input type="text" name="title" required>

<label>Description</label>

<textarea
name="description"
style="width:100%;height:120px;"
></textarea>

<label>Subject</label>

<select name="subject_id" required>

<option value="">
Select Subject
</option>

<?php foreach($subjects as $s): ?>

<option value="<?=$s['id']?>">

<?=htmlspecialchars($s['name'])?>

</option>

<?php endforeach; ?>

</select>

<label>Upload File</label>

<input
type="file"
name="file"
required
>

<br><br>

<div style="display:flex;gap:10px;margin-top:10px;">

<button class="btn">
Upload Material
</button>

<a
href="manage_materials.php"
class="btn"
>
Manage Materials
</a>

</div>

</form>

</div>

</div>

</body>
</html>