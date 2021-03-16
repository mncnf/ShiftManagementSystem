<?php
    require_once "db_connect.php";

    $title = "シフト提出ページ";
    $left_menu_btn = "worker-mypage.php";
    $left_menu_btn_text = "マイページ";
    $right_menu_btn = "logout.php";
    $right_menu_btn_text = "ログアウト";
    $action_url = "#";

    session_start();
    if(!isset($_SESSION["worker_id"])){
        header('Location: worker-login.php');
        exit;
    }
    $pdo = db_connect();
    
    // 現在の時刻を取得し，一番最新の締切日を取得
    date_default_timezone_set('Asia/Tokyo');
    $today = date('Y/m/d H:m:s');
    $sql = "SELECT MIN(deadline_id), MIN(submit_deadline) FROM m_deadlines WHERE submit_deadline >= '" . $today . "'";
    $db = $pdo->prepare($sql);
    $db->execute();
    foreach($db as $row){
        $deadline_id = $row['MIN(deadline_id)'];
        $submit_deadline = $row['MIN(submit_deadline)'];
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
    
    $sql = "SELECT * FROM t_pre_shift WHERE worker_id='".$_SESSION['worker_id']."' AND deadline_id ='".$deadline_id."' ORDER BY shift_day ASC";
    $db_shift = $pdo->prepare($sql);
    $db_shift->execute();
    
    // 入力不足箇所がないかチェック
    $submit_flag = false;
    if(isset($_POST['start_hours1'])){
        $submit_flag = true;
        for($i = 1; $i < 15; $i++){
            $tmp_sh = "start_hours".$i;
            $tmp_sm = "start_minites".$i;
            $tmp_eh = "end_hours".$i;
            $tmp_em = "end_minites".$i;
            
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

        // シフト提出
        if($submit_flag == true){
            require_once "insert-pre_shift.php";
            update_pre_shift($_POST['deadline_id']);
            
            $url = "../worker-mypage.php";
            $check_message = "シフトを提出しました<br>3秒後マイページに遷移します";
            header('Location: views/action-check.tpl.php?url='.$url.'&check_message='.$check_message);
            exit;
        }else{
            $wrong_shift = "入力不足箇所があります";
        }
    }

    require_once "views/worker-submit.tpl.php";