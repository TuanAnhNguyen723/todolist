document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll(".edit-task-button").forEach((editButton) => {
    editButton.addEventListener("click", function (event) {
      event.preventDefault();

      const taskId = this.getAttribute("data-task-id");

      console.log("Task ID clicked: ", taskId); // Kiểm tra task_id khi click

      showTaskEditModal(taskId); // Hiển thị modal chỉnh sửa và tải dữ liệu từ server
    });
  });

  // Hàm để hiển thị modal chỉnh sửa với dữ liệu từ server
  function showTaskEditModal(taskId) {
    document.querySelector("form").classList.add("hidden");

    const timestamp = new Date().getTime();

    // Gửi yêu cầu lấy thông tin task từ server
    fetch(`mainscreenController.php?task_id=${taskId}&_=${timestamp}`)
      .then((response) => response.json())
      .then((task) => {
        if (task.task_id == taskId) {
          console.log("Fetched task data: ", task);

          document.querySelector("input[name='edit_task_id']").value =
            task.task_id;
          document.querySelector("input[name='edit_title']").value = task.title;
          document.querySelector("textarea[name='edit_description']").value =
            task.description;
          document.querySelector("input[name='edit_time_start']").value =
            task.time_start;
          document.querySelector("input[name='edit_time_end']").value =
            task.time_end;

          document.querySelector("form").classList.remove("hidden");
          document.getElementById("taskEditModal").classList.remove("hidden");
        } else {
          console.error(
            "Task ID mismatch! Expected:",
            taskId,
            "Received:",
            task.task_id
          );
        }
      })
      .catch((error) => {
        console.error("Error fetching task data:", error);
      });
  }

  // Hàm để thêm hoặc xóa gạch ngang trên task
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

  // Sự kiện: Toggle trạng thái ngôi sao cho task
  document.querySelectorAll(".star-icon").forEach((starIcon) => {
    starIcon.addEventListener("click", function () {
      // Lấy task_id từ thuộc tính data-task-id
      const taskId = this.getAttribute("data-task-id");

      // Toggle trạng thái hiển thị của icon ngôi sao
      this.classList.toggle("text-yellow-300");

      // Xác định trạng thái "star" dựa trên việc icon có chứa class "text-yellow-300" hay không
      const isStarred = this.classList.contains("text-yellow-300") ? 1 : 0;

      // Tìm task container cụ thể chứa các thông tin khác như .task-text và .form-checkbox
      const taskContainer = this.closest(".task-container2");

      // Nếu không tìm thấy taskContainer, log ra lỗi
      if (!taskContainer) {
        console.error("Không tìm thấy .task-container2 cho task_id:", taskId);
        return;
      }

      // Tìm các phần tử .task-text và .form-checkbox trong task container
      const taskText = taskContainer.querySelector(".task-text");
      const checkbox = taskContainer.querySelector(".form-checkbox");

      // Kiểm tra xem taskText và checkbox có tồn tại không
      console.log("Task Text Element:", taskText);
      console.log("Checkbox Element:", checkbox);

      // Nếu tìm thấy .task-text và .form-checkbox thì thực hiện thao tác thay đổi màu sắc
      if (taskText && checkbox) {
        if (isStarred) {
          taskText.classList.add("text-yellow-500");
          checkbox.classList.add("accent-yellow-500");
        } else {
          taskText.classList.remove("text-yellow-500");
          checkbox.classList.remove("accent-yellow-500");
        }
      } else {
        console.error(
          "Không tìm thấy .task-text hoặc .form-checkbox trong .task-container!"
        );
      }

      // Cập nhật trạng thái "star" vào cơ sở dữ liệu qua AJAX
      fetch("mainscreenController.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `task_id=${taskId}&star=${isStarred}`,
      })
        .then((response) => response.text())
        .then((data) => {
          console.log(data); // Kiểm tra kết quả trả về từ server
        })
        .catch((error) => console.error("Error:", error));
    });
  });

  // Hiển thị modal thêm task mới
  function showTaskAddModal() {
    document.getElementById("taskAddModal").classList.remove("hidden");
  }

  // Ẩn modal thêm task mới
  function hideTaskAddModal() {
    document.getElementById("taskAddModal").classList.add("hidden");
  }

  // Ẩn modal chỉnh sửa task
  function hideTaskEditModal() {
    document.getElementById("taskEditModal").classList.add("hidden");
  }

  // Hàm để lưu thay đổi task sau khi chỉnh sửa
  function saveTaskEdit(event) {
    event.preventDefault(); // Ngăn chặn hành vi mặc định của form

    // Lấy dữ liệu từ form chỉnh sửa
    const taskId = document.querySelector("input[name='edit_task_id']").value; // Kiểm tra task_id có tồn tại không
    const title = document.querySelector("input[name='edit_title']").value;
    const description = document.querySelector(
      "textarea[name='edit_description']"
    ).value;
    const timeStart = document.querySelector(
      "input[name='edit_time_start']"
    ).value;
    const timeEnd = document.querySelector("input[name='edit_time_end']").value;

    if (!taskId) {
      // Nếu không có task_id, dừng lại vì đang thiếu ID
      console.error("Task ID is missing. Cannot update.");
      return;
    }

    // Gửi yêu cầu cập nhật dữ liệu lên server qua AJAX
    fetch("mainscreenController.php", {
      method: "POST", // Dùng POST để cập nhật dữ liệu
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: `task_id=${taskId}&edit_task=1&title=${encodeURIComponent(
        title
      )}&description=${encodeURIComponent(
        description
      )}&time_start=${encodeURIComponent(
        timeStart
      )}&time_end=${encodeURIComponent(timeEnd)}`,
    })
      .then((response) => response.text())
      .then((data) => {
        console.log("Server response:", data); // Kiểm tra phản hồi từ server
        window.location.reload(); // Tải lại trang để cập nhật dữ liệu đã thay đổi
      })
      .catch((error) => console.error("Error updating task:", error));

    // Ẩn modal sau khi cập nhật
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

  // Hiển thị modal filter
  function showFilterModal() {
    document.getElementById("filterModal").classList.remove("hidden");
  }

  // Ẩn modal filter
  function hideFilterModal() {
    document.getElementById("filterModal").classList.add("hidden");
  }

  // Dropdown trạng thái filter
  function toggleStatusDropdown() {
    const statusDropdown = document.getElementById("statusDropdown");
    statusDropdown.classList.toggle("hidden");
  }

  // Cập nhật trạng thái từ dropdown
  function updateStatusText(item) {
    document.getElementById("statusText").innerText =
      item.getAttribute("data-value");
    document.getElementById("statusDropdown").classList.add("hidden");
  }

  // Điều hướng đến trang chi tiết khi nhấn vào icon "mắt"
  function viewTaskDetails(taskId) {
    window.location.href = `../detail/detail.php?task_id=${taskId}`;
  }

  // Sự kiện: Toggle trạng thái hoàn thành của task
  document.querySelectorAll(".toggle-complete").forEach((checkbox) => {
    checkbox.addEventListener("change", () => toggleTaskComplete(checkbox));
  });

  // Sự kiện: Hiển thị và ẩn modal thêm task
  document
    .querySelector(".newtask")
    .addEventListener("click", showTaskAddModal);
  document
    .getElementById("closeModal")
    .addEventListener("click", hideTaskAddModal);
  document
    .getElementById("cancelButton")
    .addEventListener("click", hideTaskAddModal);

  // Sự kiện: Lưu thông tin sau khi chỉnh sửa task
  document
    .getElementById("saveEditButton")
    .addEventListener("click", saveTaskEdit);
  document
    .getElementById("closeEditModal")
    .addEventListener("click", hideTaskEditModal);
  document
    .getElementById("cancelEditButton")
    .addEventListener("click", hideTaskEditModal);

  // Sự kiện: Xóa task
  document.querySelectorAll(".fa-trash").forEach((trashIcon) => {
    trashIcon.addEventListener("click", (event) => {
      event.preventDefault();
      deleteTask(trashIcon);
    });
  });

  // Sự kiện: Hiển thị và ẩn filter modal
  document
    .querySelector(".fa-sliders-h")
    .addEventListener("click", showFilterModal);
  document
    .getElementById("applyButton")
    .addEventListener("click", hideFilterModal);
  document
    .getElementById("resetButton")
    .addEventListener("click", hideFilterModal);

  // Sự kiện: Toggle dropdown trạng thái
  document
    .getElementById("statusButton")
    .addEventListener("click", toggleStatusDropdown);

  // Cập nhật trạng thái từ dropdown
  document.querySelectorAll("#statusDropdown li").forEach((item) => {
    item.addEventListener("click", () => updateStatusText(item));
  });

  // Đóng dropdown nếu click ra ngoài
  window.addEventListener("click", (e) => {
    if (!document.getElementById("statusButton").contains(e.target)) {
      document.getElementById("statusDropdown").classList.add("hidden");
    }
  });

  // Sự kiện: Xem chi tiết task khi nhấn vào icon "mắt"
  document.querySelectorAll(".fa-eye").forEach((eyeIcon) => {
    eyeIcon.addEventListener("click", function () {
      // Lấy task_id từ phần tử cha chứa icon mắt
      const taskContainer = this.closest(".task-container2"); // Tìm container của task
      const taskId = taskContainer.querySelector("input.form-checkbox").getAttribute("data-task-id"); // Lấy task_id từ checkbox
  
      console.log("Task ID clicked:", taskId); // In ra task_id của task được click vào
  
      // Nếu bạn muốn điều hướng sang trang chi tiết hoặc làm gì đó với task_id
      viewTaskDetails(taskId); // Hàm điều hướng có thể được gọi ở đây
    });
  });

// Hàm format date để hiển thị theo yyyy/mm/dd
function formatDateToYMD(dateString) {
  const date = new Date(dateString);
  const year = date.getFullYear();
  const month = ("0" + (date.getMonth() + 1)).slice(-2); // Lấy tháng và đảm bảo có 2 chữ số
  const day = ("0" + date.getDate()).slice(-2); // Lấy ngày và đảm bảo có 2 chữ số
  return `${year}/${month}/${day}`;
}

  document.addEventListener("DOMContentLoaded", () => {
    // Giả sử dữ liệu này được trả về từ server
    fetch(`mainscreenController.php?task_id=1`)
      .then((response) => response.json())
      .then((task) => {
        if (task.task_id) {
          // Format các giá trị ngày từ server sang yyyy/mm/dd
          document.querySelector("input[name='edit_time_start']").value =
            formatDateToYMD(task.time_start);
          document.querySelector("input[name='edit_time_end']").value =
            formatDateToYMD(task.time_end);
        }
      });
  });
});