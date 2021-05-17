
<?php

// ALTER TABLE analyzes DROP COLUMN "state"

?>
<h3>SQL запрос</h3>

<?php
if ($page->hasMessages()) {
	$page->printMessages();
    //$page->redirect(BASE_URL, 1);
    //return ;
}
?>


<form method="post">
  <input type="hidden" name="action" value="sql-query">
  <div class="form-group">
    <textarea class="form-control" placeholder="SQL запрос" style="height:200px;" name="sql"><?=htmlspecialchars($_POST['sql'])?></textarea>
  </div>
  <!-- <div class="checkbox">
    <label>
      <input type="checkbox"> Check me out
    </label>
  </div> -->
  <button type="submit" class="btn btn-success">Выполнить</button>
</form>