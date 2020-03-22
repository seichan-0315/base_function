<?php 
    ini_set('display_errors', 0);
    require('../dbconnect.php');

    session_start();
    
    
    if(!empty($_POST)){
        if($_POST['name']==''){
            //エラーの確認
            $error['name']='blank';
        }else{
            if(strlen($_POST['name'])>=255){
                $error['name']='length';
            }else{
		            $member = $db->prepare('SELECT COUNT(*) AS cnt FROM members WHERE name=?');
		            $member->execute(array($_POST['name']));
		            $record = $member->fetch();
		            if ($record['cnt'] > 0) {
			            $error['name'] = 'duplicate';
	               }
                }
            }
    
       
        if($_POST['email']==''){
           $error['email']='blank'; 
        }else{
            if(strlen($_POST['email'])>=255){
                $error['email']='length';
            }else{
            if (empty($error)) {
		        $member = $db->prepare('SELECT COUNT(*) AS cnt FROM members WHERE	email=?');
		        $member->execute(array($_POST['email']));
		        $record = $member->fetch();
		        if ($record['cnt'] > 0) {
			        $error['email'] = 'duplicate';
		        }else{
	                   if(filter_var( $record,  FILTER_VALIDATE_EMAIL ) ){
                        $error['email']='wrong format';
			             }
		          }
	           }
            }
        }
        

        if($_POST['password']==''){
            $error['password']='blank'; 
        }else{
            if(strlen($_POST['password'])<=4){
                $error['password']='length';
            }else{
                if(preg_match('/[^a-zA-Z0-9]+$/',$POST['password'])) {
                    $error['password']='hankaku';
                }
            }
        }
        $fileName=$_FILES['image']['name'];
        if(!empty($fileName)){
            $ext=substr($fileName,-3);
            if($ext!='jpg' && $ext!='gif'){
                $error['image']='type';
            }
        }
        

	    
        if(empty($error)){
            //画像のアップロード
            $image=date('YmdHis').$_FILES['image']['name'];
            move_uploaded_file($_FILES['image']['tmp_name'],'../member_picture/'.$image);
            $_SESSION['join']=$_POST;
            $_SESSION['join']['image']=$image;
            header('Location: check.php');
            exit();
        }
        
    }
    
    if ($_REQUEST['action']=='rewrite'){
        $_POST=$_SESSION['join'];
        $error['rewrite']=true;
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
        <dd><input type="text" name="name" size="35" maxlength="255" value="<?php echo htmlspecialchars($_POST['name'], ENT_QUOTES); ?>" />
        <?php if ($error['name']=='blank'): ?>
        <p class="error">* ニックネームを入力してください</p>
        <?php endif; ?>
        <?php if ($error['name']=='length'): ?>
        <p class="error">* ニックネームを255文字以下にしてください</p>
        <?php endif; ?>
        <?php if($error['name']=='duplicate'): ?>
        <p class="error">* 指定されたニックネームは既に登録されています</p>
        <?php endif; ?>

        </dd>
        <dt>メールアドレス<span class="required">必須</span></dt>
        <dd><input type="text" name="email" size="35" maxlength="255" value="<?php echo htmlspecialchars($_POST['email'], ENT_QUOTES); ?>"/>
        <?php if ($error['email']=='blank'): ?>
        <p class="error">* メールアドレスを入力してください</p>
        <?php endif; ?>
        <?php if ($error['email']=='length'): ?>
        <p class="error">* メールアドレスを255文字以下にしてください</p>
        <?php endif; ?>
        <?php if($error['email']=='duplicate'): ?>
        <p class="error">* 指定されたメールアドレスは既に登録されています</p>
        <?php endif; ?>
        <?php if($error['email']=='wrong format'): ?>
        <p class="error">* 正しい形式で入力してください</p>
        <?php endif; ?>
        </dd>
        <dt>パスワード<span class="required">必須</span></dt>
        <dd>
            <input type="password" name="password" size="10" maxlength="20"  value="<?php echo htmlspecialchars($_POST['password'], ENT_QUOTES); ?>" />
        <?php if ($error['password']=='blank'): ?>
            <p class="error">* パスワードを入力してください</p>
        <?php endif; ?> 
        <?php if ($error['password']=='length'): ?>
            <p class="error">* パスワードを4文字以上で入力してください</p>
        <?php endif; ?>
        <?php if($error['password']=='hankaku'): ?>
            <p class="error">* 半角英字で入力してください</p>
        <?php endif; ?>
        </dd>
        <dt>写真など</dt>
            <dd><input type="file" name="image" size="35" />
            <?php if ($error['image']=='type'): ?>
            <p class="error">* 写真などは「.gif」または「.jpg」の画像を指定してください</p>
            <?php endif; ?>
            <?php if(!empty($error['image']=='type')): ?>
            <p class="error">* 恐れ入りますが、画像を改めて指定してください</p>
            <?php endif; ?>
        </dd>
    </dl>
    <div><input type="submit" value="入力内容を確認する" /></div>
</form>
  </div>
</div>
</body>
</html>
