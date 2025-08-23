<?php
require('config.php');
session_start();
$errormsg = "";

// Hardcoded user credentials for easy testing
$HARDCODED_EMAIL = "admin@expense.com";
$HARDCODED_PASSWORD = "password"; // This will be MD5 hashed

if (isset($_POST['email'])) {

  $email = stripslashes($_REQUEST['email']);
  $email = mysqli_real_escape_string($con, $email);
  $password = stripslashes($_REQUEST['password']);
  $password = mysqli_real_escape_string($con, $password);
  
  // Check if it's the hardcoded user first
  if ($email === $HARDCODED_EMAIL && md5($password) === md5($HARDCODED_PASSWORD)) {
    $_SESSION['email'] = $email;
    header("Location: index.php");
    exit();
  }
  
  // If not hardcoded user, check database
  $query = "SELECT * FROM `users` WHERE email='$email'and password='" . md5($password) . "'";
  $result = mysqli_query($con, $query) or die(mysqli_error($con));
  $rows = mysqli_num_rows($result);
  if ($rows == 1) {
    $_SESSION['email'] = $email;
    header("Location: index.php");
  } else {
    $errormsg  = "Wrong email or password. Please try again.";
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Login page for Plan The Analysis">
  <meta name="author" content="">

  <title>Plan The Analysis - Login</title>

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  
  <!-- Feather Icons -->
  <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
  
  <!-- Swiper.js for image carousel -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/9.3.2/swiper-bundle.min.css" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/9.3.2/swiper-bundle.min.js"></script>
  
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
        extend: {
          colors: {
            primary: {
              50: '#f0f9ff',
              100: '#e0f2fe',
              200: '#bae6fd',
              300: '#7dd3fc',
              400: '#38bdf8',
              500: '#0ea5e9',
              600: '#0284c7',
              700: '#0369a1',
              800: '#075985',
              900: '#0c4a6e',
            }
          },
          height: {
            'screen-90': '90vh',
          }
        }
      }
    }
  </script>
  
  <style>
    .swiper {
      width: 100%;
      height: 100%;
    }
    
    .swiper-slide {
      position: relative;
      text-align: center;
      background-size: cover;
      background-position: center;
    }
    
    .slide-content {
      position: absolute;
      bottom: 10%;
      left: 10%;
      width: 80%;
      color: white;
      text-align: left;
      text-shadow: 1px 1px 3px rgba(0,0,0,0.3);
    }
    
    @media (max-width: 768px) {
      .login-container {
        flex-direction: column-reverse;
      }
      
      .image-section {
        height: 40vh !important;
      }
      
      .login-section {
        width: 100% !important;
      }
    }
  </style>
</head>

