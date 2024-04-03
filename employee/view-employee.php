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

    // Search query
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $search_condition = $search ? "WHERE name LIKE '%$search%' OR email LIKE '%$search%'" : '';

    // Fetch data from the database based on search query and pagination
    $sql = "SELECT * FROM employee $search_condition LIMIT $start, $limit";
    $result = mysqli_query($conn , $sql);

    $i = 1;
?>

<style>
table, th, td {
  border: 1px solid black;
  padding: 15px;
}
table {
  border-spacing: 10px;
}
</style>

<div class="container bg-white shadow">
    <div class="py-4 mt-5"> 
    <div class='text d-flex justify-content-around pb-2'>
    <h4 class="text-center pb-3">All Employees</h4>
    <!-- Search form -->
    <form method="GET" action="" style="width: 30%;">
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Search Employee" name="search">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </div>
        </form>
    </div>

    <table style="width:100%" class="table-hover text-center ">
    <tr class="bg-dark">
        <th>Employee Id</th>
        <th>Name</th>
        <th>Email</th> 
        <th>Gender</th>
        <th>Date of Birth</th>
        <th>Position</th>
        <th>Salary</th>
        <th>Profile</th>
    </tr>
    <?php 
    if(mysqli_num_rows($result) > 0){
        while($rows = mysqli_fetch_assoc($result)){
            $name = $rows["name"];
            $email = $rows["email"];
            $dob = $rows["dob"];
            $gender = $rows["gender"];
            $Position = $rows["Position"];
            $salary = $rows["salary"];   
            $id = $rows["id"];
            
            // Display "You" for the logged-in user
            if($email == $_SESSION["email"]){
                $name = "{$name} (You)";
            } 
            
            // Handling empty values
            $gender = $gender ? $gender : "Not Defined";
            $dob = $dob ? $dob : "Not Defined";
            $Position = $Position ? $Position : "Not Available";
            $salary = $salary ? $salary . " TK." : "Not Defined";

            // Displaying table rows
            echo "<tr>";
            echo "<td>$id</td>";
            echo "<td>$name</td>";
            echo "<td>$email</td>";
            echo "<td>$gender</td>";
            echo "<td>$dob</td>";
            echo "<td>$Position</td>";
            echo "<td>$salary</td>";
            echo "<td>";   
            if($email == $_SESSION["email"]){ 
                echo "<a href='profile.php' class='btn btn-success float-right'>Profile</a>"; 
            }
            echo "</td>";
            echo "</tr>";
            $i++;
        }
    } else {
        echo "<tr><td colspan='8'>No employees found</td></tr>";
    }
    ?>
    </table>
    

<!-- Pagination links -->
<div class="container text-center mt-3">
<?php
            $sql = "SELECT COUNT(id) AS total FROM employee $search_condition";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);
            $total_records = $row['total'];
            $total_pages = ceil($total_records / $limit);
            if ($page > 1) {
                echo "<a href='?page=".($page - 1)."&search=$search' class='btn btn-sm btn-outline-secondary mr-2'>Previous</a>";
            } 
            for ($i = 1; $i <= $total_pages; $i++) {
                echo "<a href='?page=".$i."&search=$search' class='btn btn-sm ".($page == $i ? 'btn-secondary' : 'btn-outline-secondary')." mr-2'>$i</a>";
            }
            if ($page < $total_pages) {
                echo "<a href='?page=".($page + 1)."&search=$search' class='btn btn-sm btn-outline-secondary'>Next</a>";
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
