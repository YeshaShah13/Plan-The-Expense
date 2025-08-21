<?php
  include("session.php");
  // Fixed queries to ensure proper data retrieval for charts
  $exp_category_dc = mysqli_query($con, "SELECT expensecategory FROM expenses WHERE user_id = '$userid' GROUP BY expensecategory");
  $exp_category_labels = []; // Store category labels for reuse
  while ($a = mysqli_fetch_array($exp_category_dc)) {
    $exp_category_labels[] = $a['expensecategory'];
  }

  // Reset the pointer for the data query
  $exp_amt_dc = mysqli_query($con, "SELECT expensecategory, SUM(expense) as total FROM expenses WHERE user_id = '$userid' GROUP BY expensecategory");
  $exp_amt_data = []; // Store expense data for reuse
  while ($b = mysqli_fetch_array($exp_amt_dc)) {
    $exp_amt_data[] = $b['total'];
  }

  // Time series data for line chart
  $exp_date_line = mysqli_query($con, "SELECT expensedate FROM expenses WHERE user_id = '$userid' GROUP BY expensedate ORDER BY expensedate");
  $date_labels = []; // Store date labels for reuse
  while ($c = mysqli_fetch_array($exp_date_line)) {
    $date_labels[] = $c['expensedate'];
  }

  // Reset the pointer for the data query
  $exp_amt_line = mysqli_query($con, "SELECT expensedate, SUM(expense) as total FROM expenses WHERE user_id = '$userid' GROUP BY expensedate ORDER BY expensedate");
  $date_data = []; // Store date data for reuse
  while ($d = mysqli_fetch_array($exp_amt_line)) {
    $date_data[] = $d['total'];
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Expense tracker dashboard">
  <meta name="author" content="">

  <title>Plan The Analysis - Dashboard</title>

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  
  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  
  <!-- Feather Icons -->
  <script src="https://unpkg.com/feather-icons"></script>

  <script>
    // On page load or when changing themes
    if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
      document.documentElement.classList.add('dark');
    } else {
      document.documentElement.classList.remove('dark');
    }
    
    // Extend Tailwind with dark mode
    tailwind.config = {
      darkMode: 'class',
      theme: {
        extend: {}
      }
    }
  </script>
  
  <style>
    /* Direct dark mode styles for better compatibility */
    html.dark {
      background-color: #1e293b;
      color: #f3f4f6;
    }
    
    html.dark body {
      background-color: #1e293b;
      color: #f3f4f6;
    }
    
    html.dark .dark-bg {
      background-color: #1e293b;
    }
    
    html.dark .dark-card {
      background-color: #111827;
    }
    
    html.dark .dark-text {
      color: #f3f4f6;
    }
    
    html.dark .dark-border {
      border-color: #374151;
    }
    
    .theme-transition {
      transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
    }
  </style>
</head>

