<?php
include("session.php");
$update = false;
$del = false;
$expenseamount = "";
$expensedate = date("Y-m-d");
$expensecategory = "Entertainment";
$other_category = ""; // Added variable for custom category

if (isset($_POST['add'])) {
    $expenseamount = $_POST['expenseamount'];
    $expensedate = $_POST['expensedate'];
    $expensecategory = $_POST['expensecategory'];
    
    // Check if "Others" is selected and use the custom category input
    if ($expensecategory == "Others" && isset($_POST['other_category']) && !empty($_POST['other_category'])) {
        $expensecategory = $_POST['other_category'];
    }

    $expenses = "INSERT INTO expenses (user_id, expense,expensedate,expensecategory) VALUES ('$userid', '$expenseamount','$expensedate','$expensecategory')";
    $result = mysqli_query($con, $expenses) or die("Something Went Wrong!");
    header('location: add_expense.php');
}

if (isset($_POST['update'])) {
    $id = $_GET['edit'];
    $expenseamount = $_POST['expenseamount'];
    $expensedate = $_POST['expensedate'];
    $expensecategory = $_POST['expensecategory'];
    
    // Check if "Others" is selected and use the custom category input
    if ($expensecategory == "Others" && isset($_POST['other_category']) && !empty($_POST['other_category'])) {
        $expensecategory = $_POST['other_category'];
    }

    $sql = "UPDATE expenses SET expense='$expenseamount', expensedate='$expensedate', expensecategory='$expensecategory' WHERE user_id='$userid' AND expense_id='$id'";
    if (mysqli_query($con, $sql)) {
        echo "Records were updated successfully.";
    } else {
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($con);
    }
    header('location: manage_expense.php');
}

if (isset($_POST['delete'])) {
    $id = $_GET['delete'];
    $expenseamount = $_POST['expenseamount'];
    $expensedate = $_POST['expensedate'];
    $expensecategory = $_POST['expensecategory'];

    $sql = "DELETE FROM expenses WHERE user_id='$userid' AND expense_id='$id'";
    if (mysqli_query($con, $sql)) {
        echo "Records were updated successfully.";
    } else {
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($con);
    }
    header('location: manage_expense.php');
}

