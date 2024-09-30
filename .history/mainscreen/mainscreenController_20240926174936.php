<?php
// Kết nối đến CSDL
include '../config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $time_start = $_POST['time_start'];
    $time_end = $_POST['time_end'];

    // Kiểm tra nếu là chỉnh sửa
    if (!empty($_POST['task_id'])) {
        $task_id = $_POST['task_id'];
        // Câu lệnh UPDATE
        $sql = "UPDATE task SET title = ?, description = ?, time_start = ?, time_end = ? WHERE task_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $title, $description, $time_start, $time_end, $task_id);
    } else {
        // Câu lệnh INSERT (thêm mới)
        $sql = "INSERT INTO task (title, description, time_start, time_end, checked, user_id, grouptask_id) 
                VALUES (?, ?, ?, ?, 0, 'some_user_id', 'some_group_id')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $title, $description, $time_start, $time_end);
    }

    if ($stmt->execute()) {
        header("Location: mainscreen.php?success=1");
    } else {
        header("Location: mainscreen.php?error=1");
    }

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

if (isset($_GET['task_id'])) {
    $task_id = $_GET['task_id'];

    // Truy vấn dữ liệu nhiệm vụ
    $sql = "SELECT * FROM task WHERE task_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $task_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $task = $result->fetch_assoc();
        echo json_encode($task);
    } else {
        echo json_encode(['error' => 'Task not found']);
    }
}
?>
