// Add this to the bottom of your HTML or in a separate JS file
document.addEventListener('DOMContentLoaded', function() {
    // Get the login form
    const loginForm = document.querySelector('.login-form form');
    
    if (loginForm) {
      loginForm.addEventListener('submit', function(event) {
        // Get username and password fields
        const username = document.getElementById('username').value;
        const password = document.getElementById('password').value;
        
        // Simple validation
        if (username.trim() === '' || password.trim() === '') {
          event.preventDefault(); // Stop form submission
          alert('Please enter both username and password');
        }
      });
    }
  });