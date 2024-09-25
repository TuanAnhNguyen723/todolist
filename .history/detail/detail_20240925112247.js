document.addEventListener('DOMContentLoaded', () => {
    // Get the cancel button
    const cancelButton = document.querySelector('.cancelbtn');
  
    // Add click event listener to the cancel button
    cancelButton.addEventListener('click', () => {
      // Redirect to the main screen (update the path if needed)
      window.location.href = '../mainscreen.html';
    });
  });
  