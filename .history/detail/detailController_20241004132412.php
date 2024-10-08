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
$conn->close();
?>
