<?php
include '../config.php';

// Truy vấn tất cả các nhiệm vụ từ bảng task và sắp xếp theo time_start
$sql = "SELECT * FROM task ORDER BY time_end DESC";
$result = $conn->query($sql);

$tasks_by_date = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $date = date('Y-m-d', strtotime($row['time_end']));
        $tasks_by_date[$date][] = $row;
    }
} else {
    $tasks_by_date = []; // Không có dữ liệu
}
$title = $_POST['title'];


?>
