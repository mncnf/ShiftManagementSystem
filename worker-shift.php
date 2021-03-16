<?php
    require_once "db_connect.php";

    session_start();
    if(!isset($_SESSION["worker_id"])){
        header('Location: worker-login.php');
        exit;
    }
    $pdo = db_connect();
    
    // これまでにシフトを提出しているシフトを取得
    date_default_timezone_set('Asia/Tokyo');
    $today = date('Y/m/d H:m:s');
    $sql = "SELECT DISTINCT(post.deadline_id) FROM t_post_shift post, m_deadlines dead WHERE post.deadline_id = dead.deadline_id AND update_deadline < '".$today."' AND worker_id = '".$_SESSION['worker_id']."' ORDER BY deadline_id ASC";
    $db = $pdo->prepare($sql);
    $db->execute();
    foreach($db as $row){
        $deadline_id_list[] = $row['deadline_id'];
    }
    
    // シフトの最初の日付と終わりの日付を取得
    $select_count = 0;
    
    if(count($deadline_id_list) != 0){
        foreach($deadline_id_list as $value){
            $sql = "SELECT submit_deadline FROM m_deadlines WHERE deadline_id = '".$value."' ORDER BY submit_deadline ASC";
            $db = $pdo->prepare($sql);
            $db->execute();
            $row = $db->fetch(PDO::FETCH_ASSOC);

            $s_days[] = date("m/d", strtotime($row['submit_deadline']."+14 day"));
            $e_days[] = date("m/d", strtotime($row['submit_deadline']."+27 day"));
        }
    }

    if(!isset($_POST['select_day']) || $_POST['select_day'] == ""){
        // 一番最新の更新締切日を取得
        $sql = "SELECT MAX(deadline_id), MAX(submit_deadline) FROM m_deadlines WHERE update_deadline < '" . $today . "'";
        $db = $pdo->prepare($sql);
        $db->execute();
        foreach($db as $row){
            $deadline_id = $row['MAX(deadline_id)'];
            $submit_deadline = $row['MAX(submit_deadline)'];
        }
    }else{
        // POSTで締め切り日のIDを取得
        $sql = "SELECT * FROM m_deadlines WHERE deadline_id = '" .$_POST['select_day']. "'";
        $db = $pdo->prepare($sql);
        $db->execute();
        foreach($db as $row){
            $deadline_id = $row['deadline_id'];
            $submit_deadline = $row['submit_deadline'];
        }
    }

    // 曜日指定用
    $week = [
        '日', //0
        '月', //1
        '火', //2
        '水', //3
        '木', //4
        '金', //5
        '土', //6
    ];

    $day_counter = 0;

    // 締め切り日の15日から28日後までの日付を取得
    for($i=0; $i < 14; $i++){
        $count = 14 + $i;
        $tmp_day = date("m/d", strtotime($submit_deadline."+".$count." day"));
        $tmp_week = date("w", strtotime($submit_deadline."+".$count." day"));
        $days[] = $tmp_day."(". $week[$tmp_week].")";
    }

    // シフト取得
    $sql = "SELECT COUNT(*) FROM t_post_shift WHERE worker_id='".$_SESSION['worker_id']."' AND deadline_id ='".$deadline_id."' ORDER BY shift_day ASC";
    $db = $pdo->prepare($sql);
    $db->execute();
    $row = $db->fetch(PDO::FETCH_ASSOC);
    $shift_count = $row['COUNT(*)'];
    if($shift_count > 0){
        $sql = "SELECT * FROM t_post_shift WHERE worker_id='".$_SESSION['worker_id']."' AND deadline_id ='".$deadline_id."' ORDER BY shift_day ASC";
        $db_shift = $pdo->prepare($sql);
        $db_shift->execute();
    }else{
        $wrong_shift = "確認可能なシフトがありません";
    }
    
    $titile = "シフト確認";
    $left_menu_btn = "worker-mypage.php";
    $left_menu_btn_text = "マイページ";
    $right_menu_btn = "logout.php";
    $right_menu_btn_text = "ログアウト";
    require_once "views/worker-shift.tpl.php";