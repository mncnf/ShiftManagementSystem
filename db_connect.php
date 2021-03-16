<?php
    function db_connect(){
        try{
            // データベース接続
            $pdo = new PDO(
                'mysql:host=[ホスト名];dbname=[データベース名];',
                '[ユーザー名]',
                '[パスワード]'
            );
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

            return $pdo;
        }catch(PDOException $Exception){
            die('接続エラー：' .$Exception->getMessage());
        }
    }