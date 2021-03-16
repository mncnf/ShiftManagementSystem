<?php
    session_start();
    if(isset($_SESSION["admin_password"])){
        header('Location: admin-mypage.php');
        exit;
    }

    require_once "db_connect.php";
    $pdo = db_connect();
    
    // パスワードが入力されたら合っているか確認
    $wrong_message = "";
    if(isset($_POST["admin_password"])){
        $sql = "SELECT * FROM m_passwords";
        $db = $pdo->prepare($sql);
        $db->execute();
        $row = $db->fetch(PDO::FETCH_ASSOC);

        // パスワードがあっている場合ログイン
        if($row['admin_password'] == $_POST["admin_password"]){
            session_regenerate_id(true); // セッションIDの再発行
            $_SESSION["admin_password"] = $row["admin_password"]; // セッションで保持
            header('Location: admin-mypage.php');
            exit;
        }else{
            $wrong_message = "パスワードが間違っています";
        }
    }
    
    $title = "管理者ログインページ";
    require_once "views/admin-login.tpl.php";