<?php 
    //ini_set('display_errors', 0);
    require('../function-2.php');
    session_start();


    if(!empty($_POST)){
        $warnings=post_check($_POST,$_SESSION,$_FILES);
        if($warnings===0){
            print(1);
            $image=date('YmdHis').$_FILES['image']['name'];
            move_uploaded_file($_FILES['image']['tmp_name'],'../member_picture/'.$image);
            $_SESSION['join']=$_POST;
            $_SESSION['join']['image']=$image;
            header('Location: ./check.php');
            exit();
        }
        //if(isset($warnings['join']) and isset($warnings['join']['name'])){
            /*$_SESSION['join']=$warnings['join'];
            $_SESSION['join']['image']=$warnings['join']['image'];
            print($_SESSION['join']['image']);
            print($_SESSION=date('YmdHis').$_FILES['image']['name']);*/
            
        //}
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
      
    <h1>会員登録</h1>
  </div>
  <div id="content">
    <p>次のフォームに必要事項をご記入ください。</p>
    <form action="index.php" method="post" enctype="multipart/form-data">
        <dl>
            <dt>ニックネーム<span class="required">必須</span></dt>
            <dd>
                <input type="text" name="name" size="35" maxlength="255" value="<?php if(!empty($_POST)){echo htmlspecialchars($_POST['name'], ENT_QUOTES);} ?>" />
                <p class="error"><?php if(!empty($warnings['name'])){print($warnings['name']);} ?></p>
            </dd>
            <dt>メールアドレス<span class="required">必須</span></dt>
            <dd>
                <input type="text" name="email" size="35" maxlength="255" value="<?php if(!empty($_POST)){echo htmlspecialchars($_POST['email'], ENT_QUOTES);} ?>"/>
                <p class="error"><?php if(!empty($warnings['email'])){print($warnings['email']);} ?></p>
            </dd>
            <dt>パスワード<span class="required">必須</span></dt>
            <dd>
                <input type="password" name="password" size="10" maxlength="20"  value="<?php if(!empty($_POST)){echo htmlspecialchars($_POST['password'], ENT_QUOTES);} ?>" />
                <p class="error"><?php if(!empty($warnings['password'])){print($warnings['password']);} ?></p>
            </dd>
            <dt>写真など</dt>
            <dd>
                <input type="file" name="image" size="35" />
                <p class="error"><?php if(!empty($warnings['image'])){print($warnings['image']);} ?></p>
            </dd>
        </dl>
        <div><input type="submit" value="入力内容を確認する" /></div>
    </form>
  </div>
</div>
</body>
</html>
