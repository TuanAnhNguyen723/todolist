document.addEventListener("DOMContentLoaded", () => {
  // Get all the eye icon buttons
  const cancelButtons = document.querySelector(".cancelbtn");

  // Add event listener to each eye button
  cancelButtons.forEach((button) => {
    button.addEventListener("click", () => {
      // Redirect to the detail.html page
      window.location.href = "../mainscreen.html";
    });
  });
});
