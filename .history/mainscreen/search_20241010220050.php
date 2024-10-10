<?php
include '../config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Chuẩn bị câu truy vấn SQL
    $sql = "SELECT * FROM task";
    $params = [];
    $titleCondition = "";

    if (!empty($_POST['title'])) {
        $title = $_POST['title'];
        $titleCondition = " WHERE title LIKE ? ";
        $params[] = $title . '%';
    }

    $sql .= $titleCondition . " ORDER BY time_end DESC";
    
    // Sử dụng Prepared Statements để tránh SQL Injection
    $stmt = $conn->prepare($sql);
    
    // Bind params nếu có tìm kiếm
    if (!empty($params)) {
        $stmt->bind_param("s", ...$params);
    }

    $stmt->execute();
    $query = $stmt->get_result();

    // Nhóm dữ liệu theo ngày
    $tasks_by_date = [];
    while ($row = $query->fetch_assoc()) {
        $date = date('Y-m-d', strtotime($row['time_end']));
        $tasks_by_date[$date][] = $row;
    }

    // Hàm tạo thẻ HTML cho mỗi task
    function renderTask($task) {
        return '
            <div class="task-container2 flex justify-between items-center space-x-4 my-2">
                <div class="flex items-center space-x-4">
                    <input type="checkbox"
                        class="form-checkbox h-5 w-5 toggle-complete ' . ($task['star'] ? 'accent-yellow-500' : '') . '"
                        data-task-id="' . htmlspecialchars($task['task_id']) . '"
                        ' . ($task['checked'] ? 'checked' : '') . ' 
                    />
                    <span class="task-text ' . ($task['checked'] ? 'line-through text-gray-400' : '') . ' ' . ($task['star'] ? 'text-yellow-500' : '') . '">
                        ' . htmlspecialchars($task['title']) . '
                    </span>
                </div>
                <div class="flex space-x-2">
                    <button class="text-blue-500 hover:text-blue-700">
                        <i class="fa fa-eye"></i>
                    </button>
                    <button class="text-gray-500 hover:text-gray-700 edit-task-button" data-task-id="' . htmlspecialchars($task['task_id']) . '">
                        <i class="fa fa-pencil"></i>
                    </button>
                    <form action="./mainscreenController.php" method="POST" style="display:inline;">
                        <input type="hidden" name="task_id" value="' . htmlspecialchars($task['task_id']) . '">
                        <button type="submit" name="delete_task" class="text-red-500 hover:text-red-700">
                            <i class="fa fa-trash"></i>
                        </button>
                    </form>
                    <button class="text-gray-500 hover:text-yellow-300 star-icon ' . ($task['star'] ? 'text-yellow-300' : '') . '" data-task-id="' . htmlspecialchars($task['task_id']) . '">
                        <i class="fa fa-star"></i>
                    </button>
                </div>
            </div>';
    }

    // Tạo HTML cho các tác vụ theo ngày
    $data = '';
    foreach ($tasks_by_date as $date => $tasks) {
        $data .= '<div class="mb-4 border-b border-b-black pt-4">
                    <h3 class="font-bold text-gray-700">' . htmlspecialchars($date) . '</h3>';
        foreach ($tasks as $task) {
            $data .= renderTask($task);
        }
        $data .= '</div>';
    }

    echo $data;
    echo "<script src='mainscreen.js'></script>";
}
?>
