<?php
include("session.php");

// Fetch all expenses for the user
$exp_fetched = mysqli_query($con, "SELECT * FROM expenses WHERE user_id = '$userid'");

// Fetch category sums for the user
$category_sums = mysqli_query($con, "SELECT expensecategory, SUM(expense) AS category_sum FROM expenses WHERE user_id = '$userid' GROUP BY expensecategory");

// Fetch all distinct categories for the dropdown list
$category_options = mysqli_query($con, "SELECT DISTINCT expensecategory FROM expenses WHERE user_id = '$userid'");


// Initialize the filter variables
$start_date = '';
$end_date = '';
$category = '';

// Check if the filter form is submitted
if (isset($_GET['show'])) {
    // Get the start and end dates from the form
    $start_date = $_GET['start_date'];
    $end_date = $_GET['end_date'];
    $category = $_GET['category'];

    // Build the SQL query to fetch expenses with date and category filter
    $query = "SELECT * FROM expenses WHERE user_id = '$userid'";
    if ($start_date && $end_date) {
        $query .= " AND expensedate BETWEEN '$start_date' AND '$end_date'";
    }
    if (!empty($category)) {
        $query .= " AND expensecategory = '$category'";
    }

    echo "<script>
    $(document).ready(function() {
        $('#showCategorySumsBtn').hide();
        $('#showFilterBtn').hide();
    });
    </script>";
    $exp_fetched = mysqli_query($con, $query);
   

}

else {
    // If the filter form is not submitted, fetch all expenses
    $exp_fetched = mysqli_query($con, "SELECT * FROM expenses WHERE user_id = '$userid'");
}




?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Expense Manager - Dashboard</title>
    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet">
    <!-- Feather JS for Icons -->
    <script src="js/feather.min.js"></script>
</head>

<body>
<div class="d-flex" id="wrapper">

<!-- Sidebar -->
<div class="border-right" id="sidebar-wrapper">
  <div class="user">
    <img class="img img-fluid rounded-circle" src="<?php echo $userprofile ?>" width="120">
    <h5><?php echo $username ?></h5>
    <p><?php echo $useremail ?></p>
  </div>
  <div class="sidebar-heading">Management</div>
  <div class="list-group list-group-flush">
    <a href="index.php" class="list-group-item list-group-item-action "><span data-feather="home"></span> Dashboard</a>
    <a href="add_expense.php" class="list-group-item list-group-item-action "><span data-feather="plus-square"></span> Add Expenses</a>
    <a href="manage_expense.php" class="list-group-item list-group-item-action "><span data-feather="dollar-sign"></span> Manage Expenses</a>
    <a href="reports.php" class="list-group-item list-group-item-action sidebar-active"><span data-feather="file-text"></span> Expenses Report</a>

  </div>
  <div class="sidebar-heading">Settings </div>
  <div class="list-group list-group-flush">
    <a href="profile.php" class="list-group-item list-group-item-action "><span data-feather="user"></span> Profile</a>
    <a href="logout.php" class="list-group-item list-group-item-action "><span data-feather="power"></span> Logout</a>
  </div>
