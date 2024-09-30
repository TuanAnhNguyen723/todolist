<?php
// Kết nối đến CSDL
include '../config.php';

// Kiểm tra nếu form được gửi đi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Kiểm tra kết nối
    if ($conn->connect_error) {
        header("Location: error_page.php?error=db_connection_failed");
        exit;
    }

    // Lấy dữ liệu từ form
    $title = isset($_POST['title']) ? $_POST['title'] : '';
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    $time_start = isset($_POST['time_start']) ? $_POST['time_start'] : '';
    $time_end = isset($_POST['time_end']) ? $_POST['time_end'] : '';
    $user_id = $_SESSION['user_id'] ?? 'default_user_id'; // Lấy từ session
    $grouptask_id = $_POST['grouptask_id'] ?? 'default_group_id'; // Lấy từ form hoặc default

    // Kiểm tra dữ liệu có rỗng và định dạng ngày giờ hay không
    if (empty($title) || empty($time_start) || empty($time_end)) {
        header("Location: your_form_page.php?error=empty_fields");
        exit;
    }

    if (!validateDateTime($time_start) || !validateDateTime($time_end)) {
        header("Location: your_form_page.php?error=invalid_date_format");
        exit;
    }

    // Chuẩn bị câu truy vấn SQL
    $sql = "INSERT INTO task (title, description, time_start, time_end, checked, user_id, grouptask_id) 
            VALUES (?, ?, ?, ?, 0, ?, ?)";

    // Chuẩn bị câu lệnh
    $stmt = $conn->prepare($sql);

    // Ràng buộc các tham số
    if ($stmt === false) {
        header("Location: error_page.php?error=sql_prepare_failed");
        exit;
    }

    $stmt->bind_param("ssssss", $title, $description, $time_start, $time_end, $user_id, $grouptask_id);

    // Thực thi câu lệnh
    if ($stmt->execute()) {
        header("Location: mainscreen.php?success=1&message=Task%20added%20successfully");
        exit;
    } else {
        $error = urlencode($stmt->error);
        header("Location: mainscreen.php?error=insert_failed&message=$error");
        exit;
    }

    // Đóng câu lệnh và kết nối
    $stmt->close();
    $conn->close();
}

// Hàm để xác thực định dạng ngày giờ
function validateDateTime($date, $format = 'Y-m-d H:i:s') {
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}
?>
