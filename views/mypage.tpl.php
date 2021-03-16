<!DOCTYPE html>
<html lang="ja">
    <?php include('header.php'); ?>
    <body>
        <!--
            マイページのテンプレート
                ・インストラクション
                ・メニューボタン
                ・セレクトボタン
        -->
        <p class='fs-4 header-text'><?= $navi_text ?></p>
        <?php include('menu-btn.php'); ?>
        <?php include('select-btn.php'); ?>
    </body>
</html>