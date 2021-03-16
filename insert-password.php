<?php
    require_once "db_connect.php";
    $pdo = db_connect();

    // パスワードテーブルの削除
    $sql = "DROP TABLE IF EXISTS m_passwords";
    $db = $pdo->prepare($sql);
    $db->execute();

    // パスワードテーブルの作成
    $sql = "CREATE TABLE IF NOT EXISTS m_passwords (
        admin_password varchar(256) NOT NULL
    )";
    $db = $pdo->prepare($sql);
    $db->execute();
    
    if(isset($_POST['new_password'])){
        $sql = "INSERT INTO m_passwords value ('".$_POST['new_password']."')";
        $db = $pdo->prepare($sql);
        $db->execute();
    }else{
        $sql = "INSERT INTO m_passwords value ('admin')";
        $db = $pdo->prepare($sql);
        $db->execute();
    }