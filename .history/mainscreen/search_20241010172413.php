<!-- <?php
include '../config.php';

$title = $_POST['title'];

// Sử dụng Prepared Statements để tránh SQL Injection
$sql = "SELECT * FROM task WHERE title LIKE ?";
$stmt = $conn->prepare($sql);
$likeTitle = $title . '%';
$stmt->bind_param("s", $likeTitle);
$stmt->execute();
$query = $stmt->get_result();
$data = '';

// Khởi tạo một mảng để nhóm tác vụ theo ngày
$tasks_by_date = [];

// Lấy dữ liệu và nhóm theo ngày
while ($row = $query->fetch_assoc()) {
    // Giả sử bạn có trường 'date' trong bảng task
    $date = $row['date']; // Thay đổi 'date' thành tên trường thực tế của bạn
    $tasks_by_date[$date][] = $row;
}

// Tạo HTML cho các tác vụ theo từng ngày
foreach ($tasks_by_date as $date => $tasks) {
    $data .= '
        <div class="mb-4 border-b border-b-black pt-4">
            <h3 class="font-bold text-gray-700">' . htmlspecialchars($date) . '</h3>';

    foreach ($tasks as $task) {
        $data .= '
                <div class="task-container2 flex justify-between items-center space-x-4 my-2">
                    <div class="flex items-center space-x-4">
                        <input
                            type="checkbox"
                            class="form-checkbox h-5 w-5 toggle-complete ' . ($task['star'] ? 'accent-yellow-500' : '') . '"
                            data-task-id="' . $task['task_id'] . '"
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
                        <button class="text-gray-500 hover:text-gray-700 edit-task-button" data-task-id="' . $task['task_id'] . '">
                            <i class="fa fa-pencil"></i>
                        </button>
                        <form action="./mainscreenController.php" method="POST" style="display:inline;">
                            <input type="hidden" name="task_id" value="' . $task['task_id'] . '">
                            <button type="submit" name="delete_task" class="text-red-500 hover:text-red-700">
                                <i class="fa fa-trash"></i>
                            </button>
                        </form>
                        <button class="text-gray-500 hover:text-yellow-300 star-icon ' . ($task['star'] ? 'text-yellow-300' : '') . '" data-task-id="' . $task['task_id'] . '">
                            <i class="fa fa-star"></i>
                        </button>
                    </div>
                </div>';
    }

    $data .= '</div>'; // Đóng div cho ngày
}

echo $data;
?> -->
