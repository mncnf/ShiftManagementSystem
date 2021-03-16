<?php
    session_start();
    if(!isset($_SESSION["worker_id"])){
        header('Location: worker-login.php');
        exit;
    }

    // データベース接続
    require_once "db_connect.php";
    $pdo = db_connect();
    
    // 名前の取得し，誰のマイページかを出力
    $sql = "SELECT * FROM m_workers WHERE worker_id = '" . $_SESSION["worker_id"]. "'";
    $db = $pdo->prepare($sql);
    $db->execute();
    $row = $db->fetch(PDO::FETCH_ASSOC);
    $navi_text = $row["last_name"]." ".$row["first_name"]."のマイページ";

     // 現在の時刻を取得し，一番最新の締切日を取得
     date_default_timezone_set('Asia/Tokyo');
     $today = date('Y/m/d H:m:s');
     $sql = "SELECT MIN(deadline_id), MIN(submit_deadline) FROM m_deadlines WHERE submit_deadline >= '" . $today . "'";
     $db = $pdo->prepare($sql);
     $db->execute();
     foreach($db as $row){
         $deadline_id = $row['MIN(deadline_id)'];
     }
    
    //  初期シフトの登録
    require_once "insert-pre_shift.php";
    insert_pre_shift($deadline_id);

    $title = "従業員マイページ";
    $left_menu_btn = "worker-mypage.php";
    $left_menu_btn_text = "マイページ";
    $right_menu_btn = "logout.php";
    $right_menu_btn_text = "ログアウト";
    $left_btn = "worker-shift.php";
    $left_btn_text = "シフト確認";
    $right_btn = "worker-submit.php";
    $right_btn_text = "シフト提出";
    require_once "views/mypage.tpl.php";
