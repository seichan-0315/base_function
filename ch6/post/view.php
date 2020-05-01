<?php 
 ini_set('display_errors', 0);
require('../dbconnect.php');

session_start();

$db=DBconnect();

if(empty($_REQUEST['id'])){
   header('Location: index.php'); exit();
}

    $posts=$db->prepare('select m.name,m.picture,p.* from members m,posts p where m.id=p.membera_di and p.id=? order by p.created desc');
    $posts->execute(array($_REQUEST['id']));
?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>ひとこと掲示板</title>

	<link rel="stylesheet" href="../style.css" />
</head>

<body>
<div id="wrap">
  <div id="head">
    <h1>ログインする</h1>
  </div>
  <div id="content">
        <p>&laquo;<a href="index.php">一覧に戻る</a></p>
    <?php if($post=$post->fetch()): ?>
    <div class="msg">
    <img src="member_picture/<?php echo htmlspecialchars($_POST['picture'], ENT_QUOTES); ?>" width="48" height="48" alt="<?php echo htmlspecialchars($_POST['name'], ENT_QUOTES); ?>" />
    <p><?php echo htmlspecialchars($_POST['message'], ENT_QUOTES); ?><span class="name">(<?php echo htmlspecialchars($_POST['name'], ENT_QUOTES); ?>)</span></
    <p class="day"><?php echo htmlspecialchars($_POST['created'], ENT_QUOTES); ?></p>
      </div>
    <?php elde: ?>
    <p>その投稿は削除されたか、URLが間違えています</p>
    <?php endif; ?>
  </div>

</div>
</body>
</html>
