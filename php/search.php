<?php
require_once 'auth.php';
requireLogin();
require('db.php');
$return = '';
if (isset($_POST["query"])) {
    $search = mysqli_real_escape_string($conn, $_POST["query"]);
    $query = "SELECT * FROM orders
	WHERE id  LIKE '%" . $search . "%'
	OR name LIKE '%" . $search . "%' 
	OR address LIKE '%" . $search . "%' 
	OR state LIKE '%" . $search . "%' 
	OR jamb_waec LIKE '%" . $search . "%' 
	OR delivery_status LIKE '%" . $search . "%' 
	OR updated_at LIKE '%" . $search . "%' 
	OR created_at LIKE '%" . $search . "%' 
	OR phone LIKE '%" . $search . "%' 
	";
    // else
    // {
    // 	$query = "SELECT * FROM orders";
    // }
    
    $result = mysqli_query($conn, $query);
    echo $result;
    if (mysqli_num_rows($result) > 0) {
        $return .= '
	<div class="table-responsive">
	<table class="table table bordered">
	<tr>
		<th scope="col">S/N</th>
                                    <th scope="col">Fullname</th>
									<th scope="col">Email</th>
                                    <th scope="col">Phune Number</th>
                                    <th scope="col">Address</th>
                                    <th scope="col">State</th>
                                    <th scope="col">Product Name</th>
                                    <th scope="col">Ordered Date</th>
                                    <th scope="col">Delivery Comment</th>
                                    <th class="text-edit">UPDATED AT</th>
	</tr>';
        while ($row1 = mysqli_fetch_array($result)) {
            $return .= '
		<tr>
		<td>' . $row1["id"] . '</td>
		<td>' . $row1["name"] . '</td>
		<td>' . $row1["email"] . '</td>
		<td>' . $row1["phone"] . '</td>
		<td>' . $row1["address"] . '</td>
		<td>' . $row1["state"] . '</td>
		<td>' . $row1["jamb_waec"] . '</td>
		<td>' . $row1["created_at"] . '</td>
		<td>' . $row1["delivery_status"] . '</td>
		<td>' . $row1["updated_at"] . '</td>
		
		
		</tr>';
        }
        echo $return;
    } else {
        echo 'No results containing all your search terms were found.';
    }
}
