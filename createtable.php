<?php
require_once "db_connect.php";

Class CreateTable{

    // 従業員テーブル作成
    public function createWorkersTable(){
        $pdo = db_connect();

        $sql = "CREATE TABLE IF NOT EXISTS m_workers (
            worker_id int AUTO_INCREMENT NOT NULL PRIMARY KEY,
            last_name varchar(20),
            first_name varchar(20),
            login_id varchar(20),
            password varchar(20)
        )";
        $db = $pdo->prepare($sql);
        $db->execute();
    }

    // シフトテーブル作成(従業員用)
    public function createPreShiftTable(){
        $pdo = db_connect();
        $sql = "CREATE TABLE IF NOT EXISTS t_pre_shift (
            shift_id int AUTO_INCREMENT NOT NULL PRIMARY KEY,
            worker_id int NOT NULL,
            shift_day DATETIME,
            start_hour int,
            start_minite int,
            end_hour int,
            end_minite int,
            deadline_id int
        )";
        $db = $pdo->prepare($sql);
        $db->execute();
    }

    // シフトテーブル作成(管理者用)
    public function createPostShiftTable(){
        $pdo = db_connect();
        $sql = "CREATE TABLE IF NOT EXISTS t_post_shift (
            shift_id int AUTO_INCREMENT NOT NULL PRIMARY KEY,
            worker_id int NOT NULL,
            shift_day DATETIME,
            start_hour int,
            start_minite int,
            end_hour int,
            end_minite int,
            deadline_id int
        )";
        $db = $pdo->prepare($sql);
        $db->execute();
    }


    // 管理者パスワード作成
    public function createPasswordsTable(){
        // データベース接続
        $pdo = db_connect();

        // パスワードテーブルの作成
        $sql = "CREATE TABLE IF NOT EXISTS m_passwords (
            admin_password varchar(256) NOT NULL
        )";
        $db = $pdo->prepare($sql);
        $db->execute();

        // すでに追加されているか確認
        $sql = "SELECT COUNT(*) 
                FROM m_passwords
                ";
        $db = $pdo->prepare($sql);
        $db->execute();
        $row = $db->fetch(PDO::FETCH_ASSOC);
        if($row['COUNT(*)'] == 0){
            $sql = "INSERT INTO m_passwords value ('password')";
            $db = $pdo->prepare($sql);
            $db->execute();
        }
    }

    // 締め切り作成
    public function createDeadlinesTable(){
        // データベース接続
        $pdo = db_connect();

        // 締め切りテーブルの作成
        $sql = "CREATE TABLE IF NOT EXISTS m_deadlines (
            deadline_id int AUTO_INCREMENT NOT NULL PRIMARY KEY,
            submit_deadline DATETIME,
            update_deadline DATETIME
        )";
        $db = $pdo->prepare($sql);
        $db->execute();

        // すでに追加されているか確認
        $sql = "SELECT COUNT(*) 
                FROM m_deadlines
                ";
        $db = $pdo->prepare($sql);
        $db->execute();
        $row = $db->fetch(PDO::FETCH_ASSOC);
        if($row['COUNT(*)'] == 0){
            //締切日の追加
            $submit_array = array();
            $update_array = array();
            $submit_array[0] = "2021-01-14 23:59:59"; //基準となる締切日
            $update_array[0] = "2021-01-21 23:59:59"; //基準となる締切日
            for($i = 0; $i < 100; $i++){
                $submit_array[$i + 1] = date("Y-m-d H:i:s",strtotime($submit_array[$i] . "2 week"));
                $update_array[$i + 1] = date("Y-m-d H:i:s",strtotime($update_array[$i] . "2 week"));
                $sql = "INSERT INTO m_deadlines (submit_deadline, update_deadline) values('" . $submit_array[$i + 1] . "', '" . $update_array[$i + 1] . "')";
                $db = $pdo->prepare($sql);
                $db->execute();
            }
        }
    }

}

    