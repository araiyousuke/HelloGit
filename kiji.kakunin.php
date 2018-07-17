<?php
session_start();
require('dbconnect.php');
// ログイン状態チェック
if (isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()) {
	$_SESSION['time'] = time();
	$sql=sprintf('SELECT * FROM members WHERE id=%d',
	mysql_real_escape_string($_SESSION['id']));
	$record = mysql_query($sql) or die(mysql_query());
	$member = mysql_fetch_assoc($record);
}else{
	header('Location: login.php');
}

?>
<!doctype html>
<html>
<head>

<meta charset="UTF-8">
<title>EARTH</title>
</head>
<body>
 <!-- サイドメニュー -->

  <!-- メインコンテンツ -->
  <div class="wrapper">
    <header class="header">
      
		
   </header>

<?php

if (!isset($_SESSION['join'])){
	header('Location: kiji.php');
}


$pdo = new PDO('mysql:dbname=mini_bbs;host=localhost','root','1234');
$stmt = $pdo->query('SET NAMES utf8');
// TimeZoneを日本時間に設定する.phpiniにAsia/Tokyo追加
$org_timezone = date_default_timezone_get();
date_default_timezone_set('Asia/Tokyo');

$id = $member['id'];
$kategori = $_SESSION['join']['kategori'];
$taitol = $_SESSION['join']['taitol'];
$kiji = $_SESSION['join']['kiji'];
$created = date('Y-m-d H:i:s');

if (!empty($_POST)){
	//登録処理する
	$sql = $pdo->prepare("INSERT INTO posts SET member_id=:member_id, kategori=:kategori,taitol=:taitol,kiji=:kiji, created=:created ");
	$sql->bindValue(':member_id', $id);
	$sql->bindValue(':kategori', $kategori);
	$sql->bindValue(':taitol', $taitol);
	$sql->bindValue(':kiji', $kiji);
	$sql->bindValue(':created', $created);
	$flag = $sql->execute();
	
	header('Location: kiji.touroku.php');
}

?>

    <main class="contents">
      <section class="contents__inner">
	<form action="" method="post">
<input type="hidden" name="action" value="submit"/>
	<?php echo htmlspecialchars($_SESSION['join']['kategori'],ENT_QUOTES,'UTF-8'); ?>
	<?php echo htmlspecialchars($_SESSION['join']['taitol'],ENT_QUOTES,'UTF-8'); ?>
	<?php echo htmlspecialchars($_SESSION['join']['kiji'],ENT_QUOTES,'UTF-8'); ?>
		
	</section>
	
	<div><a href="kiji.php?action=rewrite">&laquo;&nbsp;書き直す</a>
	<input type="submit" value="登録する"/></div>
    </main>
    <footer class="footer">
      <p>フッター</p>
    </footer>

</body>
</html>