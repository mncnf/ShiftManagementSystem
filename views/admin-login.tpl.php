<!DOCTYPE html>
<html lang="ja">
    <?php include('header.php'); ?>
    <body>
        <!-- 
            管理者ログインページ
                ・管理者パスワードを入力し合っている場合管理者ページへ遷移
         -->
        <form class="login-form" method="POST">
            <div class="mb-2">
                <input type="password" class="form-control" name="admin_password" id="inputPassword" placeholder="管理者パスワード">
                <p class="form-text text-danger"><?= $wrong_message ?></p>
            </div>
            <div class="float-right">
                <button type="submit" id="adminLoginButton" class="btn btn-primary">ログイン</button>
            </div>
        </form>
    </body>
</html>