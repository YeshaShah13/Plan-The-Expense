<?php
  include("session.php");
  
  if (isset($_POST['save'])) {
    $fname = $_POST['first_name'];
    $lname = $_POST['last_name'];

    $sql = "UPDATE users SET firstname = '$fname', lastname='$lname' WHERE user_id='$userid'";
    if (mysqli_query($con, $sql)) {
      $success_message = "Profile updated successfully.";
    } else {
      $error_message = "ERROR: Could not update profile. " . mysqli_error($con);
    }
    
    // Refresh user data after update
    $user_check_query = mysqli_query($con, "SELECT * FROM users WHERE user_id = '$userid'");
    $user_data = mysqli_fetch_assoc($user_check_query);
    $firstname = $user_data['firstname'];
    $lastname = $user_data['lastname'];
    $username = $firstname . " " . $lastname;
  }

  if (isset($_POST['but_upload'])) {
    $name = $_FILES['file']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["file"]["name"]);

    // Select file type
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Valid file extensions
    $extensions_arr = array("jpg", "jpeg", "png", "gif");

    // Check extension
    if (in_array($imageFileType, $extensions_arr)) {
      // Insert record
      $query = "UPDATE users SET profile_path = '$name' WHERE user_id='$userid'";
      mysqli_query($con, $query);

      // Upload file
      move_uploaded_file($_FILES['file']['tmp_name'], $target_dir . $name);
      
      $success_message = "Profile picture updated successfully.";
      
      // Refresh user data
      $userprofile = "uploads/" . $name;
    } else {
      $error_message = "Invalid file format. Please upload a JPG, JPEG, PNG or GIF file.";
    }
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Expense tracker manage profile">
  <meta name="author" content="">

  <title>Plan The Analysis - Profile</title>

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  
  <!-- Feather Icons -->
  <script src="https://unpkg.com/feather-icons"></script>
  
  <script>
    // On page load or when changing themes, best to add inline in `head` to avoid FOUC
    if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
      document.documentElement.classList.add('dark');
    } else {
      document.documentElement.classList.remove('dark')
    }
    
    // Extend Tailwind with dark mode
    tailwind.config = {
      darkMode: 'class',
      theme: {
        extend: {}
      }
    }
  </script>
</head>

