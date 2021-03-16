<?php
    require_once "db_connect.php";

    session_start();
    if(!isset($_SESSION["worker_id"])){
        header('Location: worker-login.php');
        exit;
    }

    // 一度もシフトを提出していない場合初期値を入力
    function insert_pre_shift(int $deadline_id){
        // データベース接続
        $pdo = db_connect();
        
        // シフトを提出しているか確認
        $sql = "SELECT COUNT(*) FROM t_pre_shift WHERE worker_id='".$_SESSION['worker_id']."' AND deadline_id ='".$deadline_id."'";
        $db = $pdo->prepare($sql);
        $db->execute();
        $row = $db->fetch(PDO::FETCH_ASSOC);
        $shift_count = $row['COUNT(*)'];
        
        // 15日後から28日後の日付を取得
        $shift_days = get_shift_days($deadline_id);
        
        // すでにシフトが提出されている場合は何もしない
        if($shift_count == 0){
            insert_pre_shift_sql($shift_days, $deadline_id);
        }
    }

    // シフトの更新
    function update_pre_shift(int $deadline_id){
        // データベース接続
        $pdo = db_connect();

        // 更新用に条件に合うシフトを削除してからシフトを追加する
        $sql = "DELETE FROM t_pre_shift WHERE worker_id='".$_SESSION['worker_id']."' AND deadline_id='".$deadline_id."'";
        $db = $pdo->prepare($sql);
        $db->execute();

        $shift_days = get_shift_days($deadline_id);
        insert_pre_shift_sql($shift_days, $deadline_id);
    }

    // 日付の取得
    function get_shift_days(int $deadline_id){
        // データベース接続
        $pdo = db_connect();

        $sql = "SELECT submit_deadline FROM m_deadlines WHERE deadline_id ='".$deadline_id."'";
        $db = $pdo->prepare($sql);
        $db->execute();
        $row = $db->fetch(PDO::FETCH_ASSOC);
        $submit_deadline = $row['submit_deadline'];
        for($i=0; $i < 14; $i++){
            $count = 14 + $i;
            $shift_days[] = date("Y/m/d", strtotime($submit_deadline."+".$count." day")); // 15日後から28日後を取得
        }  

        return $shift_days;
    }

    // シフトを挿入するSQL
    function insert_pre_shift_sql(array $shift_days, int $deadline_id){
        // データベース接続
        $pdo = db_connect();

        // シフトの追加
        for($i=1; $i<15; $i++){
            $tmp_sh = "start_hours".$i;
            $tmp_sm = "start_minites".$i;
            $tmp_eh = "end_hours".$i;
            $tmp_em = "end_minites".$i;

            // 99→何も選択していない状態
            if(!isset($_POST[$tmp_sh])){
                $_POST[$tmp_sh] = 99;
            }
            if(!isset($_POST[$tmp_sm])){
                $_POST[$tmp_sm] = 99;
            }
            if(!isset($_POST[$tmp_eh])){
                $_POST[$tmp_eh] = 99;
            }
            if(!isset($_POST[$tmp_em])){
                $_POST[$tmp_em] = 99;
            }

            $sql = "INSERT INTO t_pre_shift (worker_id, shift_day, start_hour, start_minite, end_hour, end_minite, deadline_id) 
                    VALUES(
                        '" . $_SESSION['worker_id'] . "',
                        '" . $shift_days[$i - 1] . "',
                        '" . $_POST[$tmp_sh] . "',
                        '" . $_POST[$tmp_sm] . "',
                        '" . $_POST[$tmp_eh] . "',
                        '" . $_POST[$tmp_em] . "',
                        '" . $deadline_id . "'
                        )";
            $db = $pdo->prepare($sql);
            $db->execute();
        } 
    }