<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
  <div class="min-h-screen flex login-container">
    <!-- Left side: Image Carousel (60%) -->
    <div class="image-section hidden md:block w-3/5 h-screen bg-gray-100 dark:bg-gray-800 relative overflow-hidden">
      <div class="swiper mySwiper h-full">
        <div class="swiper-wrapper">
          <!-- Slide 1 -->
          <div class="swiper-slide">
            <img src="Assets/5.jpg" alt="Financial Dashboard" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
            <div class="slide-content">
              <h2 class="text-3xl font-bold mb-3">Plan Your Expense</h2>
              <p class="text-lg">Take control of your expenses with our intelligent analysis tools</p>
            </div>
          </div>
        </div>
        <div class="swiper-pagination"></div>
      </div>
      
      <!-- Branding in the corner -->
      <div class="absolute top-6 left-6 z-10">
        <h2 class="text-2xl font-bold text-white">Plan The Expense</h2>
        <p class="text-sm text-gray-200">Expense Management System</p>
      </div>
    </div>
    
    <!-- Right side: Login Form (40%) -->
    <div class="login-section w-full md:w-2/5 flex flex-col justify-center items-center p-8">
      <div class="w-full max-w-md">
        <!-- Dark Mode Toggle -->
        <div class="flex justify-end mb-6">
          <button id="theme-toggle" type="button" class="p-2 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-primary-500 transition-colors duration-200">
            <svg id="theme-toggle-dark-icon" class="w-5 h-5 hidden dark:block" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
              <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path>
            </svg>
            <svg id="theme-toggle-light-icon" class="w-5 h-5 dark:hidden" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
              <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
            </svg>
          </button>
        </div>
        
        <!-- Mobile only branding -->
        <div class="block md:hidden text-center mb-8">
          <h2 class="text-2xl font-bold text-gray-700 dark:text-white">Plan The Expense</h2>
          <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Expense Management System</p>
        </div>
        
        <!-- Welcome text -->
        <div class="mb-10">
          <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Welcome back</h1>
          <p class="text-gray-600 dark:text-gray-400 mt-2">Please enter your details to sign in</p>
        </div>
        
        <!-- Login Form -->
        <form action="" method="POST" autocomplete="off">
          <?php if($errormsg): ?>
          <div class="mb-6 bg-red-100 dark:bg-red-900/50 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 px-4 py-3 rounded-lg relative" role="alert">
            <div class="flex items-center">
              <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zm-1 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path>
              </svg>
              <span><?php echo $errormsg; ?></span>
            </div>
          </div>
          <?php endif; ?>
          
          <!-- Hardcoded User Info for Testing -->
          <div class="mb-6 bg-blue-100 dark:bg-blue-900/50 border border-blue-200 dark:border-blue-800 text-blue-700 dark:text-blue-300 px-4 py-3 rounded-lg relative" role="alert">
            <div class="flex items-center">
              <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zm-1 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path>
              </svg>
              
            </div>
          </div>
          
          <div class="mb-6">
            <label class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-2" for="email">
              Email
            </label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                  <polyline points="22,6 12,13 2,6"></polyline>
                </svg>
              </div>
              <input class="pl-10 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-3 px-4 text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-600 focus:border-transparent transition-colors duration-200" name="email" type="email" placeholder="name@company.com" required>
            </div>
          </div>
          
          <div class="mb-6">
            <div class="flex justify-between items-center mb-2">
              <label class="block text-gray-700 dark:text-gray-300 text-sm font-medium" for="password">
                Password
              </label>
            </div>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                  <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
              </div>
              <input class="pl-10 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-3 px-4 text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-600 focus:border-transparent transition-colors duration-200" name="password" type="password" placeholder="••••••••" required>
            </div>
          </div>
          
          <div class="flex items-center mb-6">
            <input id="remember" name="remember" type="checkbox" class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-primary-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-primary-600">
            <label for="remember" class="ml-2 text-sm font-medium text-gray-600 dark:text-gray-400">
              Remember me
            </label>
          </div>
          
          <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 dark:bg-primary-700 dark:hover:bg-primary-600 text-white font-medium py-3 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200">
            Sign in
          </button>
          
          <div class="text-center mt-6">
            <p class="text-gray-600 dark:text-gray-400 text-sm">
              Don't have an account? 
              <a href="register.php" class="text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300 font-medium">
                Register Here
              </a>
            </p>
          </div>
        </form>
        
        <div class="mt-10 pt-6 border-t border-gray-200 dark:border-gray-700 text-center">
          <p class="text-xs text-gray-500 dark:text-gray-400">
            © <?php echo date("Y"); ?> Plan The Expense. All rights reserved.
          </p>
        </div>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      // Initialize Feather Icons
      feather.replace();
      
      // Initialize Swiper
      var swiper = new Swiper(".mySwiper", {
        effect: "fade",
        autoplay: {
          delay: 5000,
          disableOnInteraction: false,
        },
        pagination: {
          el: ".swiper-pagination",
          clickable: true,
        },
        loop: true,
      });
      
      // Dark mode toggle
      var themeToggleBtn = document.getElementById('theme-toggle');
      var themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
      var themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');
      
      // Set correct icon on page load
      if (localStorage.getItem('color-theme') === 'dark' || 
          (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        themeToggleLightIcon.classList.remove('hidden');
        themeToggleDarkIcon.classList.add('hidden');
      } else {
        themeToggleLightIcon.classList.add('hidden');
        themeToggleDarkIcon.classList.remove('hidden');
      }
      
      themeToggleBtn.addEventListener('click', function() {
        // Toggle dark class on html element
        document.documentElement.classList.toggle('dark');
        
        // Toggle icons
        themeToggleDarkIcon.classList.toggle('hidden');
        themeToggleLightIcon.classList.toggle('hidden');
        
        // Update localStorage value
        if (document.documentElement.classList.contains('dark')) {
          localStorage.setItem('color-theme', 'dark');
        } else {
          localStorage.setItem('color-theme', 'light');
        }
      });
    });
  </script>
</body>
</html>