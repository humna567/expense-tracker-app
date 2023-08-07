<?php
include("session.php");

$start_date = $_GET['start_date'];
$end_date = $_GET['end_date'];
$category = $_GET['category'];

$filter_query = "SELECT * FROM expenses WHERE user_id = '$userid'";
if (!empty($start_date) && !empty($end_date)) {
    $filter_query .= " AND expensedate BETWEEN '$start_date' AND '$end_date'";
}
if (!empty($category)) {
    $filter_query .= " AND expensecategory = '$category'";
}
$filtered_expenses = mysqli_query($con, $filter_query);

while ($filtered_expense = mysqli_fetch_array($filtered_expenses)) {
    echo "<tr>";
    echo "<td>" . $filtered_expense['expenseid'] . "</td>";
    echo "<td>" . $filtered_expense['expensedate'] . "</td>";
    echo "<td>" . $filtered_expense['expensecategory'] . "</td>";
    echo "<td>" . $filtered_expense['expense'] . "</td>";
    echo "</tr>";
}
?>
