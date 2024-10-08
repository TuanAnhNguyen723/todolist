<?php
// Kết nối đến CSDL
include '../config.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Kiểm tra nếu form được gửi đi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $task_id = isset($_POST['task_id']) ? intval($_POST['task_id']) : null;

    // Xử lý cập nhật trạng thái 'checked' của task
    if (isset($_POST['checked']) && $task_id) {
        $checked = intval($_POST['checked']);
        $sql = "UPDATE task SET checked = ? WHERE task_id = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("ii", $checked, $task_id);
            $stmt->execute();
            echo $stmt->affected_rows > 0 ? "Trạng thái nhiệm vụ đã được cập nhật thành công." : "Không có thay đổi.";
            $stmt->close();
        } else {
            echo "Lỗi chuẩn bị SQL: " . $conn->error;
        }
        exit();
    }

    // Xử lý thêm mới hoặc chỉnh sửa task
    if (isset($_POST['title']) && isset($_POST['time_start']) && isset($_POST['time_end'])) {
        $title = $_POST['title'];
        $description = $_POST['description'];
        $time_start = $_POST['time_start'];
        $time_end = $_POST['time_end'];

        if (empty($title) || empty($time_start) || empty($time_end)) {
            header("Location: mainscreen.php?error=empty_fields");
            exit();
        }

        if ($task_id) {
            // Chỉnh sửa task
            $sql = "UPDATE task SET title = ?, description = ?, time_start = ?, time_end = ? WHERE task_id = ?";
            $stmt = $conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("ssssi", $title, $description, $time_start, $time_end, $task_id);
                $stmt->execute();
                echo $stmt->affected_rows > 0 ? "Nhiệm vụ đã được cập nhật thành công." : "Không có thay đổi.";
                $stmt->close();
            } else {
                echo "Lỗi chuẩn bị SQL: " . $conn->error;
            }
        } else {
            // Thêm mới task
            $sql = "INSERT INTO task (title, description, time_start, time_end, checked, user_id, grouptask_id) 
                    VALUES (?, ?, ?, ?, 0, 'some_user_id', 'some_group_id')";
            $stmt = $conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("ssss", $title, $description, $time_start, $time_end);
                if ($stmt->execute()) {
                    header("Location: mainscreen.php?success=1");
                    exit();
                } else {
                    header("Location: mainscreen.php?error=insert_failed");
                    exit();
                }
                $stmt->close();
            } else {
                echo "Lỗi chuẩn bị SQL: " . $conn->error;
            }
        }
    }

    // Xử lý yêu cầu xóa task
    if (isset($_POST['delete_task']) && $task_id) {
        $sql = "DELETE FROM task WHERE task_id = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("i", $task_id);
            $stmt->execute();
            echo $stmt->affected_rows > 0 ? "Nhiệm vụ đã được xóa thành công." : "Lỗi khi xóa nhiệm vụ.";
            $stmt->close();
        } else {
            echo "Lỗi chuẩn bị SQL: " . $conn->error;
        }
        exit();
    }

    // Xử lý cập nhật trạng thái 'star' của task
    if (isset($_POST['star']) && $task_id) {
        $star = intval($_POST['star']);
        $sql = "UPDATE task SET star = ? WHERE task_id = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("ii", $star, $task_id);
            $stmt->execute();
            echo $stmt->affected_rows > 0 ? "Trạng thái ngôi sao đã được cập nhật thành công." : "Không có thay đổi.";
            $stmt->close();
        } else {
            echo "Lỗi chuẩn bị SQL: " . $conn->error;
        }
        exit();
    }
}

// Truy vấn thông tin task theo task_id khi có yêu cầu GET
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['task_id'])) {
    $task_id = intval($_GET['task_id']);
    $sql = "SELECT * FROM task WHERE task_id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $task_id);
        $stmt->execute();
        $result = $stmt->get_result();
        echo json_encode($result->num_rows > 0 ? $result->fetch_assoc() : ['error' => 'Không tìm thấy task.']);
        $stmt->close();
    } else {
        echo json_encode(['error' => 'Lỗi chuẩn bị SQL: ' . $conn->error]);
    }
    exit();
}

// Truy vấn danh sách nhiệm vụ theo ngày
$sql = "SELECT time_start, 
               COUNT(*) AS total_tasks, 
               COUNT(CASE WHEN checked = 1 THEN 1 END) AS completed_tasks, 
               COUNT(CASE WHEN star = 1 THEN 1 END) AS starred_tasks 
        FROM task
        GROUP BY time_start
        ORDER BY time_start ASC";

$result = $conn->query($sql);

$task_summary = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $date = date('Y-m-d', strtotime($row['time_start']));
        $task_summary[$date] = [
            'total_tasks' => $row['total_tasks'],
            'completed_tasks' => $row['completed_tasks'],
            'starred_tasks' => $row['starred_tasks']
        ];
    }
}

// Đóng kết nối
$conn->close();