</div>
<!-- /#sidebar-wrapper -->

            <div class="container-fluid">
                <h3 class="mt-4 text-center">Expenses Report</h3>
                <hr>
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div id="filterSection" style="display: none;">
                            <h5 class="mt-4 text-center">Filter Expenses</h5>
                         <form method="GET" action="reports.php" class="mb-4">
                            <div class="form-row">                     
                                <div class="col-md-4">
                                    <label for="start_date">Start Date:</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo $start_date; ?>">
                                </div>
                                <div class="col-md-4">
                                    <label for="endDate">End Date:</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo $end_date; ?>">
                                </div>
                                <div class="col-md-4">
                                    <label for="category">Category:</label>
                                    <select class="form-control" id="category" name="category">
                                    <option value="">All Categories</option>
                                        <option value="Food" <?php if ($category == 'Food') echo 'selected'; ?>>Food</option>
                                        <option value="Shopping" <?php if ($category == 'Shopping') echo 'selected'; ?>>Shopping</option>
                                        <option value="Medicine" <?php if ($category == 'Medicine') echo 'selected'; ?>>Medicine</option>
                                        <option value="Bills and Recharges" <?php if ($category == 'Bills and Recharges') echo 'selected'; ?>>Bills and Recharges</option>
                                        <option value="Entertainment" <?php if ($category == 'Entertainment') echo 'selected'; ?>>Entertainment</option>
                                        <option value="Clothings" <?php if ($category == 'Clothings') echo 'selected'; ?>>Clothings</option>
                                        <option value="Rent" <?php if ($category == 'Rent') echo 'selected'; ?>>Rent</option>
                                        <option value="Household items" <?php if ($category == 'Household items') echo 'selected'; ?>>Household items</option>
                                        <option value="Others" <?php if ($category == 'Others') echo 'selected'; ?>>Others</option>
                                    </select>
                                </div>
                            </div>

                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-primary" id="filterBtn_show" name='show'>Show</button>
                                <button class="btn btn-danger" id="backBtn">Back</button>
                            </div>
                        </div>
                        </form>

                        <table class="table table-hover table-bordered" id="Expense_report_main_table">
                            <thead>
                                <tr class="text-center">
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Payment Type</th>
                                    <th>Expense Category</th>
                                </tr>
                            </thead>
                            <?php $count = 1;
                            while ($row = mysqli_fetch_array($exp_fetched)) { ?>
                                <tr>
                                    <td><?php echo $count; ?></td>
                                    <td><?php echo $row['expensedate']; ?></td>
                                    <td><?php echo '$' . $row['expense']; ?></td>
                                    <td><?php echo $row['Payment_Type']; ?></td>
                                    <td><?php echo $row['expensecategory']; ?></td>
                                </tr>
                                <?php $count++;
                            } ?>
                        </table>

                        <div class="text-center">
                            <button class="btn btn-primary" id="showCategorySumsBtn">Show Category Sums</button>
                            <button class="btn btn-danger" id="showFilterBtn">Filter</button>
                        </div>

                        <div id="categorySums" style="display: none;">
                            <h5 class="mt-4 text-center">Category Sums</h5>
                            <table class="table table-hover table-bordered">
                                <thead>
                                    <tr class="text-center">
                                        <th>Expense Category</th>
                                        <th>Total Sum</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($category = mysqli_fetch_array($category_sums)) { ?>
                                        <tr>
                                            <td><?php echo $category['expensecategory']; ?></td>
                                            <td><?php echo '$' . $category['category_sum']; ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <div class="text-center mt-4">
                                <button class="btn btn-primary" id="backToExpensesBtn">Back to Expenses</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript -->
    <script src="js/jquery.slim.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/Chart.min.js"></script>
    <!-- Menu Toggle Script -->

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            $("#showCategorySumsBtn").click(function() {
                $("#categorySums").toggle();

                $("#Expense_report_main_table").hide();
                $("#showCategorySumsBtn").hide();
                $("#showFilterBtn").hide();
            });

            $("#showFilterBtn").click(function() {
                $("#filterSection").toggle();
                $("#Expense_report_main_table").hide();
                $("#showCategorySumsBtn").hide();
                $("#showFilterBtn").hide();

               
            });

            $("#filterBtn_show").click(function() {
               
                $("#Expense_report_filtered_table").show();

                $("#showCategorySumsBtn").hide();
                $("#showFilterBtn").hide();

            });

            $("#backBtn").click(function() {
                $("#filterSection").hide();
                $("#categorySums").hide();
                $("#Expense_report_main_table").show();
                $("#showCategorySumsBtn").show();
                $("#showFilterBtn").show();
                $("#Expense_report_filtered_table").hide();


            });

            $("#backToExpensesBtn").click(function() {
                $("#filterSection").hide();
                $("#categorySums").hide();

                $("#Expense_report_main_table").show();
                $("#showCategorySumsBtn").show();
                $("#showFilterBtn").show();

            });
        });
    </script>
    <script>
        $("#menu-toggle").click(function(e) {
            e.preventDefault();
            $("#wrapper").toggleClass("toggled");
        });
    </script>
    <script>
        feather.replace()
    </script>
</body>

</html>