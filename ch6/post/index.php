<?php 
//ini_set('display_errors', 0);
session_start();
require('../dbconnect.php');

$db=DBconnect();

if(isset($_SESSION['id']) && $_SESSION['time']+3600 > time()){
    //ログインしている
    $_SESSION['time']=time();
    
    $members=$db->prepare('select * from members where id=?');
    $members->execute(array($_SESSION['id']));
    $member=$members->fetch();
}else{
    //ログインしてない
    header('Location: login.php'); exit();
}

//投稿を記録する

 // 禁止ワードチェックフラグを0にセット
$ngflag = 0;
if(!empty($_POST)){
// 禁止ワード設定ファイルをパース
$code = parse_ini_file('../ngword.ini', true);
$ng_words = $code['NG_WORDS'];
$ok_words = $code['OK_WORDS'];



  

  // タイトル変数を一旦$title_tempへ
  $title_temp = $_POST['message'];

  // 文字列を一旦小文字にする
  $title_temp = mb_strtolower($title_temp, 'utf-8');

  // 文字列内の半角カナ、濁点付きの文字、全角英数字、全角スペースを変換
  $title_temp = mb_convert_kana($title_temp,'asVK','utf-8');

  // 空白スペースや、。を一旦削除
  $title_temp = preg_replace('/\s|、|。/', '', $title_temp);

  // 禁止キーワードを包括してしまう許可キーワードを一旦 * に変換
  foreach ($ok_words as $okWordsVal) {
    if (strpos($title_temp, $okWordsVal) !== false) {
      $title_temp = str_replace($okWordsVal, '*', $title_temp);
    }
  }
 
  // 禁止ワードチェック
 foreach ($ng_words as $ngWordsVal) {
    if (strpos($title_temp, $ngWordsVal) !== false) {
      // 禁止ワードが見つかった！
      $ngflag = 1; // フラグに1を入れる
      break; // 処理の停止
    }
    
    if($_POST['message']!=''){

        $message=$db->prepare('insert into posts set member_id=?,message=?,created=NOW()');
        $message->execute(array(
            $member['id'],
            $_POST['message']
            ));
          //  header('Location: 'index.php'); exit();

        }
  }

  // 禁止ワードフラグに1が入っていればループ飛ばし

  // 以下から正常処理


 }
//投稿を取得する


//$posts=$db->prepare('select * from posts ;');//ここもなぜかしたのprepareだと一つしか表示されない


 

//返信の場合


//htmlspecialcharsのショートカット
function h($value){
    return htmlspecialchars($value,ENT_QUOTES);
}

//function makeLink($value){
  //  return mb_ereg_replace("(https?)(://[[:alum:]\+\$\;\?\.%.!#~*/:@&=_-]+)",'<a href="\1\2">\1\2</a>',$value);
//}
$posts=$db->query('select m.name ,m.picture,p.* from members m ,posts p where m.id=p.member_id order by p.created desc limit 1,5');

while($post=$posts->fetch()){
     if (
    isset($post['message']) && // $_GET['my_name'] が定義されており、
    is_string($post['message']) && // 且つ文字列であり、
    $post['message'] !== '' // 且つ空欄でないかどうか調べる
) {
    // 条件をすべて満たせばそのまま$my_nameに代入
    $message= $post['message'];
} else {
    // 1つでも満たさないものがあれば「名無し」として設定
    $message = 'blank';
}
 
         }
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
    <h1>メッセージをどうぞ</h1>
  </div>
  <div id="content">
      
    <form action="" method="post">
        <dl>
            <dt><?php echo h($member['name']); ?>さん、メッセージをどうぞ</dt>
            <dd>
                <textarea  name="message" cols="50" rows="5"><?php h($message); ?></textarea>
            </dd>
        </dl>
        <div>
            <input type="submit" value="投稿する" />
        </div>
    </form>
     <?php if($ngflag==1):?>
    <p class="error"><?php print("※ngワードが含まれています");  ?>
 
<?php endif; ?>
<br><br>
<p><a href="./logout.php">logout</p>
  </div>



  <!-- 54行目のforeachはちゃんと出るのにここだと表示されないというかformの後に置くと出てこない-->
  <?php 
  $posts=$db->query('select m.name ,m.picture,p.* from members m ,posts p where m.id=p.member_id order by p.created desc');
   //$posts->fetch();
 foreach ($posts as $post):
//while($post=$posts->fetch()):
?>
<div class="msg">
    <img src="member_picture/<?php echo h($post['picture']); ?>" width="48" height="48" alt="<?php echo h($post['name']); ?>" />
    <p><?php echo h($post['message']); ?><span class="name">(<?php echo h($post['name']); ?>)</span></p>
    <p class="day"><a href="view.php?id=<?php echo h($post['id']); ?>"><?php echo h($post['created']); ?></a>
<?php if($_SESSION['id']==$post['member_id']): ?>
[<a href="delete.php?id=<?php echo h($post['id']); ?>" style="color:#F33;">削除</a>]
<?php endif; ?>
</p>
</div>
<?php 
//endwhile;
endforeach;
?>

</div>
</body>
</html>
