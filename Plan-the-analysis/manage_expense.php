<?php
  include("session.php");
  
  // Pagination setup
  $results_per_page = 5; // Number of expenses per page
  $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
  $offset = ($page - 1) * $results_per_page;
  
  // Initialize filter variables
  $category_filter = isset($_GET['category']) ? $_GET['category'] : '';
  $date_from = isset($_GET['date_from']) ? $_GET['date_from'] : '';
  $date_to = isset($_GET['date_to']) ? $_GET['date_to'] : '';
  $search_term = isset($_GET['search']) ? $_GET['search'] : '';
  
  // Build the query based on filters - Using prepared statements
  $query_params = [];
  $query = "SELECT * FROM expenses WHERE user_id = ?";
  $query_params[] = $userid;
  
  if (!empty($category_filter)) {
    $query .= " AND expensecategory = ?";
    $query_params[] = $category_filter;
  }
  
  if (!empty($date_from) && !empty($date_to)) {
    $query .= " AND expensedate BETWEEN ? AND ?";
    $query_params[] = $date_from;
    $query_params[] = $date_to;
  } else if (!empty($date_from)) {
    $query .= " AND expensedate >= ?";
    $query_params[] = $date_from;
  } else if (!empty($date_to)) {
    $query .= " AND expensedate <= ?";
    $query_params[] = $date_to;
  }
  
  if (!empty($search_term)) {
    // FIXED: Only search in expensecategory column since expensedescription doesn't exist
    // If you have a description column with a different name, replace it here
    $query .= " AND expensecategory LIKE ?";
    $query_params[] = "%$search_term%";
  }
  
  // Get total count for pagination using prepared statement
  $stmt = mysqli_prepare($con, $query);
  if ($stmt) {
    if (!empty($query_params)) {
      mysqli_stmt_bind_param($stmt, str_repeat('s', count($query_params)), ...$query_params);
    }
    mysqli_stmt_execute($stmt);
    $total_query = mysqli_stmt_get_result($stmt);
    $total_expenses = mysqli_num_rows($total_query);
    mysqli_stmt_close($stmt);
  } else {
    // Handle error
    $total_expenses = 0;
  }
  
  $total_pages = ceil($total_expenses / $results_per_page);
  
  // Add pagination to the query
  $query .= " ORDER BY expensedate DESC LIMIT ?, ?";
  $query_params[] = $offset;
  $query_params[] = $results_per_page;
  
  // Execute the filtered and paginated query with prepared statement
  $stmt = mysqli_prepare($con, $query);
  if ($stmt) {
    // Create the correct types string for bind_param
    $types = str_repeat('s', count($query_params) - 2) . 'ii'; // 'i' for the two integers: offset and limit
    mysqli_stmt_bind_param($stmt, $types, ...$query_params);
    mysqli_stmt_execute($stmt);
    $exp_fetched = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);
  } else {
    // Handle error
    $exp_fetched = false;
  }
  
  // Get distinct categories for the filter dropdown
  $categories = mysqli_query($con, "SELECT DISTINCT expensecategory FROM expenses WHERE user_id = '$userid'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Expense tracker manage expenses">
  <meta name="author" content="">

  <title>Plan The Analysis - Manage Expenses</title>

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


<body class="bg-gray-50 dark:bg-gray-900">
  <div class="flex h-screen overflow-hidden">
    <!-- Sidebar -->
<div id="sidebar-wrapper" class="bg-white dark:bg-gray-800 shadow-md w-64 flex-shrink-0 transition-all duration-300 ease-in-out">
  <!-- App Logo and Title -->
  <div class="py-6 px-4 bg-blue-600 text-white">
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
                <img class="rounded-full h-8 w-8 object-cover border-2 border-gray-200 dark:border-gray-700" src="<?php echo $userprofile ?>" alt="User">
                <span data-feather="chevron-down" class="h-4 w-4 text-gray-500 dark:text-gray-400"></span>
              </button>
              <div class="dropdown-menu origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 hidden" aria-labelledby="navbarDropdown">
                <a class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700" href="profile.php">Your Profile</a>
                <div class="border-t border-gray-100 dark:border-gray-700"></div>
                <a class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700" href="logout.php">Logout</a>
              </div>
            </div>
          </div>
        </div>
      </nav>

      <!-- Dashboard Content -->
      <div class="p-6">
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-100 mb-4 ">Manage Expenses</h1>
        
        <!-- Filters Section -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm mb-6">
          <div class="p-5">
            <form action="manage_expense.php" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6">
              <div>
                <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Category</label>
                <select name="category" id="category" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                  <option value="">All Categories</option>
                  <?php while($cat = mysqli_fetch_array($categories)) { ?>
                    <option value="<?php echo $cat['expensecategory']; ?>" <?php if($category_filter == $cat['expensecategory']) echo "selected"; ?>>
                      <?php echo $cat['expensecategory']; ?>
                    </option>
                  <?php } ?>
                </select>
              </div>
              
              <div>
                <label for="date_from" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date From</label>
                <input type="date" name="date_from" id="date_from" value="<?php echo $date_from; ?>" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
              </div>
              
              <div>
                <label for="date_to" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date To</label>
                <input type="date" name="date_to" id="date_to" value="<?php echo $date_to; ?>" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
              </div>
              
              <div class="flex items-end space-x-2">
                <div class="flex-grow">
                  <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Search</label>
                  <input type="text" name="search" id="search" value="<?php echo $search_term; ?>" placeholder="Search categories..." class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                </div>
                <button type="submit" class="h-10 px-4 py-2 bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-600 text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                  <span data-feather="search" class="h-4 w-4"></span>
                </button>
                
                <!-- Hidden field to preserve page when filtering -->
                <input type="hidden" name="page" value="1">
              </div>
            </form>
          </div>
        </div>
        
        <!-- Expenses Table -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
              <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                  <th scope="col" class="px-6 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">#</th>
                  <th scope="col" class="px-6 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                  <th scope="col" class="px-6 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Amount</th>
                  <th scope="col" class="px-6 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Expense Category</th>
                  <th scope="col" class="px-6 py-2 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Action</th>
                </tr>
              </thead>
              <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                <?php 
                $count = ($page - 1) * $results_per_page + 1;
                if ($exp_fetched) {
                  while ($row = mysqli_fetch_array($exp_fetched)) { 
                ?>
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"><?php echo $count; ?></td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"><?php echo $row['expensedate']; ?></td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"><?php echo 'Rs '.$row['expense']; ?></td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">
                      <?php echo $row['expensecategory']; ?>
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                    <a href="add_expense.php?edit=<?php echo $row['expense_id']; ?>" class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 mr-3">
                      <span data-feather="edit-2" class="h-5 w-5"></span>
                      <span class="sr-only">Edit</span>
                    </a>
                  </td>
                </tr>
                <?php $count++; } 
                }
                ?>
                
                <?php if (!$exp_fetched || mysqli_num_rows($exp_fetched) == 0) { ?>
                <tr>
                  <td colspan="5" class="px-6 py-10 text-center text-sm text-gray-500 dark:text-gray-400">
                    <div class="flex flex-col items-center justify-center">
                      <span data-feather="inbox" class="h-12 w-12 text-gray-400 dark:text-gray-500 mb-3"></span>
                      <h3 class="font-medium text-gray-900 dark:text-gray-100 mb-1">No expenses found</h3>
                      <p class="text-gray-500 dark:text-gray-400 mb-3">Try adjusting your search or filter to find what you're looking for.</p>
                     
                    </div>
                  </td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
          
          <!-- Pagination -->
          <?php if ($total_expenses > 0) { ?>
          <div class="px-6 py-3 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
              <!-- Results summary -->
              <div class="text-sm text-gray-700 dark:text-gray-300">
                Showing <span class="font-medium"><?php echo ($page - 1) * $results_per_page + 1; ?></span> to 
                <span class="font-medium"><?php echo min($page * $results_per_page, $total_expenses); ?></span> of 
                <span class="font-medium"><?php echo $total_expenses; ?></span> expenses
              </div>
              
              <!-- Pagination controls -->
              <div class="flex space-x-1">
                <?php
                // Create the query string for pagination links
                $query_string = '';
                if (!empty($category_filter)) $query_string .= '&category=' . urlencode($category_filter);
                if (!empty($date_from)) $query_string .= '&date_from=' . urlencode($date_from);
                if (!empty($date_to)) $query_string .= '&date_to=' . urlencode($date_to);
                if (!empty($search_term)) $query_string .= '&search=' . urlencode($search_term);
                ?>
                
                <!-- Previous page button -->
                <a href="<?php echo $page > 1 ? 'manage_expense.php?page=' . ($page - 1) . $query_string : 'javascript:void(0)'; ?>" 
                   class="<?php echo $page > 1 ? 'text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-gray-600' : 'text-gray-400 dark:text-gray-500 cursor-not-allowed'; ?> relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md bg-white dark:bg-gray-800">
                  <span data-feather="chevron-left" class="h-4 w-4"></span>
                  <span class="sr-only">Previous</span>
                </a>
                
                <!-- Page numbers -->
                <?php
                $start_page = max(1, $page - 2);
                $end_page = min($total_pages, $page + 2);
                
                // Show first page if not in range
                if ($start_page > 1) { ?>
                <a href="manage_expense.php?page=1<?php echo $query_string; ?>" 
                   class="relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md bg-white dark:bg-gray-800 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-gray-600">
                  1
                </a>
                <?php if ($start_page > 2) { ?>
                <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                  ...
                </span>
                <?php } 
                } 
                
                // Show page numbers
                for ($i = $start_page; $i <= $end_page; $i++) { ?>
                <a href="manage_expense.php?page=<?php echo $i . $query_string; ?>" 
                   class="relative inline-flex items-center px-4 py-2 border <?php echo $i == $page ? 'border-blue-500 dark:border-blue-400 z-10' : 'border-gray-300 dark:border-gray-600'; ?> text-sm font-medium rounded-md <?php echo $i == $page ? 'bg-blue-50 dark:bg-blue-900 text-blue-600 dark:text-blue-200' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-600'; ?>">
                  <?php echo $i; ?>
                </a>
                <?php } 
                
                // Show last page if not in range
                if ($end_page < $total_pages) { 
                  if ($end_page < $total_pages - 1) { ?>
                <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                  ...
                </span>
                <?php } ?>
                <a href="manage_expense.php?page=<?php echo $total_pages . $query_string; ?>" 
                   class="relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md bg-white dark:bg-gray-800 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-gray-600">
                  <?php echo $total_pages; ?>
                </a>
                <?php } ?>
                
                <!-- Next page button -->
                <a href="<?php echo $page < $total_pages ? 'manage_expense.php?page=' . ($page + 1) . $query_string : 'javascript:void(0)'; ?>" 
                   class="<?php echo $page < $total_pages ? 'text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-gray-600' : 'text-gray-400 dark:text-gray-500 cursor-not-allowed'; ?> relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md bg-white dark:bg-gray-800">
                  <span data-feather="chevron-right" class="h-4 w-4"></span>
                  <span class="sr-only">Next</span>
                </a>
              </div>
            </div>
          </div>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>

  <!-- Scripts -->
   
  <script>
    // Initialize Feather Icons and setup event listeners
    document.addEventListener('DOMContentLoaded', () => {
      feather.replace();
      
      // Dark mode toggle
      const themeToggleBtn = document.getElementById('theme-toggle');
      const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
      const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');
      
      // Set the initial icon based on the current theme
      if (localStorage.getItem('color-theme') === 'dark' || 
          (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        themeToggleLightIcon.classList.add('hidden');
        themeToggleDarkIcon.classList.remove('hidden');
      } else {
        themeToggleLightIcon.classList.remove('hidden');
        themeToggleDarkIcon.classList.add('hidden');
      }
      
      // Handle dark mode toggle click
      themeToggleBtn.addEventListener('click', function() {
        // Toggle icons
        themeToggleDarkIcon.classList.toggle('hidden');
        themeToggleLightIcon.classList.toggle('hidden');
        
        // Toggle dark class on html element
        if (localStorage.getItem('color-theme') === 'dark') {
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
  <!-- Add this script for auto-hiding alerts -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.remove();
        }, 5000);
    });
    
    // Close dropdown when clicking outside
    document.addEventListener('click', (e) => {
        if (!e.target.closest('.dropdown')) {
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                menu.classList.add('hidden');
            });
        }
    });
});
</script>

<!-- Add these styles -->
<style>
.alert {
    @apply p-4 mb-4 rounded-lg;
}
.alert-success {
    @apply bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200;
}
.alert-error {
    @apply bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200;
}
.pagination-link.disabled {
    @apply opacity-50 cursor-not-allowed;
}
/* Additional styles for pagination */
.pagination-active {
    @apply bg-blue-50 border-blue-500 text-blue-600 dark:bg-blue-900 dark:border-blue-400 dark:text-blue-200 z-10;
}
.pagination-inactive {
    @apply bg-white border-gray-300 text-gray-700 hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700;
}
</style>
</body>
</html>