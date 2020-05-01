<?php
require('dbconnect.php');
    function post_check($post,$session,$files){
        //入力された値が正しかった場合　　　→　false
        //入力された値にエラーが有った場合　→　Array()　
        //返り値を与える
        if(!empty($post)){
            $db=DBconnect();
            
            $error_message['name']=name_check($post['name'],$db);
            
            $error_message['email']=email_check($post['email'],$db);
            
            $error_message['password']=password_check($post['password'],$db);
            
            $fileName=$files['image']['name'];
            $error_message['image']=image_check($session,$fileName);

        }
        if($error_message['name']!==false and $error_message['email']!==false and $error_message['password']!==false and $error_message['image']!==false){
            return $error_message;
        }else{
            return 0;
        }
    }
      /*  
      $post=[
          'name'=>"a",
          'email'=>"21",
          'password'=>" "
          ];
      print(post_check($post)['password']);
       */
        


        function image_upload($post,$session,$files){
            //画像のアップロード
            $image=date('YmdHis').$files['image']['name'];
            move_uploaded_file($files['image']['tmp_name'],'../member_picture/'.$image);
            $session['join']=$post;
            $session['join']['image']=$image;
            return $session;
            //header('Location: ./check.php');
            //exit();
        }
              
        
        
        function name_check($post,$db){
            if(empty($post)){
            //エラーの確認
            $error='blank';
            }else{
                if(strlen($post)>=255){
                    $error='length';
                }else{
                        
    		            $member = $db->prepare('SELECT COUNT(*) AS cnt FROM members WHERE name=?');
    		            $member->execute(array($post));
    		            $record = $member->fetch();
    		            if ($record['cnt'] > 0) {
    			            $error = 'duplicate';
    	               }else{
    	                   $error=false;
    	               }
                    }
                }
        
       
            if($error){
                if($error==='blank'){
                    $n_str='* ニックネームを入力してください';
                    return $n_str;
                }
                if($error==='length'){
                    $n_str='* ニックネームを255文字以下にしてください';
                    return $n_str;
                }
                if($error==='duplicate'){
                    $n_str='* 指定されたニックネームは既に登録されています';
                    return $n_str;
                    }
                }
                return false;
            }
            
        function email_check($post,$db){
            if(empty($post)){
               $error='blank'; 
            }else{
                if(strlen($post)>=255){
                    $error='length';
                }else{
                    if (empty($error)) {
                        $db=DBconnect();
    		            $member = $db->prepare('SELECT COUNT(*) AS cnt FROM members WHERE	email=?');
    		            $member->execute(array($post));
    		            $record = $member->fetch();
    		            if ($record['cnt'] > 0) {
    			            $error = 'duplicate';
    		            }else{
    	                   if(filter_var( $record,  FILTER_VALIDATE_EMAIL ) ){
                                $error='wrong format';
    			             }else{
    			                 $error=false;
    			             }
    		              }
    	               }
                    }
            }
            
            if($error){
                if($error==='blank'){
                    $e_str='* メールアドレスを入力してください';
                    return $e_str;
                }
                if($error==='length'){
                    $e_str='* メールアドレスを255文字以下にしてください';
                    return $e_str;
                }
                if($error==='duplicate'){
                    $e_str='* 指定されたメールアドレスは既に登録されています';
                    return $e_str;
                    }
                if($error==='wrong format'){
                    $e_str='* 正しい形式で入力してください';
                    return $e_str;
                    }
                }
                return false;
            }
            
        function password_check($post){
            if(empty($post)){
            $error='blank'; 
            }else{
                if(strlen($post)<4){
                    $error='length';
                }else{
                    if(preg_match('/[^a-zA-Z0-9]+$/',$post)) {
                        $error='hankaku';
                    }else{
                        $error=false;
                    }
                }
            }
            
            if($error){
                if($error==='blank'){
                    $p_str='* パスワードを入力してください';
                    return $p_str;
                }
                if($error==='length'){
                    $p_str='* パスワードを4文字以上で入力してください';
                    return $p_str;
                }
                if($error==='hankaku'){
                    $p_str='* 半角英字で入力してください';
                    return $p_str;
                    }
                }
                return false;
            }
            
            function image_check($session,$fileName){
                if(empty($fileName)){
                    $error='blank';
                }else{
                    $ext=substr($fileName,-3);
                    if($ext!='jpg' && $ext!='gif'){
                        $error='type';
                    }
                    $error=false;
                }
                
                if($error){
                    if($error=='blank'){
                        $error_message="* 写真などは「.gif」または「.jpg」の画像を指定してください";
                        return $error_message;
                    }
                    if($error=='type'){
                        $error_message="* 恐れ入りますが、画像を改めて指定してください";
                        return $error_message;
                    }
                }
                return false;
            }
?>
