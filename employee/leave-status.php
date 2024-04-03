<?php 
include "header.php";
?>
 
<?php 
$email = $_SESSION['email'];
//  database connection
require_once "../connection.php";

// Pagination variables
$limit = 5; // Number of records per page
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;

$sql = "SELECT * FROM emp_leave WHERE email = '$email' LIMIT $start, $limit";
$result = mysqli_query($conn , $sql);

$i = ($page - 1) * $limit + 1;

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
   <h4 class="text-center pb-3">Leave Status</h4>
   <table style="width:100%" class="table-hover text-center ">
   <tr class="bg-success text-light">
       <th>S.No.</th>
       <th>Starting Date</th>
       <th>Ending Date</th> 
       <th>Total Days</th>
       <th>LeaveType</th>
       <th>Comment</th>
       <th>Status</th>
       <th>Action</th>
   </tr>
   <?php 
   
   if(mysqli_num_rows($result) > 0) {
       while($rows = mysqli_fetch_assoc($result)) {
           $start_date = $rows["start_date"];
           $last_date = $rows["last_date"];
           $LeaveType = $rows["LeaveType"];
           $reason = $rows["reason"];
           $status = $rows["status"]; 
           $id = $rows["id"];   
           ?>
           <tr>
               <td><?php echo $i; ?></td>
               <td><?php echo date("jS F", strtotime($start_date)); ?></td>
               <td><?php echo date("jS F", strtotime($last_date)); ?></td>
               <td>
                   <?php 
                   $date1 = date_create($start_date);
                   $date2 = date_create($last_date);
                   $diff = date_diff($date1, $date2); 
                   echo $diff->format("%a days");
                   ?>
               </td>
               <td><?php echo $LeaveType; ?></td> 
               <td><?php echo $reason; ?></td> 
               <td><?php echo $status; ?></td> 
               <td>  
                   <a href='delete-leave.php?id=<?php echo $id; ?>' id='bin' class='btn-sm btn-danger '> <span><i class='fa fa-trash'></i></span> </a>
               </td>
           </tr>
           <?php 
           $i++;
       }
   } else {
       echo "<tr><td colspan='8' class='text-center'>No records found.</td></tr>";
   }
   ?>
   </table>

   <!-- Pagination -->
   <div class="text-center mt-3">
       <?php
       $total_query = "SELECT COUNT(*) AS total FROM emp_leave WHERE email = '$email'";
       $total_result = mysqli_query($conn, $total_query);
       $total_row = mysqli_fetch_assoc($total_result);
       $total_records = $total_row['total'];
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
   </div>

</div>

<?php 
include "footer.php";
?>
