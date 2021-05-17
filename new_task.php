<?php
if (isset($_POST['tsubmit'])){
  $pdo->query("call new_task('".$_POST['desc']."', '".$_POST['e_d']."', ".$_POST['executor'].", '".$_POST['mail']."', ".$_POST['priority'].");");
  echo '<META HTTP-EQUIV="Refresh" Content="0; URL=http://test/bd.php?page=tbl_data&table=tasks">';
}
 ?>

 <form method='post'>
 <h1>Create new task</h1>
  <p>Description<br /><input type='text' name='desc'></p>
  <p>End date<br /><input type='text' name='e_d'></p>
  <p>executor pass number<br /><input type='text' name='executor'></p>
  <p>Client's e-mail<br /><input type='text' name='mail'></p>
  <p>Priority<br /><input type='text' name='priority'></p>
  <p><input type='submit' name='tsubmit' value='Create'> <br></p></form>
