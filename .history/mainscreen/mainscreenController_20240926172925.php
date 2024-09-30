<?php
// Kết nối đến CSDL
include '../config.php';

// Kiểm tra nếu form được gửi đi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Kiểm tra kết nối
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Lấy dữ liệu từ form
    $title = isset($_POST['title']) ? $_POST['title'] : '';
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    $time_start = isset($_POST['time_start']) ? $_POST['time_start'] : '';
    $time_end = isset($_POST['time_end']) ? $_POST['time_end'] : '';

    // Kiểm tra dữ liệu có rỗng hay không
    if (empty($title) || empty($time_start) || empty($time_end)) {
        header("Location: your_form_page.php?error=empty_fields");
        exit;
    }

    // Chuẩn bị câu truy vấn SQL
    
    $sql = "INSERT INTO task (title, description, time_start, time_end, checked, user_id, grouptask_id) 
        VALUES (?, ?, ?, ?, 0, 'some_user_id', 'some_group_id')";

    // Chuẩn bị câu lệnh
    $stmt = $conn->prepare($sql);

    // Ràng buộc các tham số
    if ($stmt === false) {
        die('Lỗi chuẩn bị SQL: ' . $conn->error);
    }

    $stmt->bind_param("ssss", $title, $description, $time_start, $time_end);

    // Thực thi câu lệnh
    if ($stmt->execute()) {
        // Sau khi thêm thành công, chuyển hướng với thông báo
        header("Location: mainscreen.php?success=1");
        exit;
    } else {
        // Chuyển hướng với thông báo lỗi
        header("Location: mainscreen.php?error=insert_failed");
        exit;
    }

    // Đóng câu lệnh và kết nối
    $stmt->close();
    $conn->close();
}

// Truy vấn dữ liệu từ bảng task
$sql = "SELECT * FROM task";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $tasks = [];
    while ($row = $result->fetch_assoc()) {
        $tasks[] = $row; // Lưu tất cả các task vào mảng
    }
} else {
    $tasks = []; // Không có dữ liệu
}
?>
