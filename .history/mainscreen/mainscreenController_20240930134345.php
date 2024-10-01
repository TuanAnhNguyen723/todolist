<?php
// Kết nối đến CSDL
include '../config.php';

// Kiểm tra nếu form được gửi đi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Kiểm tra kết nối
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Xử lý cập nhật trạng thái 'checked' của task
    if (isset($_POST['task_id']) && isset($_POST['checked'])) {
        $task_id = intval($_POST['task_id']);
        $checked = intval($_POST['checked']);

        // Cập nhật trạng thái checked trong CSDL
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

        // Đóng kết nối
        $stmt->close();
        $conn->close();
        exit();
    }

    // Xử lý thêm task mới
    if (isset($_POST['title']) && isset($_POST['time_start']) && isset($_POST['time_end'])) {
        $title = $_POST['title'];
        $description = $_POST['description'];
        $time_start = $_POST['time_start'];
        $time_end = $_POST['time_end'];

        // Kiểm tra dữ liệu có rỗng hay không
        if (empty($title) || empty($time_start) || empty($time_end)) {
            header("Location: mainscreen.php?error=empty_fields");
            exit;
        }

        // Chuẩn bị câu truy vấn SQL để thêm task mới
        $sql = "INSERT INTO task (title, description, time_start, time_end, checked, user_id, grouptask_id) 
                VALUES (?, ?, ?, ?, 0, 'some_user_id', 'some_group_id')";

        // Chuẩn bị câu lệnh
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die('Lỗi chuẩn bị SQL: ' . $conn->error);
        }

        // Ràng buộc các tham số
        $stmt->bind_param("ssss", $title, $description, $time_start, $time_end);

        // Thực thi câu lệnh
        if ($stmt->execute()) {
            // Sau khi thêm thành công, chuyển hướng với thông báo
            header("Location: mainscreen.php?success=1");
            exit();
        } else {
            // Chuyển hướng với thông báo lỗi
            header("Location: mainscreen.php?error=insert_failed");
            exit();
        }

        // Đóng câu lệnh và kết nối
        $stmt->close();
        $conn->close();
    }

    // Xử lý yêu cầu xóa task
    if (isset($_POST['task_id']) && isset($_POST['delete_task'])) {
        $task_id = intval($_POST['task_id']);
        
        // Câu truy vấn SQL để xóa task
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

        // Đóng kết nối
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

        // Chuẩn bị câu truy vấn SQL để cập nhật thông tin task
        $sql = "UPDATE task SET title = ?, description = ?, time_start = ?, time_end = ? WHERE task_id = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die('Lỗi chuẩn bị SQL: ' . $conn->error);
        }

        // Ràng buộc các tham số và thực thi câu lệnh
        $stmt->bind_param("ssssi", $title, $description, $time_start, $time_end, $task_id);

        if ($stmt->execute()) {
            echo "Nhiệm vụ đã được cập nhật thành công.";
        } else {
            echo "Lỗi khi cập nhật nhiệm vụ.";
        }

        // Đóng câu lệnh và kết nối
        $stmt->close();
        $conn->close();
        exit();
    }
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
} else {
    $tasks_by_date = []; // Không có dữ liệu
}

// Xử lý yêu cầu lấy thông tin task qua AJAX
if (isset($_GET['task_id'])) {
    $task_id = intval($_GET['task_id']);

    // Chuẩn bị câu truy vấn SQL để lấy thông tin task từ database
    $sql = "SELECT * FROM task WHERE task_id = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        die('Lỗi chuẩn bị SQL: ' . $conn->error);
    }

    $stmt->bind_param("i", $task_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $task = $result->fetch_assoc();
        // Trả về dữ liệu dưới dạng JSON
        echo json_encode($task);
    } else {
        echo json_encode(['error' => 'Không tìm thấy task.']);
    }

    $stmt->close();
    $conn->close();
    exit();
}


// Đóng kết nối sau khi truy vấn
$conn->close();
?>
