// Lắng nghe sự kiện click trên phần tử cha
document.addEventListener("click", function (event) {
  // Khi người dùng click vào checkbox
  if (event.target.classList.contains("toggle-complete")) {
    const taskText = event.target.parentElement.querySelector(".task-text");
    if (event.target.checked) {
      taskText.classList.add("line-through", "text-gray-400");
    } else {
      taskText.classList.remove("line-through", "text-gray-400");
    }
  }

  // Khi người dùng click vào icon ngôi sao
  if (event.target.classList.contains("fa-star")) {
    const taskText =
      event.target.parentElement.parentElement.querySelector(".task-text");
    // Toggle màu ngôi sao
    event.target.classList.toggle("text-yellow-500");
    // Toggle màu chữ của task
    if (event.target.classList.contains("text-yellow-500")) {
      taskText.classList.add("text-yellow-600");
    } else {
      taskText.classList.remove("text-yellow-600");
    }
  }
});
