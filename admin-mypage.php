<?php
    session_start();
    if(!isset($_SESSION["admin_password"])){
        header('Location: admin-login.php');
        exit;
    }

    $title = "管理者ページ";
    $navi_text = "管理者ページ";
    $left_menu_btn = "admin-password_change.php";
    $left_menu_btn_text = "管理者パスワード変更";
    $right_menu_btn = "logout.php";
    $right_menu_btn_text = "ログアウト";
    $left_btn = "admin-shift.php";
    $left_btn_text = "シフト確認";
    $right_btn = "admin-submit.php";
    $right_btn_text = "シフト編集";
    require_once "views/mypage.tpl.php";