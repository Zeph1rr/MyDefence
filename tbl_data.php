<?php
$table = $_GET['table'];
?>

<h3>
View table <?=$table?>
&nbsp;&nbsp;
</h3>

<?php
if ($page->hasMessages()) {
	$page->printMessages();
}
?>


<?php

$limit = 10;
$start = $_GET['start'] ?: 0;
$countAll = $pdo->getData('SELECT COUNT(*) AS c FROM '.$table)[0]['c'];

$pageLinks = generatePagesLinks($limit, $start, $countAll, $floatLimit=10);

$primaryKeys = $pdo->primaryKeys($table, 1);

$datap = $pdo->getData("select position from workers where login = '".$_SESSION['login']."';");
$position = $datap[0]['position'];

// Находим order
$order = $_GET['order'];
if (!$order) {
    if (count($primaryKeys)) {
    	$order = $pks[0];
    }
}

// Составляем запрос
$sql = 'SELECT * FROM '.$table.'';
if ($order) {
	$sql .= ' ORDER BY "'.$order.'"';
}
if ($_GET['order-desc']) {
	$sql .= ' DESC';
}
$sql .= ' LIMIT '.$limit.' OFFSET '.$start;

// Извлекаем данные
$data = $pdo->getData($sql);


if (!$data) {
	if ($table == 'task'){
		echo 'You have no tasks';
		return ;
	}
	else {
    echo 'No data';
    return ;
	}
}

$fields = array_keys($data[0]);


?>

<?php

if ($table != 'workers') {

?>

<table class="table table-pg">
	<tr>
	<?php
	foreach ($fields as $field) {
	    $add = '';
	    if ($field == $_GET['order']) {
	        if ($_GET['order-desc']) {
	        	$add = '&order-desc=';
	        } else {
	        	$add = '&order-desc=1';
	        }
	    } else {
	        if ($_GET['order-desc']) {
	        	$add = '&order-desc=';
	        }
	    }
	    echo '<th><a href="'.url('order='.$field.$add).'">'.$field.'</a></th>';
	}
	?>
	</tr>
	<?php
	foreach ($data as $row) {
	    $where = [];
	    foreach ($primaryKeys as $pk) {
	    	$where []= '"'.$pk.'"=\''.$row[$pk].'\'';
	    }
	    $where = implode(' AND ', $where);
	    ?>
	    <tr>
	    <?php
	    foreach ($fields as $field) {
	        echo '<td>'.$row[$field].'</td>';
	    }
	    ?>
	    </tr>
	    <?php
	}
	?>

</table>

<?php
if ($table == 'task'){
	if (isset($_POST['idsubmit'])){
		$id = $_POST['idz'];
		$pdo->query('call complete('.$id.');');
		echo '<META HTTP-EQUIV="Refresh" Content="0; URL=http://test/bd.php?page=tbl_data&table=task">';
	}
	echo "<form method='post'>
	<h1>Complete the task</h1>
	<p>Task id<br /><input type='text' name='idz'></p>
	<p><input type='submit' name='idsubmit' value='Complete'> <br></p></form>";
}

// && $_SESSION['login'] != 'postgres' && $_SESSION['login'] != 'root'

if ($table == 'tasks' && $position == 'master') {
	echo '<a href=?page=new_task>Create new task</a>';
}

if ($table == 'administration.users') {
	echo '<a href=?page=new_user>Add new user</a>';
}

if ($table == 'prospective') {
	echo '<a href=?page=new_pros>Add a client</a>';
}
}

else {
	?>

	<table class="table table-pg">
	<tr>
		<?php if ($position != 'slave'){ ?>
	    <th>&nbsp;</th>
		<?php } ?>
	<?php
	foreach ($fields as $field) {
	    $add = '';
	    if ($field == $_GET['order']) {
	        if ($_GET['order-desc']) {
	        	$add = '&order-desc=';
	        } else {
	        	$add = '&order-desc=1';
	        }
	    } else {
	        if ($_GET['order-desc']) {
	        	$add = '&order-desc=';
	        }
	    }
	    echo '<th><a href="'.url('order='.$field.$add).'">'.$field.'</a></th>';
	}
	?>
	</tr>
	<?php
	foreach ($data as $row) {
	    $where = [];
	    foreach ($primaryKeys as $pk) {
	    	$where []= '"'.$pk.'"=\''.$row[$pk].'\'';
	    }
	    $where = implode(' AND ', $where);
	    ?>
	    <tr>
				<?php if ($position != 'slave'){ ?>
	        <td>

	            <a title="Report" href="?page=report&worker=<?=$row['pass_num']?>"><i class="glyphicon glyphicon-edit"></i></a>
	            &nbsp;
	        </td>
			<?php } ?>
	    <?php
	    foreach ($fields as $field) {
	        echo '<td>'.$row[$field].'</td>';
	    }
	    ?>
	    </tr>
	    <?php
	}
	?>
	</table>
<?php
}
echo $pageLinks;
?>
