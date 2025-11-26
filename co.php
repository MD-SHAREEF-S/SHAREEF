<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hostel_db";

$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) die("Connection failed: " . mysqli_connect_error());

$student_id = $_GET['student_id'] ?? 0;

$sql = "SELECT s.*, a.room_no FROM students s
        LEFT JOIN allocations a ON s.id = a.student_id
        WHERE s.id='$student_id'";
$result = mysqli_query($conn,$sql);
$student = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html>
<head>
<title>Hostel Admission Confirmation</title>
<style>
body{font-family:Arial; background:#f4f4f4; margin:40px;}
.container{background:#fff;padding:30px; border-radius:10px; max-width:800px; margin:auto;}
h1{text-align:center;color:#003366;}
table{width:100%; border-collapse:collapse; margin-top:20px;}
th,td{padding:12px; border:1px solid #ccc;}
th{background:#003366;color:white;text-align:left;width:35%;}
button{display:block;margin:20px auto;padding:12px 20px;background:#003366;color:white;border:none;border-radius:5px; cursor:pointer;}
button:hover{background:#0055aa;}
@media print{button{display:none;} body{margin:0;} .container{box-shadow:none; border-radius:0; padding:0;}}
</style>
</head>
<body>
<div class="container">
<h1>Hostel Admission Confirmation</h1>
<table>
<?php if($student): ?>
<tr><th>Admission No</th><td><?php echo $student['id']; ?></td></tr>
<tr><th>Student Name</th><td><?php echo $student['name']; ?></td></tr>
<tr><th>Registration No</th><td><?php echo $student['regno']; ?></td></tr>
<tr><th>Page Number</th><td><?php echo $student['pageno']; ?></td></tr>
<tr><th>Gender</th><td><?php echo $student['gender']; ?></td></tr>
<tr><th>Date of Birth</th><td><?php echo $student['dob']; ?></td></tr>
<tr><th>Course</th><td><?php echo $student['course']; ?></td></tr>
<tr><th>Department</th><td><?php echo $student['dept']; ?></td></tr>
<tr><th>Year of Study</th><td><?php echo $student['year']; ?></td></tr>
<tr><th>Email</th><td><?php echo $student['email']; ?></td></tr>
<tr><th>Phone Number</th><td><?php echo $student['phone']; ?></td></tr>
<tr><th>Home Address</th><td><?php echo $student['address']; ?></td></tr>
<tr><th>Allocated Room</th><td><?php echo $student['room_no'] ?? "Not Allocated"; ?></td></tr>
<?php else: ?>
<tr><td colspan="2" style="text-align:center;">No student data found.</td></tr>
<?php endif; ?>
</table>
<button onclick="window.print()">Print Confirmation</button>
</div>
</body>
</html>
