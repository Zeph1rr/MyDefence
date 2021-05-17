<?php session_start();//вся процедура сверки логина и паролей работает на сессиях. Именно в них хранятся данные  пользователя, пока он находится на сайте. Запускать сессию нужно в начале странички

include_once 'Pg_Pdo.php';
$pdo = new PG_PDO;
$pdo->connect('localhost', 'postgres', '', 'Moya_oborona');

global $login;
global $password;




if (isset($_POST['login'])) { $login = $_POST['login']; if ($login == '') { unset($login);} } //заносим введенный пользователем логин в переменную $login, если он пустой, то уничтожаем переменную

if (isset($_POST['password'])) { $password=$_POST['password']; if ($password =='') { unset($password);} }//заносим введенный пользователем пароль в переменную $password, если он пустой, то уничтожаем переменную

if (empty($login) or empty($password)) //если пользователь не ввел логин или пароль, то выдаём ошибку и останавливаем выполнение скрипта
   {
   exit ("You did not enter all the information, go back and fill in all the fields!");
   }

$login = stripslashes($login);//удаляет экранирование символов, произведенное функцией addslashes()

$login = htmlspecialchars($login);//преобразует специальные символы в HTML-сущности (обрабатываем их, чтобы теги и скрипты не работали на случай от действий умников-спамеров)

$password = stripslashes($password); //удаляет экранирование символов, произведенное функцией addslashes()

$password = htmlspecialchars($password);

$login = trim($login);//удаляет пробелы (или другие символы) из начала и конца строки
$password = trim($password);

$data = $pdo->getData("SELECT (pass = crypt('".$password."', pass)) AS pass FROM administration.users where login = '".$login."'" );
$boolpas = $posit = $data[0]['pass'];

if ($boolpas) {
$_SESSION['login'] = $login;
$_SESSION['password'] = $password;

header('Refresh: 2; URL=http://test/bd.php'); //redirect с задержкой
echo 'You will be redirected to the main page in 2 seconds.';
}
else {
  header('Refresh: 2; URL=http://test/index.php');
  echo 'Wrong pass. You will be redirected back in 2 seconds.';
} //вывод сообщения
?>