<body class="bg-gray-50 dark:bg-slate-800 theme-transition">
  <div class="flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <div id="sidebar-wrapper" class="bg-white dark:bg-gray-900 shadow-md w-64 flex-shrink-0 transition-all duration-300 ease-in-out theme-transition">
      <!-- App Logo and Title -->
      <div class="py-6 px-4 bg-blue-600 dark:bg-blue-800 text-white">
        <div class="flex items-center justify-center mb-3">
          <span data-feather="bar-chart-2" class="h-8 w-8 mr-2"></span>
          <h1 class="text-xl font-bold text-white">Plan The Expense</h1>
        </div>
        <div class="text-xs text-center text-blue-100">Expense Management System</div>
      </div>
      
      <!-- Navigation -->
      <div class="px-4 py-3">
        <div class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Main Menu</div>
        
        <div class="space-y-1">
          <a href="index.php" class="flex items-center px-4 py-2.5 text-gray-700 dark:text-gray-300 rounded-md hover:bg-blue-50 dark:hover:bg-blue-900/30 hover:text-blue-600 theme-transition group">
            <span data-feather="home" class="h-5 w-5 mr-3 text-gray-400 group-hover:text-blue-500"></span>
            <span>Dashboard</span>
          </a>
          <a href="add_expense.php" class="flex items-center px-4 py-2.5 text-gray-700 dark:text-gray-300 rounded-md hover:bg-blue-50 dark:hover:bg-blue-900/30 hover:text-blue-600 theme-transition group">
            <span data-feather="plus-square" class="h-5 w-5 mr-3 text-gray-400 group-hover:text-blue-500"></span>
            <span>Add Expenses</span>
          </a>
          <a href="manage_expense.php" class="flex items-center px-4 py-2.5 text-gray-700 dark:text-gray-300 rounded-md hover:bg-blue-50 dark:hover:bg-blue-900/30 hover:text-blue-600 theme-transition group">
            <span data-feather="list" class="h-5 w-5 mr-3 text-gray-400 group-hover:text-blue-500"></span>
            <span>Manage Expenses</span>
          </a>
          <a href="about.php" class="flex items-center px-4 py-2.5 text-gray-700 dark:text-gray-300 rounded-md hover:bg-blue-50 dark:hover:bg-blue-900/30 hover:text-blue-600 theme-transition group">
            <span data-feather="info" class="h-5 w-5 mr-3 text-gray-400 group-hover:text-blue-500"></span>
            <span>About Us</span>
          </a>
        </div>
      </div>
      
      <!-- Footer Section -->
      <div class="mt-auto p-4 border-t border-gray-200 dark:border-gray-700 text-center text-xs text-gray-500 dark:text-gray-400 theme-transition">
        <p>© <?php echo date("Y"); ?> Plan The Expense</p>
      </div>
    </div>
    
    <!-- Main Content -->
    <div class="flex-1 overflow-auto bg-gray-50 dark:bg-slate-800 theme-transition">
      <!-- Top Navigation -->
      <nav class="bg-white dark:bg-gray-900 shadow-sm px-6 py-3 flex items-center justify-between theme-transition">
        <button id="menu-toggle" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none">
          <!-- <span data-feather="menu" class="h-6 w-6"></span> -->
        </button>
        
        <div class="flex items-center space-x-4">
          <!-- Dark Mode Toggle Button -->
          <button id="theme-toggle" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none">
            <span id="theme-toggle-dark-icon" class="hidden">
              <span data-feather="moon" class="h-5 w-5"></span>
            </span>
            <span id="theme-toggle-light-icon">
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
              <div class="dropdown-menu origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white dark:bg-gray-900 ring-1 ring-black ring-opacity-5 hidden theme-transition" aria-labelledby="navbarDropdown">
                <a class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 theme-transition" href="profile.php">Your Profile</a>
                <div class="border-t border-gray-100 dark:border-gray-700 theme-transition"></div>
                <a class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 theme-transition" href="logout.php">Logout</a>
              </div>
            </div>
          </div>
        </div>
      </nav>

      <!-- Dashboard Content -->
      <div class="p-6 bg-gray-50 dark:bg-slate-800 theme-transition">
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-white mb-4">Dashboard</h1>
        
        <!-- Quick Actions -->
        <div class="bg-white dark:bg-gray-900 rounded-lg shadow-sm mb-4 theme-transition">
          <div class="p-3">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
              <a href="add_expense.php" class="flex flex-col items-center justify-center p-4 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/30 transition-colors theme-transition">
                <div class="bg-blue-100 dark:bg-blue-900/50 p-3 rounded-full mb-3 theme-transition">
                  <span data-feather="plus-circle" class="h-8 w-8 text-blue-600 dark:text-blue-400"></span>
                </div>
                <span class="font-medium text-gray-700 dark:text-gray-300 theme-transition">Add Expenses</span>
              </a>
              
              <a href="manage_expense.php" class="flex flex-col items-center justify-center p-4 rounded-lg hover:bg-green-50 dark:hover:bg-green-900/30 transition-colors theme-transition">
                <div class="bg-green-100 dark:bg-green-900/50 p-3 rounded-full mb-3 theme-transition">
                  <span data-feather="list" class="h-8 w-8 text-green-600 dark:text-green-400"></span>
                </div>
                <span class="font-medium text-gray-700 dark:text-gray-300 theme-transition">Manage Expenses</span>
              </a>
              
              <a href="profile.php" class="flex flex-col items-center justify-center p-4 rounded-lg hover:bg-purple-50 dark:hover:bg-purple-900/30 transition-colors theme-transition">
                <div class="bg-purple-100 dark:bg-purple-900/50 p-3 rounded-full mb-3 theme-transition">
                  <span data-feather="user" class="h-8 w-8 text-purple-600 dark:text-purple-400"></span>
                </div>
                <span class="font-medium text-gray-700 dark:text-gray-300 theme-transition">User Profile</span>
              </a>
            </div>
          </div>
        </div>
        
        <!-- Expense Reports -->
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Expense Reports</h2>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
          <!-- Yearly Expenses -->
          <div class="bg-white dark:bg-gray-900 rounded-lg shadow-sm theme-transition">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 theme-transition">
              <h3 class="font-medium text-gray-700 dark:text-gray-300 text-center theme-transition">Expenses by Date</h3>
            </div>
            <div class="p-3">
              <canvas id="expense_line" height="150"></canvas>
            </div>
          </div>
          
          <!-- Expense by Category -->
          <div class="bg-white dark:bg-gray-900 rounded-lg shadow-sm theme-transition">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 theme-transition">
              <h3 class="font-medium text-gray-700 dark:text-gray-300 text-center theme-transition">Expense by Category</h3>
            </div>
            <div class="p-3">
              <canvas id="expense_category_pie" height="150"></canvas>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script>
    // Initialize Feather Icons
    document.addEventListener('DOMContentLoaded', () => {
      feather.replace();
      
      // Dark mode toggle
      const themeToggleBtn = document.getElementById('theme-toggle');
      const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
      const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');
      
      // Change icon based on current theme
      function updateThemeToggleIcon() {
        if (document.documentElement.classList.contains('dark')) {
          themeToggleLightIcon.classList.add('hidden');
          themeToggleDarkIcon.classList.remove('hidden');
        } else {
          themeToggleLightIcon.classList.remove('hidden');
          themeToggleDarkIcon.classList.add('hidden');
        }
      }
      
      // Set the initial icon state
      updateThemeToggleIcon();
      
      // Handle dark mode toggle click
      themeToggleBtn.addEventListener('click', function() {
        // Toggle dark class on html element
        document.documentElement.classList.toggle('dark');
        
        // Update localStorage based on current state
        if (document.documentElement.classList.contains('dark')) {
          localStorage.setItem('color-theme', 'dark');
        } else {
          localStorage.setItem('color-theme', 'light');
        }
        
        // Update icon display
        updateThemeToggleIcon();
        
        // Update charts for dark/light mode
        updateChartThemes();
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
      
      // Initialize charts
      initializeCharts();
    });
    
    // Chart initialization
    function initializeCharts() {
      // Check dark mode status
      const isDark = document.documentElement.classList.contains('dark');
      const gridColor = isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';
      const textColor = isDark ? '#f3f4f6' : '#374151';
      
      const colors = [
        '#4F46E5', // Indigo
        '#EF4444', // Red
        '#10B981', // Green
        '#3B82F6', // Blue
        '#F59E0B', // Amber
        '#8B5CF6', // Purple
        '#06B6D4', // Cyan
        '#EC4899', // Pink
        '#6366F1', // Violet
        '#14B8A6'  // Teal
      ];
      
      // Category Expense Chart
      const categoryLabels = <?php echo json_encode($exp_category_labels); ?>;
      const categoryData = <?php echo json_encode($exp_amt_data); ?>;
      
      var ctxBar = document.getElementById('expense_category_pie').getContext('2d');
      window.categoryChart = new Chart(ctxBar, {
        type: 'bar',
        data: {
          labels: categoryLabels,
          datasets: [{
            label: 'Expense by Category',
            data: categoryData,
            backgroundColor: colors,
            borderWidth: 0,
            borderRadius: 4
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: {
              display: false,
              labels: {
                color: textColor
              }
            },
            tooltip: {
              enabled: true
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              grid: {
                display: true,
                color: gridColor
              },
              ticks: {
                color: textColor
              }
            },
            x: {
              grid: {
                display: false
              },
              ticks: {
                color: textColor
              }
            }
          }
        }
      });
      
      // Yearly Expense Chart
      const dateLabels = <?php echo json_encode($date_labels); ?>;
      const dateData = <?php echo json_encode($date_data); ?>;
      
      var ctxLine = document.getElementById('expense_line').getContext('2d');
      window.lineChart = new Chart(ctxLine, {
        type: 'line',
        data: {
          labels: dateLabels,
          datasets: [{
            label: 'Expense by Date',
            data: dateData,
            borderColor: '#4F46E5',
            backgroundColor: isDark ? 'rgba(79, 70, 229, 0.2)' : 'rgba(79, 70, 229, 0.1)',
            fill: true,
            tension: 0.4,
            borderWidth: 2,
            pointBackgroundColor: '#4F46E5',
            pointRadius: 3
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: {
              display: false,
              labels: {
                color: textColor
              }
            },
            tooltip: {
              enabled: true
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              grid: {
                display: true,
                color: gridColor
              },
              ticks: {
                color: textColor
              }
            },
            x: {
              grid: {
                display: false
              },
              ticks: {
                color: textColor
              }
            }
          }
        }
      });
    }
    
    // Update chart themes when dark mode changes
    function updateChartThemes() {
      const isDark = document.documentElement.classList.contains('dark');
      const gridColor = isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';
      const textColor = isDark ? '#f3f4f6' : '#374151';
      
      // Update category chart
      if (window.categoryChart) {
        window.categoryChart.options.scales.y.grid.color = gridColor;
        window.categoryChart.options.scales.y.ticks.color = textColor;
        window.categoryChart.options.scales.x.ticks.color = textColor;
        if (window.categoryChart.options.plugins.legend) {
          window.categoryChart.options.plugins.legend.labels.color = textColor;
        }
        window.categoryChart.update();
      }
      
      // Update line chart
      if (window.lineChart) {
        window.lineChart.options.scales.y.grid.color = gridColor;
        window.lineChart.options.scales.y.ticks.color = textColor;
        window.lineChart.options.scales.x.ticks.color = textColor;
        if (window.lineChart.options.plugins.legend) {
          window.lineChart.options.plugins.legend.labels.color = textColor;
        }
        window.lineChart.data.datasets[0].backgroundColor = isDark ? 'rgba(79, 70, 229, 0.2)' : 'rgba(79, 70, 229, 0.1)';
        window.lineChart.update();
      }
    }
  </script>
</body>
</html>