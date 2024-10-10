<?php
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

while ($row = $query->fetch_assoc()) {
    $data .= '
        <?php foreach ($tasks as $task): ?>
            <div class="task-container2 flex justify-between items-center space-x-4 my-2">
                  <div class="flex items-center space-x-4">
                      <input
                          type="checkbox"
                          class="form-checkbox h-5 w-5 toggle-complete ' . ($row['star'] ? 'accent-yellow-500' : '') . '"
                          data-task-id="' . $row['task_id'] . '"
                          ' . ($row['checked'] ? 'checked' : '') . '
                      />
                      <span class="task-text ' . ($row['checked'] ? 'line-through text-gray-400' : '') . ' ' . ($row['star'] ? 'text-yellow-500' : '') . '">
                          ' . htmlspecialchars($row['title']) . '
                      </span>
                  </div>
                  <div class="flex space-x-2">
                      <button class="text-blue-500 hover:text-blue-700">
                          <i class="fa fa-eye"></i>
                      </button>
                      <button class="text-gray-500 hover:text-gray-700 edit-task-button" data-task-id="' . $row['task_id'] . '">
                          <i class="fa fa-pencil"></i>
                      </button>
                      <form action="./mainscreenController.php" method="POST" style="display:inline;">
                          <input type="hidden" name="task_id" value="' . $row['task_id'] . '">
                          <button type="submit" name="delete_task" class="text-red-500 hover:text-red-700">
                              <i class="fa fa-trash"></i>
                          </button>
                      </form>
                      <button class="text-gray-500 hover:text-yellow-300 star-icon ' . ($row['star'] ? 'text-yellow-300' : '') . '" data-task-id="' . $row['task_id'] . '">
                          <i class="fa fa-star"></i>
                      </button>
                  </div>
              </div>
              </php>
              '
              
              ;
}

echo $data;
?>
