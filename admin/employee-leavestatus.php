<?php
include "header.php";
?>

<?php

// Database connection
require_once "../connection.php";
// Pagination variables
$limit = 5; // Number of records per page
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Query to fetch employee leave records and calculate TotalLeave for each email
$sql = "SELECT email, status, COUNT(*) AS TotalLeave FROM emp_leave WHERE status = 'Accepted' GROUP BY email LIMIT $start, $limit";
$result = mysqli_query($conn, $sql);

$i = 1;

?>

<style>
    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        border: 1px solid black;
        padding: 9px;
    }

    th {
        background-color: black;
        color: red;
    }

</style>

<div class="container bg-white shadow">
    <div class="py-4 mt-5">
        <h4 class="text-center pb-3">TotalLeave (Per) </h4>
        <table class="table-hover text-center">
            <tr>
                <th>S.No.</th>
                <th>Email</th>
                <th>Status</th>
                <th>Total Leave🔻</th>
            </tr>
            <?php

            if ($result) {
                while ($rows = mysqli_fetch_assoc($result)) {
                    $email = $rows["email"];
                    $status = $rows["status"];
                    $TotalLeave = $rows["TotalLeave"];

                    // Output row data
                    ?>
                    <tr>
                        <td><?php echo "$i."; ?></td>
                        <td><?php echo $email; ?></td>
                        <td><?php echo $status; ?></td>
                        <td><?php echo $TotalLeave; ?></td>
                    </tr>
                    <?php
                    $i++;
                }
            }
            ?>

        </table>

        <!-- Pagination -->
        <div class="text-center mt-3">
            <?php
            // Query to count total pages
            $count_query = "SELECT COUNT(DISTINCT email) AS total FROM emp_leave WHERE status = 'Accepted'";
            $count_result = mysqli_query($conn, $count_query);
            $count_row = mysqli_fetch_assoc($count_result);
            $total_records = $count_row['total'];
            $total_pages = ceil($total_records / $limit);

            if ($page > 1) {
                echo "<a href='?page=" . ($page - 1) . "' class='btn btn-sm btn-outline-secondary mr-2'>Previous</a>";
            }
            for ($i = 1; $i <= $total_pages; $i++) {
                echo "<a href='?page=" . $i . "' class='btn btn-sm " . ($page == $i ? 'btn-secondary' : 'btn-outline-secondary') . " mr-2'>$i</a>";
            }
            if ($page < $total_pages) {
                echo "<a href='?page=" . ($page + 1) . "' class='btn btn-sm btn-outline-secondary'>Next</a>";
            }
            ?>
        </div>

        <!-- Showing entries -->
        <div class="text-center mt-3">
            <?php
            $end = min($start + $limit, $total_records);
            echo "Showing " . ($start + 1) . " to $end of $total_records entries";
            ?>
        </div>
    </div>
</div>

<?php
include "footer.php";
?>
