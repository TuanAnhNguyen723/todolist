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
        header("Location: mainscreen.php?error=empty_fields");
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

// Truy vấn tất cả các nhiệm vụ từ bảng task và sắp xếp theo time_start
$sql = "SELECT * FROM task ORDER BY time_start ASC";
$result = $conn->query($sql);

$tasks_by_date = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Lấy ngày từ time_start (bỏ phần thời gian)
        $date = date('Y-m-d', strtotime($row['time_start']));
        // Nhóm các task theo ngày
        $tasks_by_date[$date][] = $row;
    }
}


// Kiểm tra nếu có yêu cầu POST được gửi đến
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy giá trị task_id và checked từ yêu cầu POST
    $task_id = isset($_POST['task_id']) ? intval($_POST['task_id']) : 0;
    $checked = isset($_POST['checked']) ? intval($_POST['checked']) : 0;

    // Kiểm tra kết nối cơ sở dữ liệu
    if ($conn->connect_error) {
        die("Kết nối CSDL thất bại: " . $conn->connect_error);
    }

    // Cập nhật trạng thái checked trong cơ sở dữ liệu
    $sql = "UPDATE task SET checked = ? WHERE task_id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die('Lỗi khi chuẩn bị câu truy vấn SQL: ' . $conn->error);
    }

    // Ràng buộc tham số và thực thi câu truy vấn
    $stmt->bind_param("ii", $checked, $task_id);
    if ($stmt->execute()) {
        echo "Trạng thái nhiệm vụ đã được cập nhật thành công.";
    } else {
        echo "Lỗi khi cập nhật trạng thái nhiệm vụ.";
    }

    // Đóng kết nối
    $stmt->close();
    $conn->close();
} else {
    echo "Yêu cầu không hợp lệ.";
}


?>
