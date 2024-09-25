document.addEventListener("DOMContentLoaded", () => {
    // Get all the eye icon buttons
    const viewButtons = document.querySelectorAll(".cancelbtn");
  
    // Add event listener to each eye button
    viewButtons.forEach((button) => {
      button.addEventListener("click", () => {
        // Redirect to the detail.html page
        window.location.href = "./detail/detail.html";
      });
    });
  });