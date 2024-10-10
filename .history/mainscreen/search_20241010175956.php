<?php
include '../config.php';

// Sử dụng Prepared Statements để tránh SQL Injection
$sql = "SELECT * FROM task WHERE title LIKE ?";
$title = $_POST['title'];
$stmt = $conn->prepare($sql);
$likeTitle = $title . '%';
$stmt->bind_param("s", $likeTitle);
$stmt->execute();
$query = $stmt->get_result();

$data = '';
$tasks_by_date = [];

// Lấy dữ liệu và nhóm theo ngày
while ($row = $query->fetch_assoc()) {
    $date = date('Y-m-d', strtotime($row['time_end']));
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
                        <button class="text-blue-500 hover:text-blue-700 view-task" data-task-id="' . $task['task_id'] . '">
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

?>

<script>
    // Gọi hàm đính kèm sự kiện cho các icon sau khi tải dữ liệu
    document.addEventListener("DOMContentLoaded", function () {
        attachEventListeners();
    });

    function attachEventListeners() {
        // Thêm sự kiện cho các icon
        document.querySelectorAll(".view-task").forEach(function (icon) {
            icon.addEventListener("click", function () {
                const taskId = this.dataset.taskId;
                viewTask(taskId);
            });
        });

        document.querySelectorAll(".edit-task-button").forEach(function (button) {
            button.addEventListener("click", function () {
                const taskId = this.dataset.taskId;
                editTask(taskId);
            });
        });

        document.querySelectorAll(".fa-trash").forEach(function (icon) {
            icon.addEventListener("click", function (event) {
                event.preventDefault(); // Ngăn chặn việc gửi form
                const taskId = this.closest("form").querySelector("input[name='task_id']").value;
                deleteTask(taskId);
            });
        });

        document.querySelectorAll(".star-icon").forEach(function (icon) {
            icon.addEventListener("click", function () {
                const taskId = this.dataset.taskId;
                toggleStar(taskId);
            });
        });
    }

    // Các hàm xử lý logic
    function viewTask(taskId) {
        console.log("Viewing task:", taskId);
        // Logic để xem nhiệm vụ
    }

    function editTask(taskId) {
        console.log("Editing task:", taskId);
        // Logic để chỉnh sửa nhiệm vụ
    }

    function deleteTask(taskId) {
        console.log("Deleting task:", taskId);
        // Gửi yêu cầu xóa đến server (có thể dùng AJAX)
    }

    function toggleStar(taskId) {
        console.log("Toggling star for task:", taskId);
        // Logic để đánh dấu sao
    }
</script>
