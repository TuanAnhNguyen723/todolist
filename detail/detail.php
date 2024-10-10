<!DOCTYPE html>

<?php
include './detailController.php';
?>

<html lang="ja">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>タスク詳細 - GetItDone</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

  <style>
    /* Ẩn icon mặc định của input date */
    input[type="date"]::-webkit-calendar-picker-indicator {
      display: none;
    }

    /* Bố trí lại input với icon lịch */
    .date-container {
      position: relative;
      display: inline-block;
      width: 100%;
    }

    .date-container input {
      width: 100%;
      padding-right: 2.5rem;
      /* Để dành chỗ cho icon */
    }

    .date-container i {
      position: absolute;
      right: 0.75rem;
      top: 50%;
      transform: translateY(-50%);
      pointer-events: none;
      /* Không ảnh hưởng khi click */
      color: #3b82f6;
    }

    /* Ẩn input mặc định */
    #titleInput {
      display: none;
    }


    /* Màu cho trạng thái hoàn thành (完了) */
    #taskStatus.completed {
      background-color: #00aaff;
      color: white;
      padding: 8px 16px;
      border-radius: 12px;
      cursor: pointer;
    }

    /* Màu cho trạng thái chưa hoàn thành (未完了) */
    #taskStatus.not-completed {
      background-color: #ff4b4b;
      color: white;
      padding: 8px 16px;
      border-radius: 12px;
      cursor: pointer;
    }
  </style>
</head>

<body class="bg-gray-100 font-sans">
  <!-- Header -->
  <header class="bg-white shadow px-10 py-2 flex justify-between items-center">
    <div class="flex items-center ms-20">
      <div>
        <svg width="36" height="36" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
          <g clip-path="url(#clip0_10_99)">
            <path
              d="M23.9995 19.6363V28.9309H36.9158C36.3487 31.9199 34.6466 34.4509 32.094 36.1527L39.883 42.1964C44.4212 38.0075 47.0394 31.8547 47.0394 24.5456C47.0394 22.8438 46.8867 21.2073 46.603 19.6366L23.9995 19.6363Z"
              fill="#006BBB" />
            <path
              d="M10.5492 28.5681L8.7925 29.9128L2.57422 34.7564C6.5233 42.589 14.6172 48 23.999 48C30.4788 48 35.9115 45.8618 39.8825 42.1965L32.0934 36.1528C29.9552 37.5928 27.2279 38.4656 23.999 38.4656C17.759 38.4656 12.4574 34.2547 10.559 28.5819L10.5492 28.5681Z"
              fill="#006BBB" />
            <path
              d="M2.57436 13.2437C0.938084 16.4726 0 20.1163 0 23.9999C0 27.8834 0.938084 31.5271 2.57436 34.7561C2.57436 34.7778 10.5599 28.5597 10.5599 28.5597C10.08 27.1197 9.79624 25.5926 9.79624 23.9996C9.79624 22.4067 10.08 20.8795 10.5599 19.4395L2.57436 13.2437Z"
              fill="#006BBB" />
            <path
              d="M23.9995 9.55636C27.5341 9.55636 30.6758 10.7781 33.1849 13.1345L40.0576 6.2619C35.8903 2.37833 30.4796 0 23.9995 0C14.6177 0 6.5233 5.38908 2.57422 13.2437L10.5596 19.44C12.4576 13.7672 17.7595 9.55636 23.9995 9.55636Z"
              fill="#006BBB" />
          </g>
          <defs>
            <clipPath id="clip0_10_99">
              <rect width="48" height="48" fill="white" />
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

  <!-- Task Details -->
  <div class="max-w-5xl mx-auto mt-6 p-8 bg-white shadow rounded-md">
    <h2 class="text-2xl font-bold mb-4">タスク詳細</h2>

    <div class="border-t pt-4">

      <form id="taskDetailForm" action="detail.php?task_id=<?php echo $task['task_id']; ?>" method="POST">
        <!-- Gửi task_id để biết nhiệm vụ nào cần được cập nhật -->
        <input type="hidden" name="task_id" value="<?php echo $task['task_id']; ?>">
        <!-- Title and Status -->
        <div class="mb-6">
          <div class="mt-2">
            <span class="font-bold text-xl" id="titleText" ondblclick="editTitle()">
              <?php echo htmlspecialchars(string: $task['title']); // Hiển thị title ?>
            </span>
            <input type="text" name="title" id="titleInput" class="border border-gray-300 rounded-md p-2"
              value="<?php echo htmlspecialchars($task['title']); ?>" onblur="saveTitle()" />
          </div>
          <div class="flex items-center space-x-2 mt-2">
            <label class="inline-flex items-center">
              <div class="w-50 pr-16">ステータス</div>
              <span id="taskStatus"
                class="ml-3 task-status <?php echo $task['checked'] ? 'completed' : 'not-completed'; ?>"
                onclick="toggleTaskStatus(<?php echo $task['task_id']; ?>)">
                <!-- Hiển thị trạng thái -->
                <?php echo $task['checked'] ? '完了' : '未完了'; ?>
              </span>
            </label>
          </div>
        </div>

        <!-- Dates -->
        <div class="grid grid-rows-2 gap-4 mb-6">
          <div class="flex items-center">
            <div style="width: 20%">開始日</div>
            <div class="date-container">
              <input type="text" class="form-control shadow-none" name="time_start" id="startDateInput"
                value="<?php echo htmlspecialchars(date("Y-m-d", strtotime($task['time_start']))); ?>" required="" />
              <!-- Icon -->
              <i class="fa-regular fa-clock absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>
          </div>
          <div class="flex items-center">
            <div style="width: 20%">締め切り</div>
            <div class="date-container">
              <input type="text" class="form-control shadow-none" name="time_end" id="endDateInput"
                value="<?php echo htmlspecialchars(date("Y-m-d", strtotime($task['time_end']))); ?>" required="" />
              <!-- Icon -->
              <i class="fa-regular fa-clock absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>
          </div>
        </div>

        <!-- Description -->
        <div class="mb-6">
          <label class="block text-sm text-gray-700" for="description">
            デスクリプション
          </label>
          <textarea id="description" name="description" class="w-full h-24 mt-2 p-2 border border-gray-300 rounded-md"
            placeholder="デスクリプションを入力してください"><?php echo htmlspecialchars($task['description']); ?></textarea>
          <!-- Hiển thị description -->
        </div>

        <!-- Buttons -->
        <div class="flex justify-end space-x-4">
          <button class="cancelbtn px-4 py-2 bg-gray-300 text-gray-700 rounded-md">
            キャンセル
          </button>
          <button class="px-4 py-2 bg-blue-600 text-white rounded-md">
            保存
          </button>
        </div>
      </form>
    </div>
  </div>

  <script src="./detail.js"></script>

</body>

</html>