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

document.addEventListener("DOMContentLoaded", () => {
  // Get elements
  const editButtons = document.querySelectorAll(".fa-pencil");
  const taskEditModal = document.getElementById("taskEditModal");
  const closeModalButton = document.getElementById("closeModal");

  // Add event listeners to edit buttons
  editButtons.forEach((button) => {
    button.addEventListener("click", (event) => {
      event.preventDefault();
      // Show modal
      taskEditModal.classList.remove("hidden");
    });
  });

  // Add event listener to close modal button
  closeModalButton.addEventListener("click", () => {
    // Hide modal
    taskEditModal.classList.add("hidden");
  });
});
