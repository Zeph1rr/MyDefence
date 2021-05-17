<?php
$data = $pdo->listTablesFull();
?>

<h3>Table list</h3>

<?php
if ($page->hasMessages()) {
	$page->printMessages();
    $page->redirect(BASE_URL, 1);
    return ;
}
?>

<table class="table table-pg">
<?php
foreach ($data as $v) {
    $table_name = $v['relname'];
    $rows = $v['reltuples'];
		if (($_SESSION['login']=='postgres' || $_SESSION['login'] == 'root') && $table_name == 'task') {
			 continue;
		}
?>
<tr>
    <td><a href="?page=tbl_data&table=<?=$table_name?>"><?=$table_name?></a></td>

</tr
<?php
}
if ($_SESSION['login'] == 'root' || $_SESSION['login'] == 'postgres') {
?>
<tr><td><a href="?page=tbl_data&table=administration.users">users</a></td></tr>
<?php } ?>
</table>
