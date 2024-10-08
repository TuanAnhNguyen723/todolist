document.addEventListener("DOMContentLoaded", () => {
  // Get the cancel button
  const cancelButton = document.querySelector(".cancelbtn");

  // Add click event listener to the cancel button
  cancelButton.addEventListener("click", () => {
    // Redirect to the main screen (update the path if needed)
    window.location.href = "../mainscreen/mainscreen.php";
  });
});

function editTitle() {
  const text = document.getElementById("titleText").textContent;
  const input = document.getElementById("titleInput");
  input.value = text; // Gán giá trị tiêu đề hiện tại vào input
  document.getElementById("titleText").style.display = "none";
  input.style.display = "inline-block";
  input.focus();
}

function saveTitle() {
  const input = document.getElementById("titleInput");
  const text = document.getElementById("titleText");
  text.textContent = input.value; // Cập nhật giá trị sau khi chỉnh sửa
  input.style.display = "none";
  text.style.display = "inline";
}

function toggleTaskStatus(taskId) {
  const taskStatusElement = document.getElementById("taskStatus");

  // Kiểm tra trạng thái hiện tại
  const isCompleted = taskStatusElement.classList.contains("completed");

  // Thay đổi nội dung và lớp CSS dựa trên trạng thái hiện tại
  if (isCompleted) {
    taskStatusElement.textContent = "未完了"; // Chuyển thành "Not done"
    taskStatusElement.classList.remove("completed");
    taskStatusElement.classList.add("not-completed");
  } else {
    taskStatusElement.textContent = "完了"; // Chuyển thành "Done"
    taskStatusElement.classList.remove("not-completed");
    taskStatusElement.classList.add("completed");
  }

  // Gửi AJAX để cập nhật trạng thái trong database
  const newStatus = isCompleted ? 0 : 1; // 1 là hoàn thành, 0 là chưa hoàn thành
  fetch("../mainscreen/mainscreenController.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: `task_id=${taskId}&checked=${newStatus}`,
  })
    .then((response) => response.text())
    .then((data) => {
      console.log("Cập nhật trạng thái thành công:", data); // Log ra kết quả cập nhật
    })
    .catch((error) => console.error("Lỗi khi cập nhật trạng thái:", error));
}

function convertToYYYYMMDD(dateStr) {
  // Giả sử đầu vào là 'dd/mm/yyyy'
  const [day, month, year] = dateStr.split("/"); 
  return `${year}-${month}-${day}`; // Chuyển đổi thành định dạng 'yyyy/mm/dd'
}

document.addEventListener("DOMContentLoaded", function() {
  // Khởi tạo Flatpickr cho các trường ngày
  flatpickr("#startDateInput", {
    dateFormat: "Y/m/d" // Định dạng ngày yyyy/mm/dd
  });
  flatpickr("#endDateInput", {
    dateFormat: "Y/m/d" // Định dạng ngày yyyy/mm/dd
  });
});
