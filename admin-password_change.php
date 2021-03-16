<?php
    session_start();
    if(!isset($_SESSION["admin_password"])){
        header('Location: admin-login.php');
        exit;
    }

    $wrong_message1 ="";
    $wrong_message2 ="";

    require_once "db_connect.php";
    $pdo = db_connect();

    // パスワードが入力されたら合っているか確認
    if(isset($_POST["password"])){
        // 管理者パスワードがDBに存在する確認
        $sql = "SELECT * FROM m_passwords WHERE admin_password='".$_POST["password"]."'";
        $db = $pdo->prepare($sql);
        $db->execute();
        $row = $db->fetch(PDO::FETCH_ASSOC);

        // 新しいパスワードが正しい確認し，あっている場合更新
        if(!isset($row['admin_password'])){
            $wrong_message1 = "パスワードが間違っています";
        }else{
            if(mb_strlen($_POST['new_password']) < 4){
                $wrong_message2 = "4文字以上入力してください";
            }else if($_POST['new_password'] != $_POST['confirm_new_password']){
                $wrong_message2 = "新しいパスワードが一致しませんでした";
            }else{
                require "insert-password.php"; // パスワード更新ファイル
                session_regenerate_id(true); // セッションIDの再発行
                $_SESSION["admin_password"] = $_POST["new_password"]; // 管理者パスワードを格納してログイン
                
                // 遷移先URL,確認メッセージ
                $url = "../admin-mypage.php";
                $check_message = "管理者パスワードを変更しました<br>3秒後管理者ページに遷移します";
                header('Location: views/action-check.tpl.php?url='.$url.'&check_message='.$check_message);
                exit;
            }
        }
    }
    
    $title = "管理者パスワード変更ページ";
    $left_menu_btn = "admin-mypage.php";
    $left_menu_btn_text = "マイページ";
    $right_menu_btn = "logout.php";
    $right_menu_btn_text = "ログアウト";
    require_once "views/password-change.tpl.php";
