<?php
    session_start();
    if(!isset($_SESSION["admin_password"])){
        header('Location: admin-login.php');
        exit;
    }
    require_once "db_connect.php";
    $pdo = db_connect();        
    
    // これまでにシフトを提出しているシフトを取得
    $sql = "SELECT DISTINCT(deadline_id) FROM t_post_shift ORDER BY deadline_id ASC";
    $db = $pdo->prepare($sql);
    $db->execute();
    foreach($db as $row){
        $deadline_id_list[] = $row['deadline_id'];
    }
    
    // シフトの最初の日付と終わりの日付を取得
    $select_count = 0;
    if(count($deadline_id_list) != 0){
        foreach($deadline_id_list as $value){
            $sql = "SELECT submit_deadline FROM m_deadlines WHERE deadline_id = '".$value."'";
            $db = $pdo->prepare($sql);
            $db->execute();
            $row = $db->fetch(PDO::FETCH_ASSOC);

            $s_days[] = date("m/d", strtotime($row['submit_deadline']."+14 day"));
            $e_days[] = date("m/d", strtotime($row['submit_deadline']."+27 day"));
        }
    }

    // 現在の時刻を取得し，最新のシフト提出締め切り日とシフト更新締め切り日を取得
    if(!isset($_POST['select_day']) || $_POST['select_day'] == ""){
        
        date_default_timezone_set('Asia/Tokyo');
        $today = date('Y/m/d H:m:s');
        $today = date("Y/m/d H:m:s", strtotime($today."+14 day"));
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

    // 締め切り日の15日から28日後までの日付を取得
    for($i=0; $i < 14; $i++){
        $count = 14 + $i;
        $tmp_day = date("m/d", strtotime($submit_deadline."+".$count." day"));
        $tmp_week = date("w", strtotime($submit_deadline."+".$count." day"));
        $days[] = $tmp_day."(". $week[$tmp_week].")";
    }
    
    // シフトを提出している人のみを取得
    $sql = "SELECT DISTINCT(t_post_shift.worker_id), first_name, last_name 
            FROM t_post_shift, m_workers 
            WHERE deadline_id = '".$deadline_id."' 
            AND t_post_shift.worker_id = m_workers.worker_id
            ORDER BY t_post_shift.shift_day ASC
            ";
    $db = $pdo->prepare($sql);
    $db->execute();
    foreach($db as $row){
        $worker_id[] = $row['worker_id'];
        $last_name[] = $row['last_name'];
        $first_name[] = $row['first_name'];
    }

    $title = "シフト確認ページ";
    $left_menu_btn = "admin-mypage.php";
    $left_menu_btn_text = "管理者ページ";
    $right_menu_btn = "logout.php";
    $right_menu_btn_text = "ログアウト";
    require_once "views/admin-shift.tpl.php"; 
