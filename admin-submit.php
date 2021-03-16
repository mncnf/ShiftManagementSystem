<?php
    session_start();
    if(!isset($_SESSION["admin_password"])){
        header('Location: admin-login.php');
        exit;
    }
    require_once "db_connect.php";

    $title = "シフト編集";
    $left_menu_btn = "admin-mypage.php";
    $left_menu_btn_text = "管理者ページ";
    $right_menu_btn = "logout.php";
    $right_menu_btn_text = "ログアウト";
    $action_url = "#";

    $pdo = db_connect();
    
    // 現在の時刻を取得し，最新のシフト提出締め切り日とシフト更新締め切り日を取得
    date_default_timezone_set('Asia/Tokyo');
    $today = date('Y/m/d H:m:s');
    $sql = "SELECT MAX(deadline_id), MAX(submit_deadline), MAX(update_deadline) FROM m_deadlines WHERE submit_deadline < '" . $today . "'";
    $db = $pdo->prepare($sql);
    $db->execute();
    foreach($db as $row){
        $deadline_id = $row['MAX(deadline_id)'];
        $submit_deadline = $row['MAX(submit_deadline)'];
        $update_deadline = $row['MAX(update_deadline)'];
    }

    // 更新可能か判定
    if(strtotime($submit_deadline) < strtotime($today) && strtotime($today) <= strtotime($update_deadline)){
        $submit_btn_flag = true;
    }else{
        $submit_btn_flag = false;
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

    // シフトを提出している人を取得
    $sql = "SELECT DISTINCT(t_pre_shift.worker_id), first_name, last_name 
            FROM t_pre_shift, m_workers 
            WHERE deadline_id = '".$deadline_id."' 
            AND t_pre_shift.worker_id = m_workers.worker_id";
    $db = $pdo->prepare($sql);
    $db->execute();
    foreach($db as $row){
        $worker_id[] = $row['worker_id'];
        $last_name[] = $row['last_name'];
        $first_name[] = $row['first_name'];
    }

    // 不足箇所がないか確認
    if(isset($_POST['worker_id'])){
        $submit_flag = true;
        foreach($_POST['worker_id'] as $id){
            // 入力不足箇所がないかチェック
            for($i = 1; $i < 15; $i++){
                $tmp_sh = $id."start_hours".$i;
                $tmp_sm = $id."start_minites".$i;
                $tmp_eh = $id."end_hours".$i;
                $tmp_em = $id."end_minites".$i;
                
                // キーを指定して追加
                $sh_array[$tmp_sh] = "";
                $sm_array[$tmp_sm] = "";
                $eh_array[$tmp_eh] = "";
                $em_array[$tmp_em] = "";
    
                // 初期値以外の場合，該当箇所のセルを赤枠で囲む
                if($_POST[$tmp_sh] != "99" || $_POST[$tmp_sm] != "99" || $_POST[$tmp_eh] != "99" || $_POST[$tmp_em] != "99"){
                    if($_POST[$tmp_sh] == "99"){
                        $sh_array[$tmp_sh] = "border-red";
                        $submit_flag = false;
                    }
    
                    if($_POST[$tmp_sm] == "99"){
                        $sm_array[$tmp_sm] = "border-red";
                        $submit_flag = false;
                    }
    
                    if($_POST[$tmp_eh] == "99"){
                        $eh_array[$tmp_eh] = "border-red";
                        $submit_flag = false;
                    }
    
                    if($_POST[$tmp_em] == "99"){
                        $em_array[$tmp_em] = "border-red";
                        $submit_flag = false;
                    }
                }
            }
        }    
    
        // シフト提出
        if($submit_flag == true){
            require_once "insert-post_shift.php";
            update_post_shift($_POST['worker_id'], $_POST['deadline_id']);
            
            $url = "../admin-mypage.php";
            $check_message = "シフトを提出しました<br>3秒後管理者ページに遷移します";
            header('Location: views/action-check.tpl.php?url='.$url.'&check_message='.$check_message);
            exit;
        }else{
            $wrong_shift = "入力不足箇所があります";
        }
    }

    require_once "views/admin-submit.tpl.php";