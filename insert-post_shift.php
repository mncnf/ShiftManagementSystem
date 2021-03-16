<?php
    require_once "db_connect.php";
    
    session_start();
    if(!isset($_SESSION["admin_password"])){
        header('Location: admin-login.php');
        exit;
    }

    // シフトの更新
    function update_post_shift(array $worker_id_list, int $deadline_id){
        // データベース接続
        $pdo = db_connect();

        // 日付を取得
        $shift_days = get_shift_days($deadline_id);

        foreach($worker_id_list as $id){
            // 古いシフト情報を削除(更新している)
            $sql = "DELETE FROM t_post_shift 
                    WHERE worker_id='".$id."' 
                    AND deadline_id='".$deadline_id."'";
            $db = $pdo->prepare($sql);
            $db->execute();
            // シフト追加
            insert_post_shift_sql($id, $shift_days, $deadline_id);
        }
    }

    // 日付の取得
    function get_shift_days(int $deadline_id){
        // データベース接続
        $pdo = db_connect();

        $sql = "SELECT submit_deadline 
                FROM m_deadlines 
                WHERE deadline_id ='".$deadline_id."'
                ";
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

    function insert_post_shift_sql(int $id, array $shift_days, int $deadline_id){
        // データベース接続
        $pdo = db_connect();

        // シフトの追加
        for($i=1; $i<15; $i++){
            $tmp_sh = $id."start_hours".$i;
            $tmp_sm = $id."start_minites".$i;
            $tmp_eh = $id."end_hours".$i;
            $tmp_em = $id."end_minites".$i;

            $sql = "INSERT 
                    INTO t_post_shift (
                        worker_id, 
                        shift_day, 
                        start_hour, 
                        start_minite, 
                        end_hour, 
                        end_minite, 
                        deadline_id
                        ) 
                    VALUES(
                        '" . $id . "',
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