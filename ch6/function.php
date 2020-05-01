<?php
require('dbconnect.php');
    function post_check($post){
        //入力された値が正しかった場合　　　→　TRUE
        //入力された値にエラーが有った場合　→　Array()　
        //返り値を与える 
        
      if(!empty($post)){
        if($_POST['name']==''){
            //エラーの確認
            $error['name']='blank';
        }else{
            if(strlen($post['name'])>=255){
                $error['name']='length';
            }/*else{
		            $member = $db->prepare('SELECT COUNT(*) AS cnt FROM members WHERE name=?');
		            $member->execute(array($post['name']));
		            $record = $member->fetch();
		            if ($record['cnt'] > 0) {
			            $error['name'] = 'duplicate';
	               }
                }*/
            }
        $error['name']=true;
       
        if($post['email']==''){
           $error['email']='blank'; 
        }else{
            if(strlen($post['email'])>=255){
                $error['email']='length';
            }/*else{
                if (empty($error)) {
		            $member = $db->prepare('SELECT COUNT(*) AS cnt FROM members WHERE	email=?');
		            $member->execute(array($post['email']));
		            $record = $member->fetch();
		            if ($record['cnt'] > 0) {
			            $error['email'] = 'duplicate';
		            }else{
	                   if(filter_var( $record,  FILTER_VALIDATE_EMAIL ) ){
                            $error['email']='wrong format';
			             }
		              }
	               }
                }*/
            }
            $error['email']=true;


        if($post['password']==''){
            $error['password']='blank'; 
        }else{
            if(strlen($post['password'])<=4){
                $error['password']='length';
            }else{
                if(preg_match('/[^a-zA-Z0-9]+$/',$post['password'])) {
                    $error['password']='hankaku';
                }
            }
        }
        $error['password']=true;

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
            $_SESSION['join']=$post;
            $_SESSION['join']['image']=$image;
            header('Location: ./join/check.php');
            exit();
                }
                return $error;
              }
            }
        
        function name_check($error_n){
            if($error_n!==true){
                if($error_n==='blank'){
                    $n_str='* ニックネームを入力してください';
                    return $n_str;
                }
                if($error_n==='length'){
                    $n_str='* ニックネームを255文字以下にしてください';
                    return $n_str;
                }
                if($error_n==='duplicate'){
                    $n_str='* 指定されたニックネームは既に登録されています';
                    return $n_str;
                    }
                }
            }
            
        function email_check($error_e){
            if($error_e!==true){
                if($error_e==='blank'){
                    $n_str='* メールアドレスを入力してください';
                    return $e_str;
                }
                if($error_e==='length'){
                    $e_str='* メールアドレスを255文字以下にしてください';
                    return $e_str;
                }
                if($error_e==='duplicate'){
                    $e_str='* 指定されたメールアドレスは既に登録されています';
                    return $e_str;
                    }
                if($error_e==='wrong format'){
                    $e_str='* 正しい形式で入力してください';
                    return $e_str;
                    }
                }
            }
            
        function password_check($error_p){
            if($error_p!==true){
                if($error_n==='blank'){
                    $p_str='* パスワードを入力してください';
                    return $p_str;
                }
                if($error_p==='length'){
                    $p_str='* パスワードを4文字以上で入力してください';
                    return $p_str;
                }
                if($error_p==='hankaku'){
                    $p_str='* 半角英字で入力してください';
                    return $p_str;
                    }
                }
            }
    
?>
