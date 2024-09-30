document.addEventListener("DOMContentLoaded", () => {
  // Gạch ngang task khi click checkbox
  document.querySelectorAll('.toggle-complete').forEach(checkbox => {
    checkbox.addEventListener('change', function () {
      const taskId = this.dataset.taskId;
      const isChecked = this.checked;

      // Kiểm tra taskId và isChecked để debug
      console.log('Task ID:', taskId);
      console.log('Checked state:', isChecked);

      // Gửi yêu cầu Ajax đến server để cập nhật trạng thái
      fetch('./mainscreenController.php', {
          method: 'POST',
          headers: {
              'Content-Type': 'application/json', // Đảm bảo gửi JSON
          },
          body: JSON.stringify({
              task_id: taskId,
              checked: isChecked ? 0 : 1 // Chuyển true/false thành 1/0
          }),
      })
      .then(response => {
          // Ghi log phản hồi để kiểm tra xem phản hồi là gì
          console.log('Response status:', response.status);
          if (response.ok) {
              // Kiểm tra nếu phản hồi là JSON
              if (response.headers.get('content-type')?.includes('application/json')) {
                  return response.json();
              } else {
                  throw new Error('Expected JSON but received something else');
              }
          } else {
              throw new Error(`HTTP error! status: ${response.status}`);
          }
      })
      .then(data => {
          if (data.success) {
              console.log('Status updated successfully');
          } else {
              console.error('Failed to update status', data.error);
          }
      })
      .catch(error => {
          console.error('Error:', error);
          alert("Cập nhật trạng thái thất bại. Vui lòng thử lại.");
      });
    });
  });

  // Sự kiện click vào icon ngôi sao
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

  // Hiển thị modal thêm task mới
  const newTaskButton = document.querySelector(".newtask");
  const taskAddModal = document.getElementById("taskAddModal");
  const closeModalButton = document.getElementById("closeModal");
  const cancelButton = document.getElementById("cancelButton");

  newTaskButton.addEventListener("click", (event) => {
    event.preventDefault();
    taskAddModal.classList.remove("hidden");
  });

  // Đóng modal thêm task mới
  closeModalButton.addEventListener("click", () => {
    taskAddModal.classList.add("hidden");
  });

  cancelButton.addEventListener("click", () => {
    taskAddModal.classList.add("hidden");
  });

  // Hiển thị modal chỉnh sửa task
  const editButtons = document.querySelectorAll(".fa-pencil");
  const taskEditModal = document.getElementById("taskEditModal");
  const closeEditModalButton = document.getElementById("closeEditModal");
  const cancelEditButton = document.getElementById("cancelEditButton");

  editButtons.forEach((button) => {
    button.addEventListener("click", (event) => {
      event.preventDefault();
      const taskId = button.dataset.taskId;
      const taskTitle = button.dataset.taskTitle;
      const taskDescription = button.dataset.taskDescription;

      // Điền thông tin vào form chỉnh sửa
      document.querySelector("input[name='edit_task_id']").value = taskId;
      document.querySelector("input[name='edit_title']").value = taskTitle;
      document.querySelector("textarea[name='edit_description']").value =
        taskDescription;

      // Hiển thị modal chỉnh sửa
      taskEditModal.classList.remove("hidden");
    });
  });

  // Đóng modal chỉnh sửa
  closeEditModalButton.addEventListener("click", () => {
    taskEditModal.classList.add("hidden");
  });

  cancelEditButton.addEventListener("click", () => {
    taskEditModal.classList.add("hidden");
  });

  // Xử lý nút xem chi tiết (eye icon)
  const viewButtons = document.querySelectorAll(".fa-eye");
  viewButtons.forEach((button) => {
    button.addEventListener("click", () => {
      window.location.href = "../detail/detail.php";
    });
  });

  // Hiển thị filter modal
  const filterModal = document.getElementById("filterModal");
  const filterButton = document.querySelector(".fa-sliders-h");
  const applyButton = document.getElementById("applyButton");
  const resetButton = document.getElementById("resetButton");

  filterButton.addEventListener("click", function () {
    filterModal.classList.remove("hidden");
  });

  applyButton.addEventListener("click", function () {
    filterModal.classList.add("hidden");
  });

  resetButton.addEventListener("click", function () {
    filterModal.classList.add("hidden");
  });

  // Dropdown cho trạng thái filter
  const statusButton = document.getElementById("statusButton");
  const statusDropdown = document.getElementById("statusDropdown");
  const statusText = document.getElementById("statusText");
  const dropdownItems = statusDropdown.querySelectorAll("li");

  statusButton.addEventListener("click", function () {
    statusDropdown.classList.toggle("hidden");
  });

  dropdownItems.forEach((item) => {
    item.addEventListener("click", function () {
      statusText.innerText = this.getAttribute("data-value");
      statusDropdown.classList.add("hidden");
    });
  });

  window.addEventListener("click", function (e) {
    if (!statusButton.contains(e.target)) {
      statusDropdown.classList.add("hidden");
    }
  });

  // Xử lý thêm task mới qua form
  const taskAddForm = document.querySelector("form");
  const createButton = taskAddForm.querySelector("button[type='button']");

  if (createButton) {
    createButton.addEventListener("click", async (e) => {
      e.preventDefault();

      const title = taskAddForm.querySelector("input[type='text']").value;
      const timeStart =
        taskAddForm.querySelectorAll("input[type='date']")[0].value;
      const timeEnd =
        taskAddForm.querySelectorAll("input[type='date']")[1].value;
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

      if (response.headers.get("content-type")?.includes("application/json")) {
        const result = await response.json();

        if (result.status === "success") {
          alert("タスクが正常に追加されました。");
          taskAddModal.classList.add("hidden");
          window.location.reload();
        } else {
          alert("エラーが発生しました。再度お試しください。");
        }
      } else {
        alert("サーバーの応答が不正です。");
      }
    });
  } else {
    console.error("Create button not found in the form.");
  }
});
