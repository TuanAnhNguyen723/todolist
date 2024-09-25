// Lấy các phần tử từ DOM
const editButtons = document.querySelectorAll(".fa-pencil"); // Tất cả các icon bút
const taskSummary = document.getElementById("task-summary"); // Phần popup Task Completion Summary
const closePopupButton = document.getElementById("close-popup"); // Nút đóng

// Khi người dùng bấm vào icon bút
editButtons.forEach((button) => {
  button.addEventListener("click", () => {
    taskSummary.classList.remove("hidden"); // Hiển thị pop-up
  });
});

// Khi người dùng bấm vào nút Đóng trong pop-up
closePopupButton.addEventListener("click", () => {
  taskSummary.classList.add("hidden"); // Ẩn pop-up
});

// Xử lý sự kiện khi click vào checkbox để gạch ngang hoặc bỏ gạch ngang
document.querySelectorAll(".toggle-complete").forEach((checkbox) => {
  checkbox.addEventListener("change", function () {
    const taskText = this.parentElement.querySelector(".task-text");
    if (this.checked) {
      taskText.classList.add("line-through", "text-gray-400");
    } else {
      taskText.classList.remove("line-through", "text-gray-400");
    }
  });
});

// Xử lý sự kiện khi click vào icon ngôi sao
document.querySelectorAll(".star-icon").forEach((star) => {
  star.addEventListener("click", function () {
    const taskText =
      this.parentElement.parentElement.querySelector(".task-text");
    // Toggle màu ngôi sao
    this.classList.toggle("text-yellow-500");
    // Toggle màu chữ của task
    if (this.classList.contains("text-yellow-500")) {
      taskText.classList.add("text-yellow-600");
    } else {
      taskText.classList.remove("text-yellow-600");
    }
  });
});
