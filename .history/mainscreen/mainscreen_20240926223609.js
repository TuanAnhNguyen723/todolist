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
  // Get elements for the add task modal
  const newTaskButton = document.querySelector(".newtask");
  const taskAddModal = document.getElementById("taskAddModal");
  const closeModalButton = document.getElementById("closeModal");
  const cancelButton = document.getElementById("cancelButton");

  // Get elements for the edit task modal
  const editButtons = document.querySelectorAll(".fa-pencil");
  const taskEditModal = document.getElementById("taskEditModal");
  const closeEditModalButton = document.getElementById("closeEditModal");
  const cancelEditButton = document.getElementById("cancelEditButton");

  // Show add modal when clicking on "+ 新規作成" button
  newTaskButton.addEventListener("click", (event) => {
    event.preventDefault();
    taskAddModal.classList.remove("hidden");
  });

  // Hide add modal when clicking on close or cancel button
  closeModalButton.addEventListener("click", () => {
    taskAddModal.classList.add("hidden");
  });

  cancelButton.addEventListener("click", () => {
    taskAddModal.classList.add("hidden");
  });

  // Show edit modal when clicking on pencil icon
  editButtons.forEach((button) => {
    button.addEventListener("click", (event) => {
      event.preventDefault();
      const taskId = button.dataset.taskId; // Assuming each edit button has a data-task-id attribute
      const taskTitle = button.dataset.taskTitle; // Assuming each edit button has a data-task-title attribute
      const taskDescription = button.dataset.taskDescription; // Assuming each edit button has a data-task-description attribute

      // Populate the modal fields with task data
      document.querySelector("input[name='edit_task_id']").value = taskId;
      document.querySelector("input[name='edit_title']").value = taskTitle;
      document.querySelector("textarea[name='edit_description']").value =
        taskDescription;

      // Show edit modal
      taskEditModal.classList.remove("hidden");
    });
  });

  // Hide edit modal when clicking on close or cancel button
  closeEditModalButton.addEventListener("click", () => {
    taskEditModal.classList.add("hidden");
  });

  cancelEditButton.addEventListener("click", () => {
    taskEditModal.classList.add("hidden");
  });
});

document.addEventListener("DOMContentLoaded", () => {
  // Get all the eye icon buttons
  const viewButtons = document.querySelectorAll(".fa-eye");

  // Add event listener to each eye button
  viewButtons.forEach((button) => {
    button.addEventListener("click", () => {
      // Redirect to the detail.html page
      window.location.href = "../detail/detail.php";
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

document.addEventListener("DOMContentLoaded", function () {
  const statusButton = document.getElementById("statusButton");
  const statusDropdown = document.getElementById("statusDropdown");
  const statusText = document.getElementById("statusText");
  const dropdownItems = statusDropdown.querySelectorAll("li");

  // Hiển thị hoặc ẩn dropdown khi nhấn vào button
  statusButton.addEventListener("click", function () {
    statusDropdown.classList.toggle("hidden");
  });

  // Cập nhật giá trị khi chọn trong dropdown
  dropdownItems.forEach((item) => {
    item.addEventListener("click", function () {
      statusText.innerText = this.getAttribute("data-value");
      statusDropdown.classList.add("hidden");
    });
  });

  // Đóng dropdown khi nhấn ra ngoài
  window.addEventListener("click", function (e) {
    if (!statusButton.contains(e.target)) {
      statusDropdown.classList.add("hidden");
    }
  });
});

document.addEventListener("DOMContentLoaded", () => {
  const taskAddForm = document.querySelector("form");
  const createButton = taskAddForm.querySelector("button[type='button']");

  if (createButton) {
    createButton.addEventListener("click", async (e) => {
      e.preventDefault();

      const title = taskAddForm.querySelector("input[type='text']").value;
      const timeStart = taskAddForm.querySelectorAll("input[type='date']")[0].value;
      const timeEnd = taskAddForm.querySelectorAll("input[type='date']")[1].value;
      const description = taskAddForm.querySelector("textarea").value;

      // Gửi dữ liệu qua AJAX (Fetch API)
      const response = await fetch("./mainscreenController.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: new URLSearchParams({
          title: title,
          description: description,
          time_start: timeStart,
          time_end: timeEnd,
        }),
      });

      const result = await response.json();

      if (result.status === "success") {
        alert("タスクが正常に追加されました。");
        // Ẩn modal sau khi lưu thành công
        document.getElementById("taskAddModal").classList.add("hidden");
        // Tải lại trang để cập nhật danh sách task mới (hoặc tự thêm vào danh sách)
        window.location.reload();
      } else {
        alert("エラーが発生しました。再度お試しください。");
      }
    });
  } else {
    console.error("Create button not found in the form.");
  }
});


document.addEventListener("DOMContentLoaded", () => {
  // Lấy tất cả các button có icon thùng rác
  const deleteButtons = document.querySelectorAll(".fa-trash");

  // Thêm sự kiện click cho mỗi nút thùng rác
  deleteButtons.forEach((button) => {
    button.addEventListener("click", async function (event) {
      event.preventDefault();

      // Lấy ID nhiệm vụ từ thuộc tính data-task-id
      const taskId = this.dataset.taskId;

      // Hiển thị xác nhận trước khi xóa
      if (confirm("Bạn có chắc chắn muốn xóa nhiệm vụ này không?")) {
        // Gửi yêu cầu xóa qua AJAX
        const response = await fetch("./mainscreenController.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/x-www-form-urlencoded",
          },
          body: new URLSearchParams({
            task_id: taskId,
          }),
        });

        const result = await response.json();

        if (result.status === "success") {
          alert("Nhiệm vụ đã được xóa thành công.");
          // Xóa nhiệm vụ khỏi DOM hoặc tải lại trang để cập nhật danh sách task
          window.location.reload();
        } else {
          alert("Có lỗi xảy ra. Vui lòng thử lại.");
        }
      }
    });
  });
});

// checked
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.toggle-complete').forEach(checkbox => {
      checkbox.addEventListener('change', function () {
          const taskId = this.dataset.taskId;  // Lấy task_id từ data attribute
          const isChecked = this.checked;  // Lấy trạng thái checked (true/false)
          
          // Gửi yêu cầu Ajax đến server để cập nhật trạng thái
          fetch('./mainscreenController.php', {
              method: 'POST',
              headers: {
                  'Content-Type': 'application/json',
              },
              body: JSON.stringify({
                  task_id: taskId,
                  checked: isChecked ? 1 : 0 // Chuyển true/false thành 1/0
              }),
          })
          .then(response => response.json())
          .then(data => {
              if (data.success) {
                  console.log('Status updated successfully');
              } else {
                  console.error('Failed to update status');
              }
          })
          .catch(error => console.error('Error:', error));
      });
  });
});

