<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
  <style type="text/css">
  body {
    background: #F5DEB3;
  }
  .modal{
  padding: 50px;
  position: fixed; top: 50%; left: 50%;
  -webkit-transform: translate(-50%, -50%);
  -ms-transform: translate(-50%, -50%);
  transform: translate(-50%, -50%);
}
  </style>
</head>
<body>
  <div class="modal">
<form alingn=center id='forma' action='script1.php' method='post'>
<h1>My defence</h1>
<p>Fill in the fields to log in to the site</p>
<p>login<br /><input type='text' name='login'></p>
<p>password<br /><input type='password' name='password'></p>
<p><input type='submit' name='submit' value='Log in'> <br></p></form>
</div>
</body>
</html>
