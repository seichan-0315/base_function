<?php 
//ini_set('display_errors', 0);
session_start();
require('../dbconnect.php');

$db=DBconnect();
    function timer($_SESSION,$db){
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
}
//投稿を記録する
function ngword($_POST){
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
            return $message;
            }
      }
    
      // 禁止ワードフラグに1が入っていればループ飛ばし
    
      // 以下から正常処理
    
    
     }
//投稿を取得する
}

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