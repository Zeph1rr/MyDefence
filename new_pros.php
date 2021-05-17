<?php
if (isset($_POST['psubmit'])){
  $pdo->query("insert into prospective values ((select Max(id) from prospective)+1, '".$_POST['ln']."', '".$_POST['ph_n']."', '".$_POST['email']."', '".$_POST['fax']."', '".$_POST['address']."');");
  echo '<META HTTP-EQUIV="Refresh" Content="0; URL=http://test/bd.php?page=tbl_data&table=prospective">';
}
 ?>

 <form method='post'>
 <h1>Add a client</h1>
  <p>lastname<br /><input type='text' name='ln'></p>
  <p>Phone_number<br /><input type='text' name='ph_n'></p>
  <p>E-mail<br /><input type='text' name='email'></p>
  <p>Fax<br /><input type='text' name='fax'></p>
  <p>address<br /><input type='text' name='address'></p>
  <p><input type='submit' name='psubmit' value='Add'> <br></p></form>
