<?php
// Kết nối đến CSDL
include '../config.php';


  // Kiểm tra kết nối
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Lấy dữ liệu từ form
$title = $_POST['title'];
$description = $_POST['description'];
$time_start = $_POST['time_start'];
$time_end = $_POST['time_end'];

// Kiểm tra dữ liệu có rỗng hay không
if(empty($title) || empty($time_start) || empty($time_end)) {
    echo "Vui lòng nhập đầy đủ thông tin!";
    exit;
}

// Chuẩn bị câu truy vấn SQL
$sql = "INSERT INTO tasks (title, description, time_start, time_end, checked, user_id, grouptask_id) 
        VALUES (?, ?, ?, ?, 0, 'some_user_id', 'some_group_id')";

// Chuẩn bị câu lệnh
$stmt = $conn->prepare($sql);

// Ràng buộc các tham số
$stmt->bind_param("ssss", $title, $description, $time_start, $time_end);

// Thực thi câu lệnh
if ($stmt->execute()) {
    echo "Task đã được thêm thành công!";
} else {
    echo "Lỗi: " . $sql . "<br>" . $conn->error;
}

// Đóng câu lệnh và kết nối
$stmt->close();
$conn->close();
?>


