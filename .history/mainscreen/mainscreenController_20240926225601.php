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

// Kiểm tra nếu yêu cầu là Ajax và nhận dữ liệu JSON
if ($_SERVER['REQUEST_METHOD'] == 'POST' && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
    // Đặt tiêu đề trả về là JSON
    header('Content-Type: application/json');

    // Lấy dữ liệu từ body của yêu cầu Ajax
    $data = json_decode(file_get_contents('php://input'), true);

    // Kiểm tra xem dữ liệu có được nhận chính xác không
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode(['success' => false, 'error' => 'Invalid JSON format']);
        exit;
    }

    $task_id = isset($data['task_id']) ? intval($data['task_id']) : 0;
    $checked = isset($data['checked']) ? intval($data['checked']) : 0;

    // Kiểm tra nếu task_id hợp lệ
    if ($task_id > 0) {
        // Chuẩn bị câu truy vấn cập nhật trạng thái `checked`
        $sql = "UPDATE task SET checked = ? WHERE task_id = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            echo json_encode(['success' => false, 'error' => $conn->error]);
            exit;
        }

        // Ràng buộc tham số và thực thi câu lệnh
        $stmt->bind_param("ii", $checked, $task_id);
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => $conn->error]);
        }

        // Đóng câu lệnh
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid task ID']);
    }
    exit;
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request or Content-Type']);
}


?>
