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

    // Kiểm tra task_id để xác định là thêm mới hay chỉnh sửa
    $task_id = isset($_POST['task_id']) ? intval($_POST['task_id']) : 0;

    if ($task_id > 0) {
        // Chỉnh sửa task hiện có
        if (isset($_POST['title']) && isset($_POST['time_start']) && isset($_POST['time_end'])) {
            $title = $_POST['title'];
            $description = $_POST['description'];
            $time_start = $_POST['time_start'];
            $time_end = $_POST['time_end'];

            $sql = "UPDATE task SET title = ?, description = ?, time_start = ?, time_end = ? WHERE task_id = ?";
            $stmt = $conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("ssssi", $title, $description, $time_start, $time_end, $task_id);
                if ($stmt->execute()) {
                    echo "Nhiệm vụ đã được cập nhật thành công.";
                } else {
                    echo "Lỗi khi cập nhật nhiệm vụ.";
                }
                $stmt->close();
            } else {
                echo "Lỗi chuẩn bị SQL: " . $conn->error;
            }
        }
    } else {
        // Thêm mới task
        if (isset($_POST['title']) && isset($_POST['time_start']) && isset($_POST['time_end'])) {
            $title = $_POST['title'];
            $description = $_POST['description'];
            $time_start = $_POST['time_start'];
            $time_end = $_POST['time_end'];

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

// Truy vấn tất cả các nhiệm vụ từ bảng task và nhóm theo ngày
$sql = "SELECT * FROM task";
$result = $conn->query($sql);

$data = '';

// Khởi tạo một mảng để nhóm tác vụ theo ngày
$tasks_by_date = [];

// Lấy dữ liệu và nhóm theo ngày
while ($row = $result->fetch_assoc()) {
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

// Đóng kết nối
$conn->close();
?>
