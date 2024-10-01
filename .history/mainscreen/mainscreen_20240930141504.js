document.addEventListener("DOMContentLoaded", () => {
  // Hàm để cập nhật giao diện task (hoàn thành / chưa hoàn thành)
  function toggleTaskComplete(checkbox) {
    const taskText = checkbox.parentElement.querySelector(".task-text");
    const isChecked = checkbox.checked;
    taskText.classList.toggle("line-through", isChecked);
    taskText.classList.toggle("text-gray-400", isChecked);

    // Cập nhật trạng thái hoàn thành vào CSDL
    const taskId = checkbox.getAttribute("data-task-id");
    const checkedStatus = isChecked ? 1 : 0;
    sendTaskUpdate("task_id=" + taskId + "&checked=" + checkedStatus);
  }

  // Hàm gửi yêu cầu cập nhật task qua AJAX
  function sendTaskUpdate(bodyContent) {
    fetch("mainscreenController.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: bodyContent,
    })
      .then((response) => response.text())
      .then((data) => console.log(data))
      .catch((error) => console.error("Error:", error));
  }

  // Hàm toggle trạng thái "star" của task
  function toggleStar(starIcon) {
    const taskText = starIcon.parentElement.parentElement.querySelector(".task-text");
    starIcon.classList.toggle("text-yellow-300");
    taskText.classList.toggle("text-yellow-200", starIcon.classList.contains("text-yellow-300"));
  }

  // Hiển thị modal thêm task mới
  function showTaskAddModal() {
    document.getElementById("taskAddModal").classList.remove("hidden");
  }

  // Ẩn modal thêm task mới
  function hideTaskAddModal() {
    document.getElementById("taskAddModal").classList.add("hidden");
  }

  // Hiển thị modal chỉnh sửa với dữ liệu từ database
  function showTaskEditModal(taskId) {
    fetch(`mainscreenController.php?task_id=${taskId}`)
      .then((response) => response.json())
      .then((task) => {
        document.querySelector("input[name='edit_task_id']").value = task.task_id;
        document.querySelector("input[name='edit_title']").value = task.title;
        document.querySelector("textarea[name='edit_description']").value = task.description;
        document.querySelector("input[name='edit_time_start']").value = formatDate(task.time_start);
        document.querySelector("input[name='edit_time_end']").value = formatDate(task.time_end);
        document.getElementById("taskEditModal").classList.remove("hidden");
      })
      .catch((error) => console.error("Error fetching task data:", error));
  }

  // Ẩn modal chỉnh sửa task
  function hideTaskEditModal() {
    document.getElementById("taskEditModal").classList.add("hidden");
  }

  // Lưu thay đổi task sau khi chỉnh sửa
  function saveTaskEdit(event) {
    event.preventDefault();
    const taskId = document.querySelector("input[name='edit_task_id']").value;
    const title = document.querySelector("input[name='edit_title']").value;
    const description = document.querySelector("textarea[name='edit_description']").value;
    const timeStart = document.querySelector("input[name='edit_time_start']").value;
    const timeEnd = document.querySelector("input[name='edit_time_end']").value;
    const bodyContent = `task_id=${taskId}&edit_task=1&title=${title}&description=${description}&time_start=${timeStart}&time_end=${timeEnd}`;
    
    sendTaskUpdate(bodyContent);
    hideTaskEditModal();
    window.location.reload();
  }

  // Xóa task với xác nhận từ người dùng
  function deleteTask(trashIcon) {
    const taskId = trashIcon.closest("form").querySelector("input[name='task_id']").value;
    if (confirm("Bạn có muốn xóa task này không?")) {
      sendTaskUpdate(`task_id=${taskId}&delete_task=1`);
      window.location.reload();
    }
  }

  // Hiển thị và ẩn filter modal
  function toggleFilterModal(show) {
    document.getElementById("filterModal").classList.toggle("hidden", !show);
  }

  // Dropdown trạng thái filter
  function toggleStatusDropdown() {
    document.getElementById("statusDropdown").classList.toggle("hidden");
  }

  // Cập nhật trạng thái từ dropdown
  function updateStatusText(item) {
    document.getElementById("statusText").innerText = item.getAttribute("data-value");
    document.getElementById("statusDropdown").classList.add("hidden");
  }

  // Điều hướng đến trang chi tiết khi nhấn vào icon "mắt"
  function viewTaskDetails(taskId) {
    window.location.href = `../detail/detail.php?task_id=${taskId}`;
  }

  // Định dạng lại ngày tháng năm cho input date-picker
  function formatDate(dateString) {
    return new Date(dateString).toISOString().slice(0, 10);
  }

  // Event Listeners
  document.querySelectorAll(".toggle-complete").forEach((checkbox) => {
    checkbox.addEventListener("change", () => toggleTaskComplete(checkbox));
  });

  document.querySelectorAll(".star-icon").forEach((starIcon) => {
    starIcon.addEventListener("click", () => toggleStar(starIcon));
  });

  document.querySelector(".newtask").addEventListener("click", showTaskAddModal);
  document.getElementById("closeModal").addEventListener("click", hideTaskAddModal);
  document.getElementById("cancelButton").addEventListener("click", hideTaskAddModal);

  document.querySelectorAll(".fa-pencil").forEach((editIcon) => {
    editIcon.addEventListener("click", (event) => {
      event.preventDefault();
      const taskId = editIcon.closest(".task-container").querySelector("input[name='task_id']").value;
      showTaskEditModal(taskId);
    });
  });

  document.getElementById("saveEditButton").addEventListener("click", saveTaskEdit);
  document.getElementById("closeEditModal").addEventListener("click", hideTaskEditModal);
  document.getElementById("cancelEditButton").addEventListener("click", hideTaskEditModal);

  document.querySelectorAll(".fa-trash").forEach((trashIcon) => {
    trashIcon.addEventListener("click", (event) => {
      event.preventDefault();
      deleteTask(trashIcon);
    });
  });

  document.querySelector(".fa-sliders-h").addEventListener("click", () => toggleFilterModal(true));
  document.getElementById("applyButton").addEventListener("click", () => toggleFilterModal(false));
  document.getElementById("resetButton").addEventListener("click", () => toggleFilterModal(false));

  document.getElementById("statusButton").addEventListener("click", toggleStatusDropdown);
  document.querySelectorAll("#statusDropdown li").forEach((item) => {
    item.addEventListener("click", () => updateStatusText(item));
  });

  window.addEventListener("click", (e) => {
    if (!document.getElementById("statusButton").contains(e.target)) {
      document.getElementById("statusDropdown").classList.add("hidden");
    }
  });

  document.querySelectorAll(".fa-eye").forEach((eyeIcon) => {
    eyeIcon.addEventListener("click", function () {
      const taskId = this.closest(".task-container").querySelector("input[name='task_id']").value;
      viewTaskDetails(taskId);
    });
  });
});
