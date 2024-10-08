document.addEventListener('DOMContentLoaded', () => {
    // Get the cancel button
    const cancelButton = document.querySelector('.cancelbtn');
  
    // Add click event listener to the cancel button
    cancelButton.addEventListener('click', () => {
      // Redirect to the main screen (update the path if needed)
      window.location.href = '../mainscreen/mainscreen.php';
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
  