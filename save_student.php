<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hostel_db";

// Connect
$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) die("Connection failed: " . mysqli_connect_error());

// Get form data
$name = $_POST['name'];
$regno = $_POST['regno'];
$pageno = $_POST['pageno'];
$gender = $_POST['gender'];
$dob = $_POST['dob'];
$course = $_POST['course'];
$dept = $_POST['dept'];
$year = $_POST['year'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$address = $_POST['address'];

// Insert student
$sql = "INSERT INTO students (name, regno, pageno, gender, dob, course, dept, year, email, phone, address)
        VALUES ('$name','$regno','$pageno','$gender','$dob','$course','$dept','$year','$email','$phone','$address')";
if(mysqli_query($conn, $sql)){
    $student_id = mysqli_insert_id($conn);
    header("Location: room.php?student_id=$student_id");
} else {
    echo "Error: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