<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
  <div class="flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <div id="sidebar-wrapper" class="bg-white dark:bg-gray-800 shadow-md w-64 flex-shrink-0 transition-all duration-300 ease-in-out">
      <!-- App Logo and Title -->
      <div class="py-6 px-4 bg-blue-600 dark:bg-blue-700 text-white">
        <div class="flex items-center justify-center mb-3">
          <span data-feather="bar-chart-2" class="h-8 w-8 mr-2"></span>
          <h1 class="text-xl font-bold">Plan The Expense</h1>
        </div>
        <div class="text-xs text-center text-blue-100">Expense Management System</div>
      </div>
      
      <!-- Navigation -->
      <div class="px-4 py-3">
        <div class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Main Menu</div>
        
        <div class="space-y-1">
          <a href="index.php" class="flex items-center px-4 py-2.5 text-gray-700 dark:text-gray-300 rounded-md hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-blue-600 dark:hover:text-blue-400 group">
            <span data-feather="home" class="h-5 w-5 mr-3 text-gray-400 dark:text-gray-500 group-hover:text-blue-500 dark:group-hover:text-blue-400"></span>
            <span>Dashboard</span>
          </a>
          <a href="add_expense.php" class="flex items-center px-4 py-2.5 text-gray-700 dark:text-gray-300 rounded-md hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-blue-600 dark:hover:text-blue-400 group">
            <span data-feather="plus-square" class="h-5 w-5 mr-3 text-gray-400 dark:text-gray-500 group-hover:text-blue-500 dark:group-hover:text-blue-400"></span>
            <span>Add Expenses</span>
          </a>
          <a href="manage_expense.php" class="flex items-center px-4 py-2.5 text-gray-700 dark:text-gray-300 rounded-md hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-blue-600 dark:hover:text-blue-400 group">
            <span data-feather="list" class="h-5 w-5 mr-3 text-gray-400 dark:text-gray-500 group-hover:text-blue-500 dark:group-hover:text-blue-400"></span>
            <span>Manage Expenses</span>
          </a>
          <a href="about.php" class="flex items-center px-4 py-2.5 text-gray-700 dark:text-gray-300 rounded-md hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-blue-600 dark:hover:text-blue-400 group">
            <span data-feather="info" class="h-5 w-5 mr-3 text-gray-400 dark:text-gray-500 group-hover:text-blue-500 dark:group-hover:text-blue-400"></span>
            <span>About Us</span>
          </a>
        </div>
      </div>
      
      
      
      <!-- Footer Section -->
      <div class="mt-auto p-4 border-t border-gray-200 dark:border-gray-700 text-center text-xs text-gray-500 dark:text-gray-400">
        <p>© <?php echo date("Y"); ?> Plan The Expense</p>
      </div>
    </div>
    
    <!-- Main Content -->
    <div class="flex-1 overflow-auto">
      <!-- Top Navigation -->
      <nav class="bg-white dark:bg-gray-800 shadow-sm px-6 py-3 flex items-center justify-between">
        <button id="menu-toggle" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none">
          <span data-feather="menu" class="h-6 w-6"></span>
        </button>
        
        <div class="flex items-center space-x-4">
          <!-- Dark Mode Toggle -->
          <button id="theme-toggle" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none">
            <span data-feather="moon" class="h-5 w-5 hidden dark:inline-block" id="theme-toggle-light-icon"></span>
            <span data-feather="sun" class="h-5 w-5 inline-block dark:hidden" id="theme-toggle-dark-icon"></span>
          </button>
          
          <div class="relative flex items-center">
            <div class="dropdown flex items-center">
              <button class="flex items-center space-x-2 focus:outline-none" id="navbarDropdown" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <img class="rounded-full h-8 w-8 object-cover border-2 border-gray-200 dark:border-gray-700" src="<?php echo $userprofile ?>" alt="User">
                <span data-feather="chevron-down" class="h-4 w-4 text-gray-500 dark:text-gray-400"></span>
              </button>
              <div class="dropdown-menu origin-top-right absolute right-0 mt-10 w-48 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 hidden z-10" aria-labelledby="navbarDropdown">
                <a class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700" href="profile.php">Your Profile</a>
                <div class="border-t border-gray-100 dark:border-gray-700"></div>
                <a class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700" href="logout.php">Logout</a>
              </div>
            </div>
          </div>
        </div>
      </nav>

      <!-- Profile Content -->
      <div class="p-6">
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-white mb-6">Update Profile</h1>
        
        <!-- Alert Messages -->
        <?php if(isset($success_message)): ?>
        <div class="bg-green-100 dark:bg-green-900 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 px-4 py-3 rounded relative mb-6" role="alert">
          <span class="block sm:inline"><?php echo $success_message; ?></span>
          <button type="button" class="absolute top-0 right-0 px-4 py-3 close-alert">
            <span data-feather="x" class="h-4 w-4 text-green-700 dark:text-green-300"></span>
          </button>
        </div>
        <?php endif; ?>
        
        <?php if(isset($error_message)): ?>
        <div class="bg-red-100 dark:bg-red-900 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 px-4 py-3 rounded relative mb-6" role="alert">
          <span class="block sm:inline"><?php echo $error_message; ?></span>
          <button type="button" class="absolute top-0 right-0 px-4 py-3 close-alert">
            <span data-feather="x" class="h-4 w-4 text-red-700 dark:text-red-300"></span>
          </button>
        </div>
        <?php endif; ?>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Profile Picture Update -->
          <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
            <h2 class="text-lg font-medium text-gray-800 dark:text-white mb-4">Profile Picture</h2>
            
            <form method="post" action="" enctype="multipart/form-data" class="space-y-4">
              <div class="flex flex-col items-center">
                <img src="<?php echo $userprofile; ?>" class="rounded-full w-32 h-32 object-cover border-4 border-gray-200 dark:border-gray-700 mb-4" alt="Profile Picture">
                
                <div class="w-full">
                  <div class="flex items-center justify-center w-full">
                    <label class="flex flex-col w-full h-24 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700">
                      <div class="flex flex-col items-center justify-center pt-5 pb-6">
                        <!-- <span data-feather="upload-cloud" class="h-8 w-8 text-gray-400 dark:text-gray-500 mb-2"></span> -->
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                          <span class="font-medium">Click to upload</span>
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                          JPG, JPEG, PNG or GIF (MAX. 2MB)
                        </p>
                      </div>
                      <input type="file" name="file" id="profilepic" class="hidden">
                    </label>
                  </div>
                </div>
                
                <button type="submit" name="but_upload" class="mt-4 w-full px-4 py-2 bg-blue-600 dark:bg-blue-700 text-white rounded-md hover:bg-blue-700 dark:hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-600 focus:ring-opacity-50 transition-colors duration-200">
                  Upload Picture
                </button>
              </div>
            </form>
          </div>
          
          <!-- Profile Information Update -->
          <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
            <h2 class="text-lg font-medium text-gray-800 dark:text-white mb-4">Personal Information</h2>
            
            <form method="post" action="" class="space-y-4">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label for="first_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    First Name
                  </label>
                  <input type="text" name="first_name" id="first_name" value="<?php echo $firstname; ?>" placeholder="First Name" class="w-full p-1 px-3 rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 dark:focus:border-blue-500 focus:ring focus:ring-blue-500 dark:focus:ring-blue-500 focus:ring-opacity-50 dark:bg-gray-700 dark:text-white">
                </div>
                
                <div>
                  <label for="last_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Last Name
                  </label>
                  <input type="text" name="last_name" id="last_name" value="<?php echo $lastname; ?>" placeholder="Last Name" class="w-full p-1 px-3 rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 dark:focus:border-blue-500 focus:ring focus:ring-blue-500 dark:focus:ring-blue-500 focus:ring-opacity-50 dark:bg-gray-700 dark:text-white">
                </div>
              </div>
              
              <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                  Email
                </label>
                <input type="email" name="email" id="email" value="<?php echo $useremail; ?>" disabled class="w-full p-1 px-3 rounded-md border-gray-300 dark:border-gray-600 shadow-sm bg-gray-100 dark:bg-gray-600 text-gray-500 dark:text-gray-400">
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Email cannot be changed.</p>
              </div>
              
              <div class="pt-4">
                <button type="submit" name="save" class="w-full px-4 py-2 bg-blue-600 dark:bg-blue-700 text-white rounded-md hover:bg-blue-700 dark:hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-600 focus:ring-opacity-50 transition-colors duration-200">
                  Save Changes
                </button>
              </div>
            </form>
          </div>
        </div>
        


  <!-- Scripts -->
  <script>
    // Initialize Feather Icons
    document.addEventListener('DOMContentLoaded', () => {
      feather.replace();
      
      // Dark mode toggle
      var themeToggleBtn = document.getElementById('theme-toggle');
      
      themeToggleBtn.addEventListener('click', function() {
        // Toggle dark class on html element
        document.documentElement.classList.toggle('dark');
        
        // Update localStorage value
        if (document.documentElement.classList.contains('dark')) {
          localStorage.setItem('color-theme', 'dark');
        } else {
          localStorage.setItem('color-theme', 'light');
        }
      });
      
      // Dropdown toggle
      document.getElementById('navbarDropdown').addEventListener('click', function() {
        document.querySelector('.dropdown-menu').classList.toggle('hidden');
      });
      
      // Close dropdown when clicking outside
      window.addEventListener('click', function(e) {
        if (!document.getElementById('navbarDropdown').contains(e.target)) {
          document.querySelector('.dropdown-menu').classList.add('hidden');
        }
      });
      
      // Sidebar toggle for mobile
      document.getElementById('menu-toggle').addEventListener('click', function() {
        document.getElementById('sidebar-wrapper').classList.toggle('-translate-x-full');
      });
      
      // File upload label update
      const fileInput = document.getElementById('profilepic');
      if (fileInput) {
        fileInput.addEventListener('change', (e) => {
          const fileName = e.target.files[0]?.name;
          if (fileName) {
            const label = e.target.parentElement.querySelector('.text-sm');
            if (label) {
              label.textContent = fileName;
            }
          }
        });
      }
      
      // Close alerts
      const closeButtons = document.querySelectorAll('.close-alert');
      closeButtons.forEach(button => {
        button.addEventListener('click', () => {
          const alert = button.closest('[role="alert"]');
          alert.remove();
        });
      });
      
      // Auto-hide alerts after 5 seconds
      const alerts = document.querySelectorAll('[role="alert"]');
      alerts.forEach(alert => {
        setTimeout(() => {
          alert.remove();
        }, 5000);
      });
    });
  </script>
</body>
</html>