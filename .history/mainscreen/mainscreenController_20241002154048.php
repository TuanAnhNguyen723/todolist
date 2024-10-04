<?php
// Kết nối đến CSDL
include '../config.php';

// Kiểm tra nếu form được gửi đi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Xử lý cập nhật trạng thái 'checked' của task
    if (isset($_POST['task_id']) && isset($_POST['checked'])) {
        $task_id = intval($_POST['task_id']);
        $checked = intval($_POST['checked']);

        $sql = "UPDATE task SET checked = ? WHERE task_id = ?";
        $stmt = $conn->prepare($sql);
        
        if ($stmt === false) {
            die('Lỗi chuẩn bị SQL: ' . $conn->error);
        }

        $stmt->bind_param("ii", $checked, $task_id);

        if ($stmt->execute()) {
            echo "Trạng thái nhiệm vụ đã được cập nhật thành công.";
        } else {
            echo "Lỗi khi cập nhật trạng thái nhiệm vụ.";
        }

        $stmt->close();
        $conn->close();
        exit();
    }

    // Xử lý yêu cầu thêm task mới
    if (isset($_POST['title']) && isset($_POST['time_start']) && isset($_POST['time_end'])) {
        $title = $_POST['title'];
        $description = $_POST['description'];
        $time_start = $_POST['time_start'];
        $time_end = $_POST['time_end'];

        if (empty($title) || empty($time_start) || empty($time_end)) {
            header("Location: mainscreen.php?error=empty_fields");
            exit;
        }

        $sql = "INSERT INTO task (title, description, time_start, time_end, checked, user_id, grouptask_id) 
                VALUES (?, ?, ?, ?, 0, 'some_user_id', 'some_group_id')";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die('Lỗi chuẩn bị SQL: ' . $conn->error);
        }

        $stmt->bind_param("ssss", $title, $description, $time_start, $time_end);

        if ($stmt->execute()) {
            header("Location: mainscreen.php?success=1");
            exit();
        } else {
            header("Location: mainscreen.php?error=insert_failed");
            exit();
        }

        $stmt->close();
        $conn->close();
    }

    // Xử lý yêu cầu xóa task
    if (isset($_POST['task_id']) && isset($_POST['delete_task'])) {
        $task_id = intval($_POST['task_id']);

        $sql = "DELETE FROM task WHERE task_id = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die('Lỗi chuẩn bị SQL: ' . $conn->error);
        }

        $stmt->bind_param("i", $task_id);

        if ($stmt->execute()) {
            echo "Nhiệm vụ đã được xóa thành công.";
        } else {
            echo "Lỗi khi xóa nhiệm vụ.";
        }

        $stmt->close();
        $conn->close();
        exit();
    }

    // Xử lý yêu cầu chỉnh sửa (update) task
    if (isset($_POST['edit_task']) && isset($_POST['task_id'])) {
        $task_id = intval($_POST['task_id']);
        $title = $_POST['title'];
        $description = $_POST['description'];
        $time_start = $_POST['time_start'];
        $time_end = $_POST['time_end'];

        // Kiểm tra task_id đã tồn tại và cập nhật dữ liệu
        $sql = "UPDATE task SET title = ?, description = ?, time_start = ?, time_end = ? WHERE task_id = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die('Lỗi chuẩn bị SQL: ' . $conn->error);
        }

        $stmt->bind_param("ssssi", $title, $description, $time_start, $time_end, $task_id);

        if ($stmt->execute()) {
            echo "Nhiệm vụ đã được cập nhật thành công.";
        } else {
            echo "Lỗi khi cập nhật nhiệm vụ.";
        }

        $stmt->close();
        $conn->close();
        exit();
    }

// Xử lý cập nhật trạng thái 'star' của task
if (isset($_POST['task_id']) && isset($_POST['star'])) {
    $task_id = intval($_POST['task_id']);
    $star = intval($_POST['star']);

    $sql = "UPDATE task SET star = ? WHERE task_id = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        die('Lỗi chuẩn bị SQL: ' . $conn->error);
    }

    $stmt->bind_param("ii", $star, $task_id);

    if ($stmt->execute()) {
        echo "Trạng thái ngôi sao đã được cập nhật thành công.";
    } else {
        echo "Lỗi khi cập nhật trạng thái ngôi sao.";
    }

    $stmt->close();
    $conn->close();
    exit();
}

}

if (isset($_GET['task_id'])) {
    $task_id = intval($_GET['task_id']); // Đảm bảo task_id là số nguyên hợp lệ

    // Chuẩn bị câu truy vấn SQL để lấy thông tin task dựa trên task_id
    $sql = "SELECT * FROM task WHERE task_id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die('Lỗi chuẩn bị SQL: ' . $conn->error);
    }

    $stmt->bind_param("i", $task_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Trả về duy nhất một task có task_id đã click
        $task = $result->fetch_assoc();
        
        // Định dạng lại ngày bắt đầu và ngày kết thúc theo yyyy/mm/dd
        $task['time_start'] = date('Y/m/d', strtotime($task['time_start']));
        $task['time_end'] = date('Y/m/d', strtotime($task['time_end']));
        
        echo json_encode($task); // Trả về dữ liệu JSON
    }
    

    $stmt->close();
    $conn->close();
    exit();
}


// Truy vấn tất cả các nhiệm vụ từ bảng task và sắp xếp theo time_start
$sql = "SELECT * FROM task ORDER BY time_start ASC";
$result = $conn->query($sql);

$tasks_by_date = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $date = date('Y-m-d', strtotime($row['time_start']));
        $tasks_by_date[$date][] = $row;
    }
} else {
    $tasks_by_date = []; // Không có dữ liệu
}

// Đóng kết nối
$conn->close();
