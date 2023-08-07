<?php
include("session.php");

// Initialize the filter variables
$start_date = '';
$end_date = '';
$category = '';

// Check if the filter form is submitted
if (isset($_GET['filter'])) {
    // Get the start and end dates from the form
    $start_date = $_GET['start_date'];
    $end_date = $_GET['end_date'];
    $category = $_GET['category'];

    // Build the SQL query to fetch expenses with date and category filter
    $query = "SELECT * FROM expenses WHERE user_id = '$userid'";
    if ($start_date && $end_date) {
        $query .= " AND expensedate BETWEEN '$start_date' AND '$end_date'";
    }
    if ($category) {
        $query .= " AND expensecategory = '$category'";
    }
    $exp_fetched = mysqli_query($con, $query);
} else {
    // If the filter form is not submitted, fetch all expenses
    $exp_fetched = mysqli_query($con, "SELECT * FROM expenses WHERE user_id = '$userid'");
}

$category_sums = mysqli_query($con, "SELECT expensecategory, SUM(expense) AS category_sum FROM expenses WHERE user_id = '$userid' GROUP BY expensecategory");
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
                <a href="index.php" class="list-group-item list-group-item-action"><span data-feather="home"></span> Dashboard</a>
                <a href="add_expense.php" class="list-group-item list-group-item-action "><span data-feather="plus-square"></span> Add Expenses</a>
                <a href="manage_expense.php" class="list-group-item list-group-item-action"><span data-feather="dollar-sign"></span> Manage Expenses</a>
                <a href="reports.php" class="list-group-item list-group-item-action sidebar-active"><span data-feather="file-text"></span> Expenses Report</a>
            </div>
            <div class="sidebar-heading">Settings</div>
            <div class="list-group list-group-flush">
                <a href="profile.php" class="list-group-item list-group-item-action "><span data-feather="user"></span> Profile</a>
                <a href="logout.php" class="list-group-item list-group-item-action "><span data-feather="power"></span> Logout</a>
            </div>
        </div>
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div id="page-content-wrapper">
            <nav class="navbar navbar-expand-lg navbar-light  border-bottom">
                <button class="toggler" type="button" id="menu-toggle" aria-expanded="false">
                    <span data-feather="menu"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img class="img img-fluid rounded-circle" src="<?php echo $userprofile ?>" width="25">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="#">Your Profile</a>
                                <a class="dropdown-item" href="#">Edit Profile</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="logout.php">Logout</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>

            <div class="container-fluid">
                <h3 class="mt-4 text-center">Expenses Report</h3>
                <hr>
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <form method="GET" action="trying.php" class="mb-4" id='filter_options'>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="start_date">Start Date</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo $start_date; ?>">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="end_date">End Date</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo $end_date; ?>">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="category">Category</label>
                                    <select class="form-control" id="category" name="category">
                                        <option value="">All Categories</option>
                                        <option value="Food" <?php if ($category == 'Food') echo 'selected'; ?>>Food</option>
                                        <option value="Transportation" <?php if ($category == 'Transportation') echo 'selected'; ?>>Transportation</option>
                                        <option value="Shopping" <?php if ($category == 'Shopping') echo 'selected'; ?>>Shopping</option>
                                    </select>
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary" name="filter">Filter</button>
                                <button type="submit" class="btn btn-secondary" name="reset">Reset</button>
                            </div>
                        </form>

                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr class="text-center">
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Payment Type</th>
                                    <th>Expense Category</th>
                                </tr>
                            </thead>
                            <?php
                            $count = 1;
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
    <script>
        $(document).ready(function() {
            $("#showCategorySumsBtn").click(function() {
                $("#categorySums").toggle();
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
