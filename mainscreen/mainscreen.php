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
    
    <link rel="stylesheet" href="./mainscreen.css">
    <script src="./mainscreen.js" defer></script>
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>



  </head>
  
  <body class="bg-gray-50">

    <!-- Header Section -->
    <header class="bg-white shadow px-10 py-2 flex justify-between items-center">
      <div class="flex items-center ms-20">
        <div>
          <svg width="36" height="36" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
          <g clip-path="url(#clip0_10_99)">
          <path d="M23.9995 19.6363V28.9309H36.9158C36.3487 31.9199 34.6466 34.4509 32.094 36.1527L39.883 42.1964C44.4212 38.0075 47.0394 31.8547 47.0394 24.5456C47.0394 22.8438 46.8867 21.2073 46.603 19.6366L23.9995 19.6363Z" fill="#006BBB"/>
          <path d="M10.5492 28.5681L8.7925 29.9128L2.57422 34.7564C6.5233 42.589 14.6172 48 23.999 48C30.4788 48 35.9115 45.8618 39.8825 42.1965L32.0934 36.1528C29.9552 37.5928 27.2279 38.4656 23.999 38.4656C17.759 38.4656 12.4574 34.2547 10.559 28.5819L10.5492 28.5681Z" fill="#006BBB"/>
          <path d="M2.57436 13.2437C0.938084 16.4726 0 20.1163 0 23.9999C0 27.8834 0.938084 31.5271 2.57436 34.7561C2.57436 34.7778 10.5599 28.5597 10.5599 28.5597C10.08 27.1197 9.79624 25.5926 9.79624 23.9996C9.79624 22.4067 10.08 20.8795 10.5599 19.4395L2.57436 13.2437Z" fill="#006BBB"/>
          <path d="M23.9995 9.55636C27.5341 9.55636 30.6758 10.7781 33.1849 13.1345L40.0576 6.2619C35.8903 2.37833 30.4796 0 23.9995 0C14.6177 0 6.5233 5.38908 2.57422 13.2437L10.5596 19.44C12.4576 13.7672 17.7595 9.55636 23.9995 9.55636Z" fill="#006BBB"/>
          </g>
          <defs>
          <clipPath id="clip0_10_99">
          <rect width="48" height="48" fill="white"/>
          </clipPath>
          </defs>
          </svg>

        </div>

        <h1 class="text-3xl font-bold text-blue-500 mt-3">
            etItDone
        </h1>
      </div>
      <div class="flex items-center me-20">
        <span class="text-black mr-4 text-xs font-medium">
            <p class="">KietCT</p>
            <p class="text-gray-500">Admin</p>
        </span>
        <i class="fa-solid fa-chevron-down text-blue-500"></i>
      </div>
    </header>

    <!-- Main Section -->
    <main class="container mx-auto mt-5 max-w-screen-xl ">
      <div class="flex justify-between">
        <!-- Task List -->
        <div class="w-full bg-white p-4 shadow rounded-lg">
          <div class="flex justify-between items-center mb-4">
            <!-- Task List Title -->
            <h2 class="text-xl font-bold">タスク一覧</h2>

            <div class="flex items-center space-x-4">
              <!-- Today Icon -->
              <button id="todayButton" class="text-blue-500 border border-blue-500 px-2 py-1 rounded-lg hover:bg-blue-500 hover:text-white">
                今日
              </button>

              <!-- Filter Icon -->
              <button class="text-blue-500 hover:text-gray-700 rotate-90 hidden">
                <i class="fas fa-sliders-h"></i>
              </button>

              <!-- Search Input with Icon -->
              <div class="relative">
                <input
                  type="text"
                  class="border rounded-lg pl-10 pr-3 py-2 w-72"
                  placeholder="タスク名を検索してください"
                  id="searchInput"
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
        <div class="flex justify-between border-t-black border-t-2 pt-4 ">
          <!-- Task List -->
          <div class="w-2/3" style="height: 70vh; overflow-y: scroll; scrollbar-width: none;">
            <!-- Task Groups by Date -->
            <div class="task-container me-3">
              <!-- Thêm ID cho các task, để dễ dàng truy cập vào chúng trong JavaScript -->
              <?php foreach ($tasks_by_date as $date => $tasks): ?>
                <div class="mb-4 border-b border-b-black pt-4" data-date="<?php echo $date; ?>">
                  <h3 class="font-bold text-gray-700">
                    <?php echo htmlspecialchars($date); ?>
                  </h3>
                  <?php foreach ($tasks as $task): ?>
                    <div class="task-container2 flex justify-between items-center space-x-4 my-2" id="task-<?php echo $task['task_id']; ?>">
                      <div class="flex items-center space-x-4">
                        <input
                          type="checkbox"
                          class="form-checkbox h-5 w-5 toggle-complete 
                          <?php echo $task['star'] ? 'accent-yellow-500' : ''; ?>"
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
                          <i class="fa-regular fa-eye"></i>
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

                        <!-- Nút sao -->
                        <button class="text-gray-500 hover:text-yellow-300 star-icon <?php echo $task['star'] ? 'text-yellow-300' : ''; ?>" data-task-id="<?php echo $task['task_id']; ?>">
                          <i class="fa fa-star"></i>
                        </button>
                      </div>
                      
                      <!-- Thêm ngày kết thúc vào đây -->
                      <span class="task-end-date hidden"><?php echo htmlspecialchars($task['time_end']); ?></span>
                    </div>
                  <?php endforeach; ?>

                </div>
              <?php endforeach; ?>

            </div>
          </div>

          <!-- Task Completion Summary -->
          <div id="task-summary" class="w-1/3 bg-grey-900 p-6 rounded-lg shadow-lg ml-4 bg-neutral-100 border-2" style="height: 490px; overflow-y: scroll; scrollbar-width: none;">
            <?php if (!empty($task_summary)): ?>
              <?php foreach ($task_summary as $date => $summary): ?>
                <h3 class="font-bold text-gray-700 cursor-pointer"><?php echo htmlspecialchars($date); ?>:</h3>
                <ul class="list-disc pl-5 mb-4 border-b border-b-black pt-4">
                  <li>完了タスク: 
                    <span class="text-blue-500">
                      <?php echo htmlspecialchars($summary['completed_tasks']) . '/' . htmlspecialchars($summary['total_tasks']); ?>
                    </span>
                  </li>
                  <li class="pb-4" >スタータスク: 
                    <span class="text-blue-500">
                      <?php echo htmlspecialchars($summary['starred_tasks']) . '/' . htmlspecialchars($summary['total_tasks']); ?>
                    </span>
                  </li>
                </ul>
                <?php endforeach; ?>
                <?php else: ?>
                  <p>データがありません。</p>
                <?php endif; ?>
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
                  placeholder="タイトルを入力してください"
                  class="title w-full border border-gray-300 p-2 rounded-lg mb-4"
                />

                <!-- Date Pickers -->
                <div class="flex justify-between space-x-4 mb-4">
                  <div class="w-1/2 relative">
                    <input
                      type="text"
                      name="time_start"
                      class="w-full border border-gray-300 p-2 rounded-lg"
                      placeholder="開始日"
                    />
                    <!-- Icon -->
                <i class="fa-regular fa-clock absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                  </div>
                  <div class="w-1/2 relative">
                    <input
                      type="text"
                      name="time_end"
                      class="w-full border border-gray-300 p-2 rounded-lg"
                      placeholder="終了日"
                    />
                    <!-- Icon -->
                <i class="fa-regular fa-clock absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                  </div>
                </div>

                <!-- Task Description -->
                <textarea
                  name="description"
                  placeholder="ディスクリプを入力してください"
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
        <div id="filterModal" class="fixed inset-0 bg-black bg-opacity-30 flex justify-center items-center hidden">
          <div class="bg-white p-6 rounded-lg shadow-lg w-80">
            <h2 class="text-xl font-bold mb-4">フィルターオプション</h2>

            <!-- Status Dropdown -->
            <div class="mb-4 relative ">
              <label class="block text-gray-700 font-semibold pb-2"
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
                <input 
                    type="text" 
                    name="edit_time_start" 
                    class="edit-time-start w-full border border-gray-300 p-2 rounded-lg" 
                    placeholder="yyyy-mm-dd" 
                />
                <!-- Icon -->
                <i class="fa-regular fa-clock absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
              </div>
              <div class="w-1/2 relative">
                <label for="edit_time_end" class="sr-only">End Date</label>
                <input 
                    type="text" 
                    name="edit_time_end" 
                    class="edit-time-end w-full border border-gray-300 p-2 rounded-lg" 
                    placeholder="yyyy-mm-dd"
                />
                <!-- Icon -->
                <i class="fa-regular fa-clock absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
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
