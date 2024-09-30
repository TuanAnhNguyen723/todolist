document.addEventListener("DOMContentLoaded", () => {
  // Gạch ngang task khi click checkbox
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

  document.querySelectorAll(".toggle-complete").forEach((checkbox) => {
    checkbox.addEventListener("change", function () {
      const taskId = this.getAttribute("data-task-id");
      const isChecked = this.checked ? 1 : 0; // Chuyển thành 1 nếu checkbox được check, ngược lại 0

      // Gửi yêu cầu AJAX để cập nhật trạng thái trong CSDL
      fetch("mainscreenController.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: `task_id=${taskId}&checked=${isChecked}`,
      })
        .then((response) => response.text())
        .then((data) => {
          console.log(data); // Xử lý kết quả trả về từ server (nếu cần)
        })
        .catch((error) => {
          console.error("Error:", error);
        });

      // Cập nhật giao diện (gạch ngang hoặc bỏ gạch ngang text)
      const taskText = this.parentElement.querySelector(".task-text");
      if (isChecked) {
        taskText.classList.add("line-through", "text-gray-400");
      } else {
        taskText.classList.remove("line-through", "text-gray-400");
      }
    });
  });

  // xóa dữ liệu
  document.querySelectorAll(".fa-trash").forEach((trashIcon) => {
    trashIcon.addEventListener("click", function (event) {
      event.preventDefault(); // Ngăn chặn hành vi mặc định của form
  
      const taskId = this.closest("form").querySelector("input[name='task_id']").value;
  
      // Hiển thị thông báo xác nhận xóa
      const userConfirmed = confirm("Bạn có muốn xóa task này không?");
      
      if (userConfirmed) {
        // Nếu người dùng chọn 'Có', submit form để xóa task
        fetch('mainscreenController.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: `task_id=${taskId}&delete_task=1`,
        })
        .then(response => response.text())
        .then(data => {
          // Xử lý khi xóa thành công
          console.log(data);
          window.location.reload(); // Tải lại trang sau khi xóa
        })
        .catch(error => {
          console.error('Error:', error);
        });
      } else {
        // Người dùng chọn 'Không', không làm gì cả
        console.log("Người dùng hủy yêu cầu xóa.");
      }
    });
  });
  
});