if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $update = true;
    $record = mysqli_query($con, "SELECT * FROM expenses WHERE user_id='$userid' AND expense_id=$id");
    if (mysqli_num_rows($record) == 1) {
        $n = mysqli_fetch_array($record);
        $expenseamount = $n['expense'];
        $expensedate = $n['expensedate'];
        $expensecategory = $n['expensecategory'];
        
        // Check if the category is not in the predefined list - it's a custom category
        $predefined_categories = ['Medicine', 'Food', 'Bills & Recharges', 'Entertainment', 'Clothings', 'Rent', 'Household Items', 'Others'];
        if (!in_array($expensecategory, $predefined_categories)) {
            $other_category = $expensecategory;
            $expensecategory = 'Others';
        }
    } else {
        echo ("WARNING: AUTHORIZATION ERROR: Trying to Access Unauthorized data");
    }
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $del = true;
    $record = mysqli_query($con, "SELECT * FROM expenses WHERE user_id='$userid' AND expense_id=$id");

    if (mysqli_num_rows($record) == 1) {
        $n = mysqli_fetch_array($record);
        $expenseamount = $n['expense'];
        $expensedate = $n['expensedate'];
        $expensecategory = $n['expensecategory'];
        
        // Check if the category is not in the predefined list - it's a custom category
        $predefined_categories = ['Medicine', 'Food', 'Bills & Recharges', 'Entertainment', 'Clothings', 'Rent', 'Household Items', 'Others'];
        if (!in_array($expensecategory, $predefined_categories)) {
            $other_category = $expensecategory;
            $expensecategory = 'Others';
        }
    } else {
        echo ("WARNING: AUTHORIZATION ERROR: Trying to Access Unauthorized data");
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Expense tracker add expenses">
    <meta name="author" content="">

    <title>Plan The Analysis - Add Expense</title>

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

<body class="bg-gray-50 dark:bg-gray-900 h-screen flex flex-col">
  <div class="flex flex-1 overflow-hidden">
    <!-- Sidebar -->
<div id="sidebar-wrapper" class="bg-white dark:bg-gray-800 shadow-md w-64 flex-shrink-0 transition-all duration-300 ease-in-out flex flex-col h-full">
  <!-- App Logo and Title -->
  <div class="py-6 px-4 bg-blue-600 dark:bg-blue-800 text-white">
    <div class="flex items-center justify-center mb-3">
      <span data-feather="bar-chart-2" class="h-8 w-8 mr-2"></span>
      <h1 class="text-xl font-bold">Plan The Expense</h1>
    </div>
    <div class="text-xs text-center text-blue-100">Expense Management System</div>
  </div>
  
  
  
  <!-- Navigation -->
  <div class="px-4 py-3 flex-grow">
    <div class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Main Menu</div>
    
    <div class="space-y-1">
      <a href="index.php" class="flex items-center px-4 py-2.5 text-gray-700 dark:text-gray-200 rounded-md hover:bg-blue-50 dark:hover:bg-blue-900 hover:text-blue-600 dark:hover:text-blue-300 group">
        <span data-feather="home" class="h-5 w-5 mr-3 text-gray-400 dark:text-gray-500 group-hover:text-blue-500 dark:group-hover:text-blue-400"></span>
        <span>Dashboard</span>
      </a>
      <a href="add_expense.php" class="flex items-center px-4 py-2.5 text-gray-700 dark:text-gray-200 rounded-md hover:bg-blue-50 dark:hover:bg-blue-900 hover:text-blue-600 dark:hover:text-blue-300 group">
        <span data-feather="plus-square" class="h-5 w-5 mr-3 text-gray-400 dark:text-gray-500 group-hover:text-blue-500 dark:group-hover:text-blue-400"></span>
        <span>Add Expenses</span>
      </a>
      <a href="manage_expense.php" class="flex items-center px-4 py-2.5 text-gray-700 dark:text-gray-200 rounded-md hover:bg-blue-50 dark:hover:bg-blue-900 hover:text-blue-600 dark:hover:text-blue-300 group">
        <span data-feather="list" class="h-5 w-5 mr-3 text-gray-400 dark:text-gray-500 group-hover:text-blue-500 dark:group-hover:text-blue-400"></span>
        <span>Manage Expenses</span>
      </a>
      <a href="about.php" class="flex items-center px-4 py-2.5 text-gray-700 dark:text-gray-200 rounded-md hover:bg-blue-50 dark:hover:bg-blue-900 hover:text-blue-600 dark:hover:text-blue-300 group">
        <span data-feather="info" class="h-5 w-5 mr-3 text-gray-400 dark:text-gray-500 group-hover:text-blue-500 dark:group-hover:text-blue-400"></span>
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
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Navigation -->
            <nav class="bg-white dark:bg-gray-900 shadow-sm px-6 py-3 flex items-center justify-between theme-transition">
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
              <div class="dropdown-menu origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white dark:bg-gray-900 ring-1 ring-black ring-opacity-5 hidden theme-transition" aria-labelledby="navbarDropdown">
                <a class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 theme-transition" href="profile.php">Your Profile</a>
                <div class="border-t border-gray-100 dark:border-gray-700 theme-transition"></div>
                <a class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 theme-transition" href="logout.php">Logout</a>
              </div>
            </div>
          </div>
        </div>
      </nav>

            <!-- Page Content - Modified to fill the screen better -->
            <div class="flex-1 overflow-auto">
                <div class="h-full flex flex-col">
                    <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-6 p-8 text-center">Add Your Daily Expenses</h1>
                    <!-- Removed container width constraints and padding adjustments -->
                    <div class="flex-grow bg-white dark:bg-gray-800 shadow-sm p-8 mx-4 mb-4 rounded-lg">
                        <form action="" method="POST" class="h-full">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Left Column -->
                                <div>
                                    <!-- Amount Field -->
                                    <div class="mb-6">
                                        <label for="expenseamount" class="block text-2xl font-medium text-gray-700 dark:text-gray-300 mb-8">Enter Amount </label>
                                        <input type="number"   
                                               class="w-full px-8 py-6 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" 
                                               value="<?php echo $expenseamount; ?>" 
                                               id="expenseamount" 
                                               name="expenseamount" 
                                               required>
                                    </div>
                                    
                                    <!-- Date Field -->
                                    <div class="mb-6">
                                        <label for="expensedate" class="block text-2xl font-medium text-gray-700 dark:text-gray-300 mb-8">Date</label>
                                        <input type="date" 
                                               class="w-full px-8 py-6 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" 
                                               value="<?php echo $expensedate; ?>" 
                                               name="expensedate" 
                                               id="expensedate" 
                                               required>
                                    </div>
                                </div>
                                
                                <!-- Right Column -->
                                <div>
                                    <!-- Category Field -->
                                    <div class="mb-6">
                                        <label class="block text-2xl font-medium text-gray-700 dark:text-gray-300 mb-8">Category</label>
                                        
                                        <!-- Better organized grid for categories -->
                                        <div class="grid grid-cols-2 lg:grid-cols-2 gap-4">
                                            <!-- Medicine -->
                                            <div class="flex items-center">
                                                <input class="h-6 w-6 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600" 
                                                       type="radio" 
                                                       name="expensecategory" 
                                                       id="expensecategory4" 
                                                       value="Medicine" 
                                                       <?php echo ($expensecategory == 'Medicine') ? 'checked' : '' ?>>
                                                <label class="ml-2 block text-1xl text-gray-700 dark:text-gray-300" for="expensecategory4">
                                                    Medicine
                                                </label>
                                            </div>
                                            
                                            <!-- Food -->
                                            <div class="flex items-center">
                                                <input class="h-6 w-6 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600" 
                                                       type="radio" 
                                                       name="expensecategory" 
                                                       id="expensecategory3" 
                                                       value="Food" 
                                                       <?php echo ($expensecategory == 'Food') ? 'checked' : '' ?>>
                                                <label class="ml-2 block text-1xl text-gray-700 dark:text-gray-300" for="expensecategory3">
                                                    Food
                                                </label>
                                            </div>
                                            
                                            <!-- Bills & Recharges -->
                                            <div class="flex items-center">
                                                <input class="h-6 w-6 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600" 
                                                       type="radio" 
                                                       name="expensecategory" 
                                                       id="expensecategory2" 
                                                       value="Bills & Recharges" 
                                                       <?php echo ($expensecategory == 'Bills & Recharges') ? 'checked' : '' ?>>
                                                <label class="ml-2 block text-1xl text-gray-700 dark:text-gray-300" for="expensecategory2">
                                                    Bills and Recharges
                                                </label>
                                            </div>
                                            
                                            <!-- Entertainment -->
                                            <div class="flex items-center">
                                                <input class="h-6 w-6 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600" 
                                                       type="radio" 
                                                       name="expensecategory" 
                                                       id="expensecategory1" 
                                                       value="Entertainment" 
                                                       <?php echo ($expensecategory == 'Entertainment') ? 'checked' : '' ?>>
                                                <label class="ml-2 block text-1xl text-gray-700 dark:text-gray-300" for="expensecategory1">
                                                    Entertainment
                                                </label>
                                            </div>
                                            
                                            <!-- Clothings -->
                                            <div class="flex items-center">
                                                <input class="h-6 w-6 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600" 
                                                       type="radio" 
                                                       name="expensecategory" 
                                                       id="expensecategory7" 
                                                       value="Clothings" 
                                                       <?php echo ($expensecategory == 'Clothings') ? 'checked' : '' ?>>
                                                <label class="ml-2 block text-1xl text-gray-700 dark:text-gray-300" for="expensecategory7">
                                                    Clothings
                                                </label>
                                            </div>
                                            
                                            <!-- Rent -->
                                            <div class="flex items-center">
                                                <input class="h-6 w-6 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600" 
                                                       type="radio" 
                                                       name="expensecategory" 
                                                       id="expensecategory6" 
                                                       value="Rent" 
                                                       <?php echo ($expensecategory == 'Rent') ? 'checked' : '' ?>>
                                                <label class="ml-2 block text-1xl text-gray-700 dark:text-gray-300" for="expensecategory6">
                                                    Rent
                                                </label>
                                            </div>
                                            
                                            <!-- Household Items -->
                                            <div class="flex items-center">
                                                <input class="h-6 w-6 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600" 
                                                       type="radio" 
                                                       name="expensecategory" 
                                                       id="expensecategory8" 
                                                       value="Household Items" 
                                                       <?php echo ($expensecategory == 'Household Items') ? 'checked' : '' ?>>
                                                <label class="ml-2 block text-1xl text-gray-700 dark:text-gray-300" for="expensecategory8">
                                                    Household Items
                                                </label>
                                            </div>
                                            
                                            <!-- Others -->
                                            <div class="flex items-center">
                                                <input class="h-6 w-6 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600" 
                                                       type="radio" 
                                                       name="expensecategory" 
                                                       id="expensecategory5" 
                                                       value="Others" 
                                                       <?php echo ($expensecategory == 'Others') ? 'checked' : '' ?>
                                                       onclick="toggleOtherCategory()">
                                                <label class="ml-2 block text-1xl text-gray-700 dark:text-gray-300" for="expensecategory5">
                                                    Others
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Other Category Text Field (Hidden by default) -->
                                    <div id="otherCategoryField" class="<?php echo ($expensecategory == 'Others') ? '' : 'hidden'; ?>">
                                        <label for="other_category" class="block text-1xl font-medium text-gray-700 dark:text-gray-300 mb-2">Specify Category</label>
                                        <input type="text" 
                                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" 
                                               value="<?php echo $other_category; ?>" 
                                               id="other_category" 
                                               name="other_category" 
                                               placeholder="Enter custom category name">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Submit Button -->
                            <div class="mt-8">
                                <?php if ($update == true) : ?>
                                    <button class="w-full py-4 bg-yellow-500 hover:bg-yellow-600 text-white text-lg font-medium rounded-md transition duration-200" 
                                            type="submit" 
                                            name="update">
                                        Update Expense
                                    </button>
                                <?php elseif ($del == true) : ?>
                                    <button class="w-full py-4 bg-red-500 hover:bg-red-600 text-white text-lg font-medium rounded-md transition duration-200" 
                                            type="submit" 
                                            name="delete">
                                        Delete Expense
                                    </button>
                                <?php else : ?>
                                    <button class="w-full py-4 bg-green-500 hover:bg-green-600 text-white text-lg font-medium rounded-md transition duration-200" 
                                            type="submit" 
                                            name="add">
                                        Add Expense
                                    </button>
                                <?php endif ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // Function to toggle the visibility of the "Other Category" text field
        function toggleOtherCategory() {
            var otherCategoryField = document.getElementById('otherCategoryField');
            var otherRadio = document.getElementById('expensecategory5');
            
            if (otherRadio.checked) {
                otherCategoryField.classList.remove('hidden');
            } else {
                otherCategoryField.classList.add('hidden');
            }
        }
        
        // Initialize Feather Icons and setup event listeners
        document.addEventListener('DOMContentLoaded', () => {
            feather.replace();
            
            // Setup category radios to toggle the other category field
            const categoryRadios = document.querySelectorAll('input[name="expensecategory"]');
            categoryRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    toggleOtherCategory();
                });
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
            
            // Dark mode toggle functionality
            var themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
            var themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

            // Make sure both are hidden initially to avoid showing both at once
            themeToggleDarkIcon.classList.add('hidden');
            themeToggleLightIcon.classList.add('hidden');

            // Show the appropriate icon based on current theme
            if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                // Dark mode is active, show the light/sun icon
                themeToggleLightIcon.classList.remove('hidden');
            } else {
                // Light mode is active, show the dark/moon icon
                themeToggleDarkIcon.classList.remove('hidden');
            }

            // Toggle dark mode on button click
            document.getElementById('theme-toggle').addEventListener('click', function() {
                // Toggle icons
                themeToggleDarkIcon.classList.toggle('hidden');
                themeToggleLightIcon.classList.toggle('hidden');

                // If set via local storage previously
                if (localStorage.getItem('color-theme')) {
                    if (localStorage.getItem('color-theme') === 'light') {
                        document.documentElement.classList.add('dark');
                        localStorage.setItem('color-theme', 'dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                        localStorage.setItem('color-theme', 'light');
                    }
                } else {
                    // If NOT set via local storage previously
                    if (document.documentElement.classList.contains('dark')) {
                        document.documentElement.classList.remove('dark');
                        localStorage.setItem('color-theme', 'light');
                    } else {
                        document.documentElement.classList.add('dark');
                        localStorage.setItem('color-theme', 'dark');
                    }
                }
            });
        });
    </script>
</body>
</html>