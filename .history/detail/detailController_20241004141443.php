<?php
// Kết nối đến CSDL
include '../config.php';

// Lấy task_id từ URL
$task_id = isset($_GET['task_id']) ? intval($_GET['task_id']) : 0;

if ($task_id > 0) {
    // Truy vấn dữ liệu từ bảng task dựa trên task_id
    $sql = "SELECT * FROM task WHERE task_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $task_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $task = $result->fetch_assoc(); // Lấy dữ liệu task
    } else {
        echo "Không tìm thấy nhiệm vụ.";
        exit();
    }
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['task_id']) && isset($_POST['checked'])) {
    $task_id = intval($_POST['task_id']);
    $checked = intval($_POST['checked']);

    $sql = "UPDATE task SET checked = ? WHERE task_id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("ii", $checked, $task_id);
        if ($stmt->execute()) {
            echo "Trạng thái nhiệm vụ đã được cập nhật thành công.";
        } else {
            echo "Lỗi khi cập nhật trạng thái nhiệm vụ.";
        }
        $stmt->close();
    } else {
        echo "Lỗi chuẩn bị SQL: " . $conn->error;
    }
    $conn->close();
    exit();
}

// Kiểm tra nếu form được gửi từ detail.php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['task_id'])) {
    $task_id = intval($_POST['task_id']);
    $title = $_POST['title'];
    $description = $_POST['description'];
    $time_start = $_POST['time_start'];
    $time_end = $_POST['time_end'];

    // Cập nhật dữ liệu vào bảng task
    $sql = "UPDATE task SET title = ?, description = ?, time_start = ?, time_end = ? WHERE task_id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die('Lỗi chuẩn bị SQL: ' . $conn->error);
    }

    // Bind tham số
    $stmt->bind_param("ssssi", $title, $description, $time_start, $time_end, $task_id);

    // Thực thi và kiểm tra kết quả
    if ($stmt->execute()) {
        // Chuyển hướng về màn hình chính sau khi lưu thành công
        header("Location: ../mainscreen/mainscreen.php?success=1");
        exit();
    } else {
        echo "Lỗi khi cập nhật dữ liệu.";
    }

    $stmt->close();
    $conn->close();
}

$conn->close();
?>
