<?php
    // テーブル作成
    require "createtable.php";
    $create = new CreateTable();
    $create->createWorkersTable();
    $create->createPreShiftTable();
    $create->createPostShiftTable();
    $create->createPasswordsTable();
    $create->createDeadlinesTable();

    $title = "シフト管理システム";
    $left_btn = "admin-login.php";
    $left_btn_text = "管理者";
    $right_btn = "worker-login.php";
    $right_btn_text = "従業員";
    require_once "views/select.tpl.php";