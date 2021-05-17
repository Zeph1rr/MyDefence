<?php
$worker = $_GET['worker'];
$report_period = '5 months';
$lastname = $pdo->getData('select lastname from workers where pass_num='.$worker)[0]['lastname'];
?>

<h3>
Report of <?=$lastname?>
&nbsp;&nbsp;
</h3>

<?php
if ($page->hasMessages()) {
	$page->printMessages();
}
?>

<?php
$sql = "SELECT lastname as lastname, (SELECT COUNT(*) FROM tasks WHERE executor_id =".$worker.") as amount,
	(SELECT COUNT(*) FROM tasks WHERE executor_id = ".$worker." AND status = 'Completed' AND (complete_date <= end_date OR end_date is null)) as done,
	(SELECT COUNT(*) FROM tasks WHERE executor_id = ".$worker." AND status = 'Completed' AND complete_date > end_date) as wrong,
	(SELECT COUNT(*) FROM tasks WHERE executor_id = ".$worker." AND status = 'Progressing' AND end_date < current_date) as urgently,
	(SELECT COUNT(*) FROM tasks WHERE executor_id = ".$worker." AND status = 'Progressing' AND (end_date > current_date OR end_date is null)) as deadline
	FROM tasks INNER JOIN workers ON pass_num = executor_id
	WHERE executor_id =".$worker;

$data = $pdo->getData($sql);


if (!$data) {
    echo 'No data!';
    return ;
}

$fields = array_keys($data[0]);
 ?>

 <table class="table table-pg">
 	<tr>
 	<?php
 	foreach ($fields as $field) {
 	    echo '<th><a href="">'.$field.'</a></th>';
 	}
 	?>
 	</tr>
 	    <tr>
 	    <?php
 	    foreach ($fields as $field) {
 	        echo '<td>'.$data[0][$field].'</td>';
 	    }
 	    ?>
 	    </tr>

 </table>

 <a href="?page=tbl_data&table=workers">Go back</a>
