<?php
if (isset($_POST['usubmit'])){
  $login = trim(htmlspecialchars(stripslashes($_POST['log'])));
  $password = trim(htmlspecialchars(stripslashes($_POST['pass'])));
  $data = $pdo->getData("select position from workers where login = '".$login."';");
  $pos = $data[0]['position'];
  $p_n = $_POST['p_n'];
  $pdo->query("create user ".$login." with password '".$password."';");
  $pdo->query("grant ".$pos."s to ".$login.";");
  $pdo->query("insert into administration.users values((select max(user_id) from administration.users)+1, '".$login."', crypt('".$password."',gen_salt('md5')), '".$pos."', ".$p_n.");");
  echo '<META HTTP-EQUIV="Refresh" Content="0; URL=http://test/bd.php?page=tbl_data&table=administration.users">';
}
 ?>

 <form method='post'>
 <h1>Add user</h1>
  <p>Login<br /><input type='text' name='log'></p>
  <p>Password<br /><input type='password' name='pass'></p>
  <p>Worker pass number<br /><input type='text' name='p_n'></p>
  <p><input type='submit' name='usubmit' value='Add'> <br></p></form>
