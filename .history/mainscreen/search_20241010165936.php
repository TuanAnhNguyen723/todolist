<?php
include '../config.php';

$title = $_POST['title'];

$sql = "SELECT * FROM task WHERE title LIKE '$title%'";
$query = mysqli_query($conn,$sql);
$data = '';

while($row = mysqli_fetch_assoc($query))
{

    $data = "<tr>
    <td>
        ".$row('title')." 
    </td>

    <td>
        ".$row('task_id')."
    </td>

    </tr>";
}
echo $data;

?>