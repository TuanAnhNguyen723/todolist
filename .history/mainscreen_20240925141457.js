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
    this.classList.toggle("text-yellow-300");
    // Toggle màu chữ của task
    if (this.classList.contains("text-yellow-300")) {
      taskText.classList.add("text-yellow-200");
    } else {
      taskText.classList.remove("text-yellow-200");
    }
  });
});

document.addEventListener("DOMContentLoaded", () => {
  // Get elements
  const newTaskButton = document.querySelector(".newtask");
  const editButtons = document.querySelectorAll(".fa-pencil");
  const taskAddModal = document.getElementById("taskAddModal");
  const closeModalButton = document.getElementById("closeModal");
  const cancelButton = document.getElementById("cancelButton");

  // Show modal when clicking on "新規作成" button
  newTaskButton.addEventListener("click", (event) => {
    event.preventDefault();
    taskAddModal.classList.remove("hidden");
  });

  // Add event listeners to edit buttons (if any)
  editButtons.forEach((button) => {
    button.addEventListener("click", (event) => {
      event.preventDefault();
      // Show modal
      taskAddModal.classList.remove("hidden");
    });
  });

  // Add event listener to close modal button
  closeModalButton.addEventListener("click", () => {
    // Hide modal
    taskAddModal.classList.add("hidden");
  });

  // Add event listener to cancel button
  cancelButton.addEventListener("click", () => {
    // Hide modal
    taskAddModal.classList.add("hidden");
  });
});

document.addEventListener("DOMContentLoaded", () => {
  // Get all the eye icon buttons
  const viewButtons = document.querySelectorAll(".fa-eye");

  // Add event listener to each eye button
  viewButtons.forEach((button) => {
    button.addEventListener("click", () => {
      // Redirect to the detail.html page
      window.location.href = "./detail/detail.html";
    });
  });
});

document.addEventListener("DOMContentLoaded", function () {
  const filterModal = document.getElementById("filterModal");
  const filterButton = document.querySelector(".fa-sliders-h");
  const applyButton = document.getElementById("applyButton");
  const resetButton = document.getElementById("resetButton");

  // Hiển thị modal khi nhấn vào icon filter
  filterButton.addEventListener("click", function () {
    filterModal.classList.remove("hidden");
  });

  // Ẩn modal khi nhấn nút '適用' hoặc 'リセット'
  applyButton.addEventListener("click", function () {
    filterModal.classList.add("hidden");
  });

  resetButton.addEventListener("click", function () {
    filterModal.classList.add("hidden");
  });
});
