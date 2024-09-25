document.addEventListener("DOMContentLoaded", function () {
  // Đặt tất cả mã JavaScript của bạn ở đây
  // Lắng nghe sự kiện click trên phần tử cha
  document.addEventListener("click", function (event) {
    // Khi người dùng click vào checkbox
    if (event.target.classList.contains("toggle-complete")) {
      const taskText = event.target
        .closest(".flex")
        .querySelector(".task-text");
      if (event.target.checked) {
        taskText.classList.add("line-through", "text-gray-400");
      } else {
        taskText.classList.remove("line-through", "text-gray-400");
      }
    }

    // Khi người dùng click vào icon ngôi sao
    if (event.target.classList.contains("fa-star")) {
      const taskText = event.target
        .closest(".flex")
        .querySelector(".task-text");

      // Toggle màu ngôi sao
      event.target.classList.toggle("text-yellow-500");

      // Kiểm tra xem ngôi sao đã được click chưa
      if (event.target.classList.contains("text-yellow-500")) {
        taskText.classList.add("text-yellow-500"); // Thêm màu vàng cho text
      } else {
        taskText.classList.remove("text-yellow-500"); // Bỏ màu vàng khỏi text
      }
    }
  });
});
