<?php
// Kết nối tới database
$host = 'localhost'; // Thay thế bằng thông tin kết nối của bạn
$dbname = 'todolistDb'; 
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Lấy dữ liệu từ yêu cầu AJAX
    $title = $_POST['title'];
    $description = $_POST['description'];
    $time_start = $_POST['time_start'];
    $time_end = $_POST['time_end'];

    // Câu lệnh SQL để thêm task vào cơ sở dữ liệu
    $sql = "INSERT INTO task (title, description, time_start, time_end) VALUES (:title, :description, :time_start, :time_end)";
    $stmt = $pdo->prepare($sql);

    // Gán giá trị vào câu lệnh SQL
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':time_start', $time_start);
    $stmt->bindParam(':time_end', $time_end);

    // Thực thi câu lệnh SQL
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }

} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
