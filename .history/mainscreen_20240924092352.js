// Lấy các phần tử từ DOM
const editButtons = document.querySelectorAll('.fa-pencil'); // Tất cả các icon bút
const taskSummary = document.getElementById('task-summary'); // Phần popup Task Completion Summary
const closePopupButton = document.getElementById('close-popup'); // Nút đóng

// Khi người dùng bấm vào icon bút
editButtons.forEach(button => {
    button.addEventListener('click', () => {
        taskSummary.classList.remove('hidden'); // Hiển thị pop-up
    });
});

// Khi người dùng bấm vào nút Đóng trong pop-up
closePopupButton.addEventListener('click', () => {
    taskSummary.classList.add('hidden'); // Ẩn pop-up
});
