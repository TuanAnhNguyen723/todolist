document.addEventListener("DOMContentLoaded", function () {
  attachEventListeners();
});

function attachEventListeners() {
  // Thêm sự kiện cho icon mắt
  document.querySelectorAll(".fa-eye").forEach(function (icon) {
    icon.addEventListener("click", function () {
      const taskId = this.closest(".task-container2").querySelector(
        "input[data-task-id]"
      ).dataset.taskId;
      viewTask(taskId);
    });
  });

  // Thêm sự kiện cho icon bút
  document.querySelectorAll(".edit-task-button").forEach(function (button) {
    button.addEventListener("click", function () {
      const taskId = this.dataset.taskId;
      editTask(taskId);
    });
  });

  // Thêm sự kiện cho icon xóa
  document.querySelectorAll(".fa-trash").forEach(function (icon) {
    icon.addEventListener("click", function (event) {
      event.preventDefault(); // Ngăn chặn việc gửi form
      const taskId = this.closest("form").querySelector(
        "input[name='task_id']"
      ).value;
      deleteTask(taskId);
    });
  });

  // Thêm sự kiện cho icon ngôi sao
  document.querySelectorAll(".star-icon").forEach(function (icon) {
    icon.addEventListener("click", function () {
      const taskId = this.dataset.taskId;
      toggleStar(taskId);
    });
  });
}

// Các hàm xử lý logic
function viewTask(taskId) {
  // Logic để xem nhiệm vụ
  console.log("Viewing task:", taskId);
}

function editTask(taskId) {
  // Logic để chỉnh sửa nhiệm vụ
  console.log("Editing task:", taskId);
}

function deleteTask(taskId) {
  // Logic để xóa nhiệm vụ
  console.log("Deleting task:", taskId);
  // Gửi yêu cầu xóa đến server (có thể dùng AJAX)
}

function toggleStar(taskId) {
  // Logic để đánh dấu sao
  console.log("Toggling star for task:", taskId);
}
