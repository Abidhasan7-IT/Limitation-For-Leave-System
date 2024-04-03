<?php
include "header.php";

?>


<?php

// database connection
require_once "../connection.php";

$currentDay = date('Y-m-d', strtotime("today"));
$tomarrow = date('Y-m-d', strtotime("+1 day"));

$i = 1;
$today_leave = 0;
$tomarrow_leave = 0;
$this_week = 0;
$next_week = 0;

// total admin
$select_admins = "SELECT * FROM admin";
$total_admins = mysqli_query($conn, $select_admins);

// total employee
$select_emp = "SELECT * FROM employee";
$total_emp = mysqli_query($conn, $select_emp);

// employee on leave
$emp_leave  = "SELECT * FROM emp_leave";
$total_leaves = mysqli_query($conn, $emp_leave);

if (mysqli_num_rows($total_leaves) > 0) {
    while ($leave = mysqli_fetch_assoc($total_leaves)) {
        $leave = $leave["start_date"];

        //daywise
        if ($currentDay == $leave) {
            $today_leave += 1;
        } elseif ($tomarrow == $leave) {
            $tomarrow_leave += 1;
        }
    }
} 
// else {
//     echo "no leave found";
// }


// highest paid employee
$sql_highest_salary =  "SELECT * FROM employee ORDER BY salary DESC";
$emp_ = mysqli_query($conn, $sql_highest_salary);

// total salary for
$sql_salary= "SELECT * FROM employee where salary";
$money= $conn-> query($sql_salary);

$sl= 0;
$amount=0;
while ($total= $money -> fetch_array()){
    $total_amount= $total['salary'];
    $sl++;

    $amount += $total_amount;
}


?>

<!-- dashboard Start -->
<div class="container-fluid px-4 pt-3 mb-2">
    <div class="row g-5">
        <div class="col-sm-6 col-xl-4">
            <div class="bg-warning rounded d-flex align-items-center justify-content-between p-4">
                <div class="ms-2 text-light">
                    <p class="mb-2 text-center heading"><i class="fas fa-user-check fa-0.5x" style="color: #ffffff;"></i> Attendances</p>
                    <p class=" heading">Total present : </p>
                    <a href="#" class="text-center "><b>View All</b></a>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-4">
            <div class="bg-success rounded d-flex align-items-center justify-content-between p-4">
                <div class="ms-2 text-light">
                    <p class="mb-2 text-center heading"><i class="fas fa-user-check fa-0.5x" style="color: #ffffff;"></i> Admins</p>
                    <p class=" heading">Total Admin : <?php echo mysqli_num_rows($total_admins); ?> </p>
                    <a href="manage-admin.php" class="text-center "><b>View All Admins</b></a>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-4">
            <div class="bg-danger rounded d-flex align-items-center justify-content-between p-4">
                <div class="ms-2 text-light">
                    <p class="mb-2 text-center heading"><i class="fas fa-users fa-0.5x" style="color: #ffffff;"></i> Employees</p>
                    <p class=" heading">Total Employees : <?php echo mysqli_num_rows($total_emp);  ?> </p>
                    <a href="manage-employee.php"> <b>View All Employees</b></a>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-4">
            <div class="bg-dark rounded d-flex align-items-center justify-content-between p-3">
                <div class="ms-1 text-light">
                    <p class=" text-center heading"> <i class="fas fa-user fa-0.5x" style="color: #ffffff;"></i> Leave(Daywise)</p>
                    <p class="heading">Today : <?php echo $today_leave;  ?> </p>
                    <p>Tomorrow : <?php echo  $tomarrow_leave;  ?> </p>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-4">
            <div class="bg-primary rounded d-flex align-items-center justify-content-between p-3">
                <div class="ms-1 text-light">
                    <p class=" text-center heading">$ </p>
                    <p class="heading">Employee Expenditure</p>
                    <p class="text-center"> Total: <?php echo $amount .' TK'; ?> </p>
                </div>
            </div>
        </div>

    </div>
</div>
<!-- dashboard End -->

<!-- Table start -->
<div class="container-fluid pt-3 px-3 mb-3">
    <div class="bg-light text-center rounded p-3">
        <div class="d-flex align-items-center justify-content-around mb-1">
            <h5>Employee Leadership </h5>
            <form method="POST" action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" class="mb-2">
                <input type="search" name="search" class="rounded-3 ">
                <button class="btn btn-dark text-light mb-1" type="submit" name="submit">Search</button>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table text-start align-middle table-bordered table-hover mb-0">

                <thead>
                    <tr class="bg-dark text-light text-center">
                        <th scope="col">Employee's Id</th>
                        <th scope="col">Employee's Name</th>
                        <th scope="col">Employee's Email</th>
                        <th scope="col">Salary in Rs.</th>
                        <th scope="col">Position</th>
                    </tr>
                </thead>

                <?php
                
                if (isset($_POST['submit'])) {
                    $search = $_POST['search'];
                        $mytable = "SELECT * FROM employee where id like '%$search%' or name like '%$search%' ";
                        $output = mysqli_query($conn, $mytable);


                        if (mysqli_num_rows($output) > 0) {
                            foreach ($output as $row) {

                            // $row = mysqli_fetch_assoc($output);
                            echo ' <tbody> 
                            <tr>
                            <td>' . $row['id'] . '</td>
                            <td>' . $row['name'] . '</td>
                            <td>' . $row['email'] . '</td>
                            <td>' . $row['salary'] . '</td>
                            <td>' . $row['Position'] . '</td>
                        </tr>
                            
                            </tbody>';
                        }
                     } else {
                            echo '<td class=" text-center text-danger">Data Not Found. </td>';
                        }

                    }
                    $i++;
                ?>

            </table>
        </div>
    </div>

</div>
<!--dashbord  End -->


<!-- Footer Start -->

<?php
include "footer.php";

?>