<?php
    require_once "db_connect.php";
    $pdo = db_connect();
    
    //従業員を追加
    if(isset($_POST["last_name"]) && isset($_POST["first_name"]) && isset($_POST["login_id"]) && isset($_POST["password"])){
        $sql = "INSERT INTO m_workers (last_name, first_name, login_id, password) 
                VALUES(
                    '". $_POST["last_name"] . "', 
                    '" . $_POST["first_name"] . "', 
                    '" . $_POST["login_id"] . "', 
                    '" . $_POST["password"]. "'
                    )";
        $db = $pdo->prepare($sql);
        $db->execute();
    }