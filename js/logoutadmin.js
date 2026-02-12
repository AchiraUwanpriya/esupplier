function confirmLogout() {
    var confirmation = confirm("Are you sure you want to logout?");
    if (confirmation) {
      logout();
    }
  }

  function logout() {
    fetch('./logoutadmin.js', {
        method: 'GET',
      })
      .then(function(response) {
        if (response.ok) {
          return response.json();
        }
        throw new Error('Network response was not ok.');
      })
      .then(function(data) {
        if (data.success) {
          // Destroy the sessions and redirect to index.php
          window.location.href = "admin.php";
        } else {
          alert('Failed to logout. Please try again.');
        }
      })
      .catch(function(error) {
        console.error('Error:', error);
        alert('An error occurred. Please try again later.');
      });
  }