<?php
    session_start();
    if(isset($_SESSION["worker_id"])){
        header('Location: worker-mypage.php');
        exit;
    }
    $wrong_message = "";
    
    // データベース接続
    require_once "db_connect.php";
    $pdo = db_connect();

    // IDかパスワードが入力されたら処理を行う
    if(isset($_POST["login_id"]) || isset($_POST["password"])){

        // IDを指定してデータベースに問い合わせ
        $sql = "SELECT * FROM m_workers WHERE login_id = '" . $_POST["login_id"]. "'";
        $db = $pdo->prepare($sql);
        $db->execute();
        $row = $db->fetch(PDO::FETCH_ASSOC);

        // IDが登録されていない場合は指摘
        // true:新しくセッション登録,false:指摘
        // 登録されている場合はパスワードがあっているか確認
        if(!isset($row["login_id"])){
            $wrong_message = "ID又はパスワードが間違っています";
        }else{
            if($row["password"] == $_POST["password"]){
                session_regenerate_id(true); // セッションIDの再発行
                $_SESSION["worker_id"] = $row["worker_id"]; // 従業員識別IDを格納してログイン
                header('Location: worker-mypage.php');
                exit;
            }else{
                $wrong_message = "ID又はパスワードが間違っています";
            }
        }
    }

    $title = "従業員ログインページ";
    require_once "views/worker-login.tpl.php";