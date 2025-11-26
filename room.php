<?php
$conn = mysqli_connect("localhost","root","","hostel_db");
if(!$conn) die("Connection failed: ".mysqli_connect_error());

// Assume student ID is passed from previous page
$student_id = intval($_GET['student_id'] ?? 0);

// Handle room allocation when a room button is clicked
if(isset($_POST['room_no'])){
    $room_no = intval($_POST['room_no']);

    // Start transaction to prevent race conditions
    mysqli_begin_transaction($conn);

    $room_query = mysqli_query($conn,"SELECT * FROM rooms WHERE room_no='$room_no' FOR UPDATE");
    $room = mysqli_fetch_assoc($room_query);

    if(!$room){
        mysqli_rollback($conn);
        die("Invalid room number.");
    }

    $occupied = intval($room['occupied']);
    $capacity = intval($room['capacity']);

    if($occupied >= $capacity){
        mysqli_rollback($conn);
        echo "<script>alert('Room $room_no is full!'); window.location.href='room.php?student_id=$student_id';</script>";
        exit();
    }

    // Allocate the student
    mysqli_query($conn,"INSERT INTO allocations (student_id, room_no) VALUES ('$student_id','$room_no')");
    mysqli_query($conn,"UPDATE rooms SET occupied = occupied + 1 WHERE room_no='$room_no'");

    mysqli_commit($conn);

    // Redirect to confirmation page
    header("Location: co.php?student_id=$student_id");
    exit();
}

// Fetch all rooms
$rooms = mysqli_query($conn,"SELECT * FROM rooms ORDER BY room_no ASC");
$roomData = [];
while($r = mysqli_fetch_assoc($rooms)) $roomData[$r['room_no']] = $r;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Hostel Room Allocation</title>
<style>
body { font-family: Arial; margin: 20px; background: #eef2f3; }
h1 { text-align: center; color: #003366; }
.section { margin-bottom: 40px; }
.section h2 { color: #003366; margin-bottom: 10px; }
.grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(80px, 1fr)); gap: 10px; }
.room { padding: 10px; text-align: center; border-radius: 5px; cursor: pointer; font-weight: bold; color: white; border:none; }
.available { background: green; }
.full { background: red; cursor: not-allowed; }
.ac { border: 2px solid gold; }
form { margin:0; }
</style>
</head>
<body>

<h1>Hostel Room Allocation - JAMAL MOHAMED COLLEGE</h1>

<!-- 3-member rooms -->
<div class="section">
    <h2>Rooms 1–100 (3 Members)</h2>
    <div class="grid">
    <?php
    for($i=1; $i<=100; $i++){
        $r = $roomData[$i] ?? ['occupied'=>0,'capacity'=>3];
        $occupied = intval($r['occupied']);
        $capacity = intval($r['capacity']);
        $class = ($occupied >= $capacity) ? 'full' : 'available';
        echo "<form method='POST'>";
        echo "<input type='hidden' name='room_no' value='$i'>";
        echo "<button type='submit' class='room $class' ".(($occupied >= $capacity) ? "disabled":"").">";
        echo "Room $i<br>($occupied/$capacity)";
        echo "</button>";
        echo "</form>";
    }
    ?>
    </div>
</div>

<!-- 6-member rooms -->
<div class="section">
    <h2>Rooms 101–350 (6 Members)</h2>
    <div class="grid">
    <?php
    for($i=101; $i<=350; $i++){
        $r = $roomData[$i] ?? ['occupied'=>0,'capacity'=>6];
        $occupied = intval($r['occupied']);
        $capacity = intval($r['capacity']);
        $class = ($occupied >= $capacity) ? 'full' : 'available';
        echo "<form method='POST'>";
        echo "<input type='hidden' name='room_no' value='$i'>";
        echo "<button type='submit' class='room $class' ".(($occupied >= $capacity) ? "disabled":"").">";
        echo "Room $i<br>($occupied/$capacity)";
        echo "</button>";
        echo "</form>";
    }
    ?>
    </div>
</div>

<!-- AC rooms -->
<div class="section">
    <h2>AC Rooms 351–400 (2 Members)</h2>
    <div class="grid">
    <?php
    for($i=351; $i<=400; $i++){
        $r = $roomData[$i] ?? ['occupied'=>0,'capacity'=>2];
        $occupied = intval($r['occupied']);
        $capacity = intval($r['capacity']);
        $class = ($occupied >= $capacity) ? 'full' : 'available';
        echo "<form method='POST'>";
        echo "<input type='hidden' name='room_no' value='$i'>";
        echo "<button type='submit' class='room $class ac' ".(($occupied >= $capacity) ? "disabled":"").">";
        echo "Room $i<br>($occupied/$capacity)";
        echo "</button>";
        echo "</form>";
    }
    ?>
    </div>
</div>

</body>
</html>

