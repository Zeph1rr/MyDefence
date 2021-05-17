<?php session_start();

define('APP_NAME', 'PG Admin');

define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'Moya_oborona');

include_once 'Pg_Pdo.php';
include_once 'Page.php';


$page = new Page;

global $pdo;
$pdo = new PG_PDO;
$pdo->connect(DB_HOST, $_SESSION['login'], $_SESSION['password'], DB_NAME);

if (!$pdo->connect) {
    echo $pdo->error;
	exit;
}



$datap = $pdo->getData("select position from workers where login = '".$_SESSION['login']."';");
$posit = $datap[0]['position'];

function generatePagesLinks($limit, $start, $countAll, $floatLimit=50)
{
    $pageLinks = '';
    $pageCount = ceil($countAll / $limit);
    if ($pageCount == 1) {
        return '';
    }
    $j = 0;
    if ($start > $floatLimit) {
        $pageLinks .= '<li><a href="'.url('start=0').'">1...</a></li> ';
    }
    for ($i = max(1, $start - $floatLimit); $i <= $pageCount; $i ++) {
        if ($j > $floatLimit * 2) {
            break;
        }
        $st = '';
        if ($i - 1 == $start) {
            $st = ' style="font-weight:bold; color:#FF0000; background-color:green; color:white "';
        }
        $pageLinks .= '<li><a'.$st.' href="'.url('start='.($i-1)).'">'.$i.'</a></li> ';
        $j ++;
    }
    if ($pageCount > $floatLimit * 2) {
        $pageLinks .= '<li><a href="'.url('start='.($pageCount-1)).'"><span aria-hidden="true">&raquo;</span></a></li> ';
    }
    $pageLinks = '
        <nav aria-label="Page navigation">
          <ul class="pagination">
            '.$pageLinks.'
          </ul>
        </nav>
    ';
    return $pageLinks;
}

function url($add='', $query='')
{
    $httpHost = 'http://'.$_SERVER['HTTP_HOST'];
    $path     = $_SERVER['SCRIPT_NAME'];
    $query    = $query == '' ? $_SERVER['QUERY_STRING'] : $query;
    if ($query == '') {
        return $path.'?'.$add;
    }
    parse_str($query, $currentAssoc);
    parse_str($add, $addAssoc);
    if (is_array($addAssoc)) {
        foreach ($addAssoc as $k => $v) {
            $currentAssoc [$k]= $v;
        }
    }
    $a = array();
    foreach ($currentAssoc as $k => $v) {
        if ($v == '') {
            continue;
        }
        $a []= $v == '' ? $k : "$k=$v";
    }
    return $path.'?'.implode('&', $a);
}



if ($_GET['action'] == 'deleteTable') {
	$result = $pdo->dropTable($_GET['table']);
    if ($result) {
    	$page->message('Успешно удалена таблица '.$_GET['table']);
    } else {
        $page->error('Ошибка удаления таблицы', $pdo->error);
    }
}


if ($_GET['action'] == 'truncateTable') {
	$result = $pdo->truncateTable($_GET['table']);
    if ($result) {
    	$page->message('Успешно очищена таблица '.$_GET['table']);
    } else {
        $page->error('Ошибка очистки таблицы', $pdo->error);
    }
}

if ($_GET['action'] == 'copyTable') {
	$result = $pdo->copyTable($_GET['table'], $_GET['name']);
    if ($result) {
    	$page->message('Таблица '.$_GET['table'].' успешно скопирована в '.$_GET['name'].'');
    } else {
        $page->error('Ошибка копирования таблицы', $pdo->error);
    }
}

if ($_GET['action'] == 'dropColumn') {
	$result = $pdo->dropColumn($_GET['table'], $_GET['column']);
    if ($result) {
    	$page->message('Поле успешно удалено');
    } else {
        $page->error('Ошибка удаления поля', $pdo->error);
    }
}

if ($_POST['action'] == 'sql-query') {
	$result = $pdo->query($_POST['sql']);
    if ($result) {
    	$page->message('Запрос успешно выполнен');
    } else {
        $page->error('Ошибка выполнения запроса', $pdo->error);
    }
}

if ($_GET['action'] == 'deleteRow') {
	$result = $pdo->deleteRow($_GET['table'], $_GET['where']);
    if ($result) {
    	$page->message('Строка удалена');
    } else {
        $page->error('Ошибка удаления строки', $pdo->error);
    }
}

?>



<!DOCTYPE html>
<html>
<head>
    <title>My defence</title>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

    <!-- Bootstrap -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

    <style type="text/css">
    .table-pg {width:100%}
    .tbl-menu {list-style:none; padding: 0; font-size: 11px;}
    .pimg {padding: 4px 2px 2px 0px;}
    h3 {margin: 0 0 15px; font-size: 20px;}
    </style>
</head><body>

  <nav class="navbar navbar-default">
    <div class="container-fluid">
      <!-- Brand and toggle get grouped for better mobile display -->
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <p class="pimg"><img title='ООО "Моя оборона"' src='https://storage.vsemayki.ru/images/0/1/1918/1918533/previews/people_1_sign_front_white_500.jpg' height="32px" width="32px"> &nbsp;</p>

      </div>

      <!-- Collect the nav links, forms, and other content for toggling -->
      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav">

          <li><a class="navbar-brand" href="bd.php">Main page</a></li>
          <li><a href="">Hello, <?=$_SESSION['login']?></a></li>
          <?php if ($_SESSION['login'] != 'postgres' && $_SESSION['login'] != 'root'){
          echo '<li><a href="">You are a '.$posit.'</a></li>'; }
        else {
          echo '<li><a href="">You are the administrator</a></li>'; } ?>
          <li><a href="index.php">Log out</a></li>
        </ul>
      </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
  </nav>

<div class="container-fluid">

    <div class="row">
        <div class="col-sm-10 ">
            <?php
            if ($_GET['page']) {
                $p = $_GET['page'];
            	include_once $p.'.php';
            } else {
                include_once 'tbl_list.php';
            }
            ?>
        </div>
    </div>




</div>

</body></html>
