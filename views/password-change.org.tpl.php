<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8" />
        <title>管理者パスワード変更ページ</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
        <link rel="stylesheet" href="css/style.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
        <script type="text/javascript" src="js/button.js"></script>
    </head>
    <body>
        <!-- 

         -->
         <div class="header-btn">
            <a class="btn btn-primary" href="admin-mypage.php">管理者ページ</a>
            <a class="btn btn-primary" href="logout.php">ログアウト</a>
        </div>
        <form class="password-form" method="POST">
            <div class="mb-4">
                <input type="text" class="form-control" name="admin_password"placeholder="現在の管理者パスワード" value="<?php echo $_POST['admin_password'] ?>" >
                <p class="form-text text-danger"><?php echo $str; ?></p>
            </div>
            <div class="mb-2">
                <input type="password" class="form-control" name="new_admin_password" placeholder="新しい管理者パスワード" value="<?php echo $_POST['new_admin_password'] ?>">
            </div>
            <div class="mb-2">
                <input type="password" class="form-control" name="confirm_new_admin_password" placeholder="新しい管理者パスワードの確認" value="<?php echo $_POST['confirm_new_admin_password'] ?>">
                <p class="form-text text-danger"><?php echo $str2; ?></p>
            </div>
            <div class="float-right">
                <button type="submit" id="registButton" class="btn btn-primary">変更</button>
            </div>
        </form>
    </body>
</html>