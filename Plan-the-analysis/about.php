<?php
  include("session.php");
?>
<!DOCTYPE html>
<html lang="en" class="light">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="About Plan The Analysis">
  <meta name="author" content="">

  <title>Plan The Analysis - About Us</title>

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    // Extend Tailwind configuration for dark mode
    tailwind.config = {
      darkMode: 'class',
      theme: {
        extend: {}
      }
    }
  </script>
  
  <!-- Feather Icons -->
  <script src="https://unpkg.com/feather-icons"></script>
</head>

<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
  <div class="flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <div id="sidebar-wrapper" class="bg-white dark:bg-gray-900 shadow-md w-64 flex-shrink-0 transition-all duration-300 ease-in-out theme-transition">
  <!-- App Logo and Title -->
  <div class="py-6 px-4 bg-blue-600 dark:bg-blue-800 text-white">
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
      <a href="index.php" class="flex items-center px-4 py-2.5 text-gray-700 dark:text-gray-300 rounded-md hover:bg-blue-50 dark:hover:bg-blue-900 hover:text-blue-600 dark:hover:text-blue-400 group">
        <span data-feather="home" class="h-5 w-5 mr-3 text-gray-400 dark:text-gray-500 group-hover:text-blue-500 dark:group-hover:text-blue-400"></span>
        <span>Dashboard</span>
      </a>
      <a href="add_expense.php" class="flex items-center px-4 py-2.5 text-gray-700 dark:text-gray-300 rounded-md hover:bg-blue-50 dark:hover:bg-blue-900 hover:text-blue-600 dark:hover:text-blue-400 group">
        <span data-feather="plus-square" class="h-5 w-5 mr-3 text-gray-400 dark:text-gray-500 group-hover:text-blue-500 dark:group-hover:text-blue-400"></span>
        <span>Add Expenses</span>
      </a>
      <a href="manage_expense.php" class="flex items-center px-4 py-2.5 text-gray-700 dark:text-gray-300 rounded-md hover:bg-blue-50 dark:hover:bg-blue-900 hover:text-blue-600 dark:hover:text-blue-400 group">
        <span data-feather="list" class="h-5 w-5 mr-3 text-gray-400 dark:text-gray-500 group-hover:text-blue-500 dark:group-hover:text-blue-400"></span>
        <span>Manage Expenses</span>
      </a>
      <a href="about.php" class="flex items-center px-4 py-2.5 text-gray-700 dark:text-gray-300 rounded-md hover:bg-blue-50 dark:hover:bg-blue-900 hover:text-blue-600 dark:hover:text-blue-400 group">
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
      <nav class="bg-white dark:bg-gray-800 shadow-sm px-6 py-3 flex items-center justify-between transition-colors duration-200">
        <button id="menu-toggle" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none">
          <span data-feather="menu" class="h-6 w-6"></span>
        </button>
        
        <div class="flex items-center space-x-4">
          <!-- Dark Mode Toggle Button -->
          <button id="theme-toggle" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none">
            <span id="theme-toggle-dark-icon" class="hidden">
              <span data-feather="moon" class="h-5 w-5"></span>
            </span>
            <span id="theme-toggle-light-icon" class="hidden">
              <span data-feather="sun" class="h-5 w-5"></span>
            </span>
          </button>
          
          <!-- User Dropdown -->
          <div class="relative">
            <div class="dropdown">
              <button class="flex items-center space-x-2 focus:outline-none" id="navbarDropdown" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <div class="flex items-center">
                  <img class="rounded-full h-8 w-8 object-cover border-2 border-gray-200 dark:border-gray-700" src="<?php echo $userprofile ?>" alt="User">
                </div>
                <span data-feather="chevron-down" class="h-4 w-4 text-gray-500 dark:text-gray-400"></span>
              </button>
              <div class="dropdown-menu origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 hidden transition-colors duration-200" aria-labelledby="navbarDropdown">
                <a class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200" href="profile.php">Your Profile</a>
                <div class="border-t border-gray-100 dark:border-gray-700 transition-colors duration-200"></div>
                <a class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200" href="logout.php">Logout</a>
              </div>
            </div>
          </div>
        </div>
      </nav>

      <!-- About Content -->
      <div class="p-6">
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-4">About Us</h1>
        
        <!-- About section -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm mb-8">
          <div class="p-6">
            <div class="flex flex-col items-center mb-4">
              <span data-feather="bar-chart-2" class="h-12 w-12 text-blue-500 mb-3"></span>
              <h2 class="text-xl font-semibold text-center text-gray-800 dark:text-gray-200">Plan The Expense - Expense Management System</h2>
            </div>
            
            <div class="space-y-4 text-gray-600 dark:text-gray-300">
              <p>Welcome to Plan The Expense, a comprehensive expense management system designed to help you track, manage, and analyze your personal or business expenses with ease. Our team has created this project with love and dedication to make financial management accessible to everyone.</p>
              
              <p>This expense tracker allows you to categorize expenses, visualize spending patterns, and generate detailed reports to better understand your financial habits. With our intuitive interface, managing your finances has never been easier!</p>
              
              <p>The project was developed as part of our course work, combining our technical skills and passion for creating practical solutions to everyday problems.</p>
            </div>
          </div>
        </div>
        
        <!-- Team section -->
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4">Our Team</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
          <!-- Team Member 1 -->
          <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden transition-transform duration-300 hover:transform hover:scale-105">
            <div class="p-6 flex flex-col items-center">
              <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-blue-100 dark:border-blue-900 mb-4">
                <img src="Assets/khushal.png" alt="Khushal Patel" class="w-full h-full object-cover">
              </div>
              <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Khushal Patel</h3>
              <p class="text-blue-600 dark:text-blue-400 font-medium mb-2">Web Developer</p>
              <p class="text-sm text-gray-500 dark:text-gray-400 text-center">Responsible for frontend development and UI design.</p>
              
              <div class="mt-4 flex space-x-3">
                <a href="#" class="text-gray-400 hover:text-blue-500 dark:hover:text-blue-400">
                  <span data-feather="github" class="h-5 w-5"></span>
                </a>
                <a href="#" class="text-gray-400 hover:text-blue-500 dark:hover:text-blue-400">
                  <span data-feather="linkedin" class="h-5 w-5"></span>
                </a>
                <a href="#" class="text-gray-400 hover:text-blue-500 dark:hover:text-blue-400">
                  <span data-feather="mail" class="h-5 w-5"></span>
                </a>
              </div>
            </div>
          </div>
          
          <!-- Team Member 2 -->
          <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden transition-transform duration-300 hover:transform hover:scale-105">
            <div class="p-6 flex flex-col items-center">
              <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-blue-100 dark:border-blue-900 mb-4">
                <img src="Assets/naishad.jpg" alt="Naishad Rajpoot" class="w-full h-full object-cover">
              </div>
              <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Naishad Rajpoot</h3>
              <p class="text-blue-600 dark:text-blue-400 font-medium mb-2">Backend Developer</p>
              <p class="text-sm text-gray-500 dark:text-gray-400 text-center">Handled database structure and PHP backend logic.</p>
              
              <div class="mt-4 flex space-x-3">
                <a href="#" class="text-gray-400 hover:text-blue-500 dark:hover:text-blue-400">
                  <span data-feather="github" class="h-5 w-5"></span>
                </a>
                <a href="#" class="text-gray-400 hover:text-blue-500 dark:hover:text-blue-400">
                  <span data-feather="linkedin" class="h-5 w-5"></span>
                </a>
                <a href="#" class="text-gray-400 hover:text-blue-500 dark:hover:text-blue-400">
                  <span data-feather="mail" class="h-5 w-5"></span>
                </a>
              </div>
            </div>
          </div>
          
          <!-- Team Member 3 -->
          <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden transition-transform duration-300 hover:transform hover:scale-105">
            <div class="p-6 flex flex-col items-center">
              <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-blue-100 dark:border-blue-900 mb-4">
                <img src="Assets/hitanshu.png" alt="Hitanshu Parekh" class="w-full h-full object-cover">
              </div>
              <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Hitanshu Parekh</h3>
              <p class="text-blue-600 dark:text-blue-400 font-medium mb-2">Data Visualization Specialist</p>
              <p class="text-sm text-gray-500 dark:text-gray-400 text-center">Created charts and expense analysis reports.</p>
              
              <div class="mt-4 flex space-x-3">
                <a href="#" class="text-gray-400 hover:text-blue-500 dark:hover:text-blue-400">
                  <span data-feather="github" class="h-5 w-5"></span>
                </a>
                <a href="#" class="text-gray-400 hover:text-blue-500 dark:hover:text-blue-400">
                  <span data-feather="linkedin" class="h-5 w-5"></span>
                </a>
                <a href="#" class="text-gray-400 hover:text-blue-500 dark:hover:text-blue-400">
                  <span data-feather="mail" class="h-5 w-5"></span>
                </a>
              </div>
            </div>
          </div>
        </div>
        

  <!-- Scripts -->
  <script>
    // Initialize Feather Icons
    document.addEventListener('DOMContentLoaded', () => {
      feather.replace();
      
      // Theme toggle functionality
      const themeToggleBtn = document.getElementById('theme-toggle');
      const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
      const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');
      
      // Set the initial theme based on system preference or saved preference
      if (localStorage.getItem('color-theme') === 'dark' || 
          (!localStorage.getItem('color-theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
        themeToggleLightIcon.classList.remove('hidden');
      } else {
        document.documentElement.classList.remove('dark');
        themeToggleDarkIcon.classList.remove('hidden');
      }
      
      // Toggle theme when button is clicked
      themeToggleBtn.addEventListener('click', function() {
        // Toggle icons
        themeToggleDarkIcon.classList.toggle('hidden');
        themeToggleLightIcon.classList.toggle('hidden');
        
        // Toggle dark mode class
        if (document.documentElement.classList.contains('dark')) {
          document.documentElement.classList.remove('dark');
          localStorage.setItem('color-theme', 'light');
        } else {
          document.documentElement.classList.add('dark');
          localStorage.setItem('color-theme', 'dark');
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
    });
  </script>
</body>
</html>