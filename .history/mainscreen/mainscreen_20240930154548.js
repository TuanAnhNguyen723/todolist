document.addEventListener("DOMContentLoaded", () => {
  // Sử dụng event delegation để xử lý sự kiện click cho nút chỉnh sửa (pencil)
  document.addEventListener("click", function (event) {
    if (event.target.classList.contains("edit-task-button")) {
      event.preventDefault();

      // Lấy task_id từ thuộc tính data-task-id của nút chỉnh sửa
      const taskId = event.target.getAttribute("data-task-id");

      console.log("Task ID clicked: ", taskId); // Kiểm tra task_id khi click

      // Hiển thị modal chỉnh sửa và tải dữ liệu từ server
      showTaskEditModal(taskId);
    }
  });

  // Hàm để hiển thị modal chỉnh sửa với dữ liệu từ server
  function showTaskEditModal(taskId) {
    // Gửi yêu cầu AJAX để lấy thông tin task từ server dựa trên task_id
    fetch(`mainscreenController.php?task_id=${taskId}`)
      .then((response) => response.json())
      .then((task) => {
        console.log("Fetched task data: ", task); // Kiểm tra dữ liệu trả về từ server

        // Điền thông tin của task vào modal
        document.querySelector("input[name='edit_task_id']").value =
          task.task_id;
        document.querySelector("input[name='edit_title']").value = task.title;
        document.querySelector("textarea[name='edit_description']").value =
          task.description;
        document.querySelector("input[name='edit_time_start']").value =
          task.time_start;
        document.querySelector("input[name='edit_time_end']").value =
          task.time_end;

        // Hiển thị modal
        document.getElementById("taskEditModal").classList.remove("hidden");
      })
      .catch((error) => {
        console.error("Error fetching task data:", error);
      });
  }

  // Hàm để toggle trạng thái hoàn thành của task
  function toggleTaskComplete(checkbox) {
    const taskText = checkbox.parentElement.querySelector(".task-text");
    const isChecked = checkbox.checked;

    if (isChecked) {
      taskText.classList.add("line-through", "text-gray-400");
    } else {
      taskText.classList.remove("line-through", "text-gray-400");
    }

    // Cập nhật trạng thái vào cơ sở dữ liệu qua AJAX
    const taskId = checkbox.getAttribute("data-task-id");
    const checkedStatus = isChecked ? 1 : 0;

    fetch("mainscreenController.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: `task_id=${taskId}&checked=${checkedStatus}`,
    })
      .then((response) => response.text())
      .then((data) => console.log(data))
      .catch((error) => console.error("Error:", error));
  }

  // Hàm để toggle ngôi sao (star) task
  function toggleStar(starIcon) {
    const taskText =
      starIcon.parentElement.parentElement.querySelector(".task-text");
    starIcon.classList.toggle("text-yellow-300");

    if (starIcon.classList.contains("text-yellow-300")) {
      taskText.classList.add("text-yellow-200");
    } else {
      taskText.classList.remove("text-yellow-200");
    }
  }

  // Event delegation cho các sự kiện khác
  document.addEventListener("click", function (event) {
    if (event.target.classList.contains("toggle-complete")) {
      toggleTaskComplete(event.target);
    } else if (event.target.classList.contains("star-icon")) {
      toggleStar(event.target);
    } else if (event.target.classList.contains("fa-trash")) {
      event.preventDefault();
      deleteTask(event.target);
    } else if (event.target.classList.contains("fa-eye")) {
      const taskId = event.target
        .closest(".task-container")
        .querySelector("input[name='task_id']").value;
      viewTaskDetails(taskId);
    }
  });

  // Sự kiện hiển thị và ẩn modal thêm task
  document
    .querySelector(".newtask")
    .addEventListener("click", showTaskAddModal);
  document
    .getElementById("closeModal")
    .addEventListener("click", hideTaskAddModal);
  document
    .getElementById("cancelButton")
    .addEventListener("click", hideTaskAddModal);

  // Sự kiện hiển thị và ẩn modal chỉnh sửa task
  document
    .getElementById("saveEditButton")
    .addEventListener("click", saveTaskEdit);
  document
    .getElementById("closeEditModal")
    .addEventListener("click", hideTaskEditModal);
  document
    .getElementById("cancelEditButton")
    .addEventListener("click", hideTaskEditModal);

  // Hàm hiển thị modal thêm task mới
  function showTaskAddModal() {
    document.getElementById("taskAddModal").classList.remove("hidden");
  }

  // Hàm ẩn modal thêm task
  function hideTaskAddModal() {
    document.getElementById("taskAddModal").classList.add("hidden");
  }

  // Hàm ẩn modal chỉnh sửa task
  function hideTaskEditModal() {
    document.getElementById("taskEditModal").classList.add("hidden");
  }

  // Lưu thay đổi task sau khi chỉnh sửa
  function saveTaskEdit(event) {
    event.preventDefault();
    const taskId = document.querySelector("input[name='edit_task_id']").value;
    const title = document.querySelector("input[name='edit_title']").value;
    const description = document.querySelector(
      "textarea[name='edit_description']"
    ).value;
    const timeStart = document.querySelector(
      "input[name='edit_time_start']"
    ).value;
    const timeEnd = document.querySelector("input[name='edit_time_end']").value;

    fetch("mainscreenController.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: `task_id=${taskId}&edit_task=1&title=${title}&description=${description}&time_start=${timeStart}&time_end=${timeEnd}`,
    })
      .then((response) => response.text())
      .then((data) => {
        console.log(data);
        window.location.reload();
      })
      .catch((error) => console.error("Error:", error));

    hideTaskEditModal();
  }

  // Xóa task với xác nhận từ người dùng
  function deleteTask(trashIcon) {
    const taskId = trashIcon
      .closest("form")
      .querySelector("input[name='task_id']").value;
    const userConfirmed = confirm("Bạn có muốn xóa task này không?");

    if (userConfirmed) {
      fetch("mainscreenController.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `task_id=${taskId}&delete_task=1`,
      })
        .then((response) => response.text())
        .then((data) => {
          console.log(data);
          window.location.reload();
        })
        .catch((error) => console.error("Error:", error));
    }
  }

  // Điều hướng đến trang chi tiết khi nhấn vào icon "mắt"
  function viewTaskDetails(taskId) {
    window.location.href = `../detail/detail.php?task_id=${taskId}`;
  }
});
