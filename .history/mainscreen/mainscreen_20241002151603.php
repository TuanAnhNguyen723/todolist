<!DOCTYPE html>
<?php
include '../mainscreen/mainscreenController.php';
?>

<html lang="ja">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>GetItDone Task List</title>
    <!-- <link
      href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css"
      rel="stylesheet"
    /> -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
    />
    
    <link href="./mainscreen.css">
    <script src="./mainscreen.js" defer></script>
    <script src="https://cdn.tailwindcss.com"></script>


  </head>
  <body class="bg-gray-50">
    <!-- Header Section -->
    <header class="bg-white shadow p-4 flex justify-between items-center">
      <div class="flex items-center">
      <h1 class="text-3xl font-bold text-blue-500">
          <span class="text-5xl text-blue-800">G</span>etItDone
      </h1>
      </div>
      <div class="flex items-center">
        <span class="text-gray-700 mr-4"
          >KietCT <span class="text-sm text-gray-500">(Admin)</span></span
        >
      </div>
    </header>

    <!-- Main Section -->
    <main class="container mx-auto mt-8">
      <div class="flex justify-between">
        <!-- Task List -->
        <div class="w-2/3 bg-white p-4 shadow rounded-lg">
          <div class="flex justify-between items-center mb-4">
            <!-- Task List Title -->
            <h2 class="text-xl font-bold">タスク一覧</h2>

            <div class="flex items-center space-x-4">
              <!-- Filter Icon -->
              <button class="text-blue-500 hover:text-gray-700">
                <i class="fas fa-sliders-h"></i>
              </button>

              <!-- Search Input with Icon -->
              <div class="relative">
                <input
                  type="text"
                  class="border rounded-lg pl-10 pr-3 py-2 w-64"
                  placeholder="タスクを入力してください"
                />
                <!-- Search Icon -->
                <i class="fas fa-search absolute left-3 top-3 text-gray-500"></i>
              </div>

              <!-- New Task Button -->
              <button class="newtask bg-blue-500 text-white px-4 py-2 rounded-lg">
                + 新規作成
              </button>
            </div>
          </div>

        <!-- Tạo một khối Flex cho task list và task summary -->
        <div class="flex justify-between border-t border-4 pt-4">
          <!-- Task List -->
          <div class="w-2/3">
            <!-- Task Groups by Date -->
            <div class="task-container">
              <?php foreach ($tasks_by_date as $date => $tasks): ?>
                <div class="mb-4 border-b pt-4">
                  <h3 class="font-bold text-gray-700"><?php echo htmlspecialchars($date); ?></h3>

                  <?php foreach ($tasks as $task): ?>
                    <div class="task-container2 flex justify-between items-center space-x-4 my-2">
                      <div class="flex items-center space-x-4">
                        <input
                          type="checkbox"
                          class="form-checkbox h-5 w-5 toggle-complete <?php echo $task['star'] ? 'accent-yellow-500' : ''; ?>"
                          data-task-id="<?php echo $task['task_id']; ?>"
                          <?php echo $task['checked'] ? 'checked' : ''; ?>
                        />
                        <span class="task-text <?php echo $task['checked'] ? 'line-through text-gray-400' : ''; ?> <?php echo $task['star'] ? 'text-yellow-500' : ''; ?>">
                          <?php echo htmlspecialchars($task['title']); ?>
                        </span>
                      </div>
                      <div class="flex space-x-2">
                        <!-- Nút xem chi tiết -->
                        <button class="text-blue-500 hover:text-blue-700">
                          <i class="fa fa-eye"></i>
                        </button>

                        <!-- Nút sửa -->
                        <button class="text-gray-500 hover:text-gray-700 edit-task-button" data-task-id="<?php echo $task['task_id']; ?>">
                          <i class="fa fa-pencil"></i>
                        </button>

                        <!-- Nút xóa -->
                        <form action="./mainscreenController.php" method="POST" style="display:inline;">
                          <input type="hidden" name="task_id" value="<?php echo $task['task_id']; ?>">
                          <button type="submit" name="delete_task" class="text-red-500 hover:text-red-700">
                            <i class="fa fa-trash"></i>
                          </button>
                        </form>

                        <!-- // Kết hợp trạng thái star -->
                        <button class="text-gray-500 hover:text-yellow-300 star-icon <?php echo $task['star'] ? 'text-yellow-300' : ''; ?>" data-task-id="<?php echo $task['task_id']; ?>">
                          <i class="fa fa-star"></i>
                        </button>
                      </div>
                    </div>
                  <?php endforeach; ?>
                </div>
              <?php endforeach; ?>
            </div>
          </div>

              <!-- Task Completion Summary -->
              <div id="task-summary" class="w-1/3 bg-grey-900 p-6 rounded-lg shadow-lg ml-4">
                <h3 class="font-bold text-gray-700">28/08/2024:</h3>
                <ul class="list-disc pl-5">
                  <li>完了タスク: <span class="text-blue-500">1/2</span></li>
                  <li>スタータスク: <span class="text-blue-500">0</span></li>
                </ul>

                <h3 class="font-bold text-gray-700 mt-4 border-t pt-4">
                  29/08/2024:
                </h3>
                <ul class="list-disc pl-5">
                  <li>完了タスク: <span class="text-blue-500">2/3</span></li>
                  <li>スタータスク: <span class="text-blue-500">1</span></li>
                </ul>
              </div>
            </div>
        </div>


        <!-- Task Add Modal -->
        <div id="taskAddModal" class="fixed z-50 inset-0 overflow-y-auto hidden">
          <div class="flex items-center justify-center min-h-screen bg-black bg-opacity-50">
            <div class="relative bg-white w-96 p-6 rounded-lg shadow-lg">
              <!-- Close Button -->
              <button id="closeModal" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
              </button>

              <!-- Modal Title -->
              <h2 class="text-2xl font-bold mb-4">タスク追加</h2>

              <!-- Form -->

              <form action="mainscreenController.php" method="POST">
                <!-- Task Title -->
                <input
                  name="title"
                  type="text"
                  placeholder="タイトルが入力してください"
                  class="title w-full border border-gray-300 p-2 rounded-lg mb-4"
                />

                <!-- Date Pickers -->
                <div class="flex justify-between space-x-4 mb-4">
                  <div class="w-1/2 relative">
                    <label for="time_start" class="sr-only">Start Date</label>
                    <input
                      type="date"
                      name="time_start"
                      class="w-full border border-gray-300 p-2 rounded-lg"
                    />
                  </div>
                  <div class="w-1/2 relative">
                    <label for="time_end" class="sr-only">End Date</label>
                    <input
                      type="date"
                      name="time_end"
                      class="w-full border border-gray-300 p-2 rounded-lg"
                    />
                  </div>
                </div>

                <!-- Task Description -->
                <textarea
                  name="description"
                  placeholder="ディスクリプが入力してください"
                  class="w-full border border-gray-300 p-2 rounded-lg mb-4"
                  rows="4"
                ></textarea>

                <!-- Buttons -->
                <div class="flex justify-between">
                  <button
                    type="submit"
                    class="bg-blue-500 text-white px-4 py-2 rounded-lg w-full mr-2"
                  >
                    作成
                  </button>
                  <button
                    type="button"
                    class="border border-blue-500 text-blue-500 px-4 py-2 rounded-lg w-full"
                    id="cancelButton"
                  >
                    キャンセル
                  </button>
                </div>
              </form>

            </div>
          </div>
        </div>

        <!-- Filter Modal -->
        <div
          id="filterModal"
          class="fixed inset-0 bg-black bg-opacity-30 flex justify-center items-center hidden"
        >
          <div class="bg-white p-6 rounded-lg shadow-lg w-80">
            <h2 class="text-xl font-bold mb-4">フィルターオプション</h2>

            <!-- Status Dropdown -->
            <div class="mb-4 relative">
              <label class="block text-gray-700 font-semibold"
                >ステータス</label
              >
              <button
                id="statusButton"
                class="bg-gray-200 border border-gray-300 p-2 w-full text-left rounded-lg flex justify-between items-center"
              >
                <span id="statusText">全ステータス</span>
                <i class="fas fa-chevron-down"></i>
              </button>

              <ul
                id="statusDropdown"
                class="absolute bg-white border border-gray-300 rounded-lg w-full hidden mt-1 z-10"
              >
                <li
                  class="p-2 hover:bg-blue-500 hover:text-white cursor-pointer"
                  data-value="全ステータス"
                >
                  全ステータス
                </li>
                <li
                  class="p-2 hover:bg-blue-500 hover:text-white cursor-pointer"
                  data-value="完了"
                >
                  完了
                </li>
                <li
                  class="p-2 hover:bg-blue-500 hover:text-white cursor-pointer"
                  data-value="未完了"
                >
                  未完了
                </li>
              </ul>
            </div>

            <!-- Date Picker -->
            <div class="flex items-center mb-4">
              <i class="fas fa-calendar mr-2"></i>
              <span>締め切り</span>
            </div>

            <!-- Star Task Checkbox -->
            <div class="flex items-center mb-4">
              <input type="checkbox" id="starTask" class="mr-2" />
              <label for="starTask">スタータスク</label>
            </div>

            <!-- Buttons -->
            <div class="flex justify-between">
              <button
                id="resetButton"
                class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg"
              >
                リセット
              </button>
              <button
                id="applyButton"
                class="bg-blue-500 text-white px-4 py-2 rounded-lg"
              >
                適用
              </button>
            </div>
          </div>
        </div>


     <!-- Modal chỉnh sửa task -->
      <div id="taskEditModal" class="fixed z-50 inset-0 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen bg-black bg-opacity-50">
          <div class="relative bg-white w-96 p-6 rounded-lg shadow-lg">
            <!-- Close Button -->
            <button id="closeEditModal" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">
              <i class="fas fa-times"></i>
            </button>



            <!-- Modal Content -->
            <h2 class="text-2xl font-bold mb-4">タスク編集</h2>
            <!-- Form -->
            <form id="editTaskForm" action="mainscreenController.php" method="POST">
              <!-- Task ID (ẩn) để biết nhiệm vụ nào cần chỉnh sửa -->
              <input type="hidden" name="edit_task_id" value="">

              <!-- Task Title -->
              <input name="edit_title" type="text" placeholder="タイトルを入力してください" class="edit-title w-full border border-gray-300 p-2 rounded-lg mb-4" />

              <!-- Date Pickers -->
              <div class="flex justify-between space-x-4 mb-4">
                <div class="w-1/2 relative">
                  <label for="edit_time_start" class="sr-only">Start Date</label>
                  <input type="date" name="edit_time_start" class="edit-time-start w-full border border-gray-300 p-2 rounded-lg" />
                </div>
                <div class="w-1/2 relative">
                  <label for="edit_time_end" class="sr-only">End Date</label>
                  <input type="date" name="edit_time_end" class="edit-time-end w-full border border-gray-300 p-2 rounded-lg" />
                </div>
              </div>

              <!-- Task Description -->
              <textarea name="edit_description" placeholder="ディスクリプションを入力してください" class="edit-description w-full border border-gray-300 p-2 rounded-lg mb-4" rows="4"></textarea>

              <!-- Buttons -->
              <div class="flex justify-between">
                <button id="saveEditButton" class="bg-blue-500 text-white px-4 py-2 rounded-lg w-full mr-2">
                  保存
                </button>
                <button type="button" class="border border-blue-500 text-blue-500 px-4 py-2 rounded-lg w-full" id="cancelEditButton">
                  キャンセル
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

      </div>
    </main>

    <!-- Icons FontAwesome -->
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
  </body>
</html>
