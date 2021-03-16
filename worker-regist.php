<?php
    require_once "db_connect.php";

    $title = "従業員登録ページ";
    $wrong_last_name = "";
    $wrong_first_name = "";
    $wrong_login_id = "";
    $wrong_confirm_id = "";
    $wrong_password = "";
    $wrong_confirm_password = "";
    $action_url = "#";

    // 最初何もセットされていない時false
    if(!isset($_POST['last_name'])){
        $login_flag = false;
    }else{
        $login_flag = true;
    }
    
    // 名前が入力されているか確認
    if(isset($_POST['last_name'])){
        if($_POST['last_name'] == ""){
            $wrong_last_name = "名前(姓)を入力してください";
            $login_flag = false;
        }
    }
    
    // 名前が入力されているか確認
    if(isset($_POST['first_name'])){
        if($_POST['first_name'] == ""){
            $wrong_first_name = "名前(名)を入力してください";
            $login_flag = false;
        }
    }
    
    // ログインIDが入力されているか確認
    if(isset($_POST['login_id'])){
        if($_POST['login_id'] == ""){
            $wrong_login_id = "ログインIDを入力してください";
            $login_flag = false;
        }else{
            // ログインIDが登録されているか確認
            $pdo = db_connect();
            
            $sql = "SELECT count(login_id) FROM m_workers WHERE login_id = '".$_POST['login_id']."'";
            $db = $pdo->prepare($sql);
            $db->execute();
            $row = $db->fetch(PDO::FETCH_ASSOC);
            
            // ログインIDが登録されている場合登録できないようにする
            if($row['count(login_id)']){
                $login_flag = false;
                $wrong_login_id = "他のログインIDを入力してください";
            }
        }
    }
    
    // 確認用ログインIDが入力されているか確認
    if(isset($_POST['confirm_id'])){
        if($_POST['confirm_id'] == ""){
            $wrong_confirm_id = "確認用のログインIDを入力してください";
            $login_flag = false;
        }else if($_POST['login_id'] != $_POST['confirm_id']){
            $wrong_confirm_id = "ログインIDが一致しませんでした";
            $login_flag = false;
        }
    }
    
    // パスワードが入力されている確認
    if(isset($_POST['password'])){
        if($_POST['password'] == ""){
            $wrong_password = "パスワードを入力してください";
            $login_flag = false;
        }
    }
    
    // 確認用パスワードが入力されているか確認
    if(isset($_POST['confirm_password'])){
        if($_POST['confirm_password'] == "" ){
            $wrong_confirm_password = "確認用のパスワードを入力してください";
            $login_flag = false;
        }else if($_POST['password'] != $_POST['confirm_password']){
            $wrong_confirm_password = "パスワードが一致しませんでした";
            $login_flag = false;
        }
    }

    // アカウント登録
    if($login_flag == true){
        require_once "insert-worker.php"; // アカウント登録ファイル読み込み

        // 遷移先URL,確認メッセージ
        $url = "../worker-login.php";
        $check_message = "アカウントを作成しました<br>3秒後ログインページに遷移します";
        header('Location: views/action-check.tpl.php?url='.$url.'&check_message='.$check_message);
        exit;
    }

    require_once "views/worker-regist.tpl.php";
