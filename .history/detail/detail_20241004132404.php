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
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
    />
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
        padding-right: 2.5rem; /* Để dành chỗ cho icon */
      }

      .date-container i {
        position: absolute;
        right: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none; /* Không ảnh hưởng khi click */
        color: #3b82f6;
      }

      /* Ẩn input mặc định */
      #titleInput {
        display: none;
      }
    </style>
  </head>
  <body class="bg-gray-100 font-sans">
    <!-- Header -->
    <header class="bg-white shadow p-4">
      <div class="max-w-7xl mx-auto flex justify-between items-center">
        <h1 class="text-2xl font-bold text-blue-600">GetItDone</h1>
        <div class="flex items-center">
          <span class="text-gray-700">KietCT</span>
          <span class="text-sm text-gray-500 ml-2">(Admin)</span>
        </div>
      </div>
    </header>

    <!-- Task Details -->
    <div class="max-w-3xl mx-auto mt-6 p-6 bg-white shadow rounded-md">
      <h2 class="text-xl font-bold mb-4">タスク詳細</h2>

      <div class="border-t pt-4">
        <!-- Title and Status -->
        <div class="mb-6">
          <div class="mt-2">
            <span class="font-bold" id="titleText" ondblclick="editTitle()">
              <?php echo htmlspecialchars($task['title']); // Hiển thị title ?>
            </span>
            <input
              type="text"
              id="titleInput"
              class="border border-gray-300 rounded-md p-2"
              value="<?php echo htmlspecialchars($task['title']); ?>" 
              onblur="saveTitle()"
            />
          </div>
          <div class="flex items-center space-x-2 mt-2">
            <label class="inline-flex items-center">
              <i class="fa-regular fa-circle-check text-blue-600 text-xl"></i>
              <span class="ml-2">ステータス</span>
            </label>
            <!-- Nếu task đã hoàn thành thì hiển thị '完了', nếu chưa thì hiển thị '未完了' -->
            <span class="text-red-500 font-bold text-sm px-2 py-1 rounded bg-red-100">
              <?php echo $task['checked'] ? '完了' : '未完了'; ?>
            </span>
          </div>
        </div>

        <!-- Dates -->
        <div class="grid grid-rows-2 gap-4 mb-6">
          <div class="flex items-center">
            <div style="width: 20%">開始日</div>
            <div class="date-container">
              <input
                type="date"
                class="form-control shadow-none"
                name="checkin"
                id="startDateInput"
                required=""
                value="<?php echo htmlspecialchars($task['time_start']); ?>" 
              />
              <i class="fas fa-calendar-alt"></i>
            </div>
          </div>
          <div class="flex items-center">
            <div style="width: 20%">締め切り</div>
            <div class="date-container">
              <input
                type="date"
                class="form-control shadow-none"
                name="checkin"
                id="endDateInput"
                required=""
                value="<?php echo htmlspecialchars($task['time_end']); ?>" 
              />
              <i class="fas fa-calendar-alt"></i>
            </div>
          </div>
        </div>

        <!-- Description -->
        <div class="mb-6">
          <label class="block text-sm text-gray-700" for="description">
            デスクリプション
          </label>
          <textarea
            id="description"
            class="w-full h-24 mt-2 p-2 border border-gray-300 rounded-md"
            placeholder="デスクリプションを入力してください"
          ><?php echo htmlspecialchars($task['description']); ?></textarea> <!-- Hiển thị description -->
        </div>

        <!-- Buttons -->
        <div class="flex justify-end space-x-4">
          <button
            class="cancelbtn px-4 py-2 bg-gray-300 text-gray-700 rounded-md"
          >
            キャンセル
          </button>
          <button class="px-4 py-2 bg-blue-600 text-white rounded-md">
            保存
          </button>
        </div>
      </div>
    </div>

    <script>
      function editTitle() {
        const text = document.getElementById("titleText").textContent;
        const input = document.getElementById("titleInput");
        input.value = text; // Gán giá trị tiêu đề hiện tại vào input
        document.getElementById("titleText").style.display = "none";
        input.style.display = "inline-block";
        input.focus();
      }

      function saveTitle() {
        const input = document.getElementById("titleInput");
        const text = document.getElementById("titleText");
        text.textContent = input.value; // Cập nhật giá trị sau khi chỉnh sửa
        input.style.display = "none";
        text.style.display = "inline";
      }
    </script>

  </body>
</html>
