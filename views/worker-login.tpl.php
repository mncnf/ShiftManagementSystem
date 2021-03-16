<!DOCTYPE html>
<html lang="ja">
    <?php include('header.php'); ?>
    <body>
        <!-- 
            ログインフォーム
                ・ログインIDとパスワードでログイン可能
                ・IDまたはパスワードが違う場合指摘
                ・アカウント登録していない場合新規アカウント登録画面へ遷移
        -->
        <form class="login-form" method="POST">
            <div class="mb-3">
                <input type="text" name="login_id" class="form-control" id="inputId" placeholder="ログインID" value="<?= $_POST['login_id'] ?>">
            </div>
            <div class="mb-3">
                <input type="password" name="password" class="form-control" id="inputPassword" placeholder="パスワード" value="<?= $_POST['password'] ?>">
            </div>
            <p class="form-text text-danger"><?= $wrong_message ?></p>
            <div class="float-left align-bottom align-text-bottom">
                <a href="worker-regist.php">アカウント作成</a>
            </div>
            <div class="float-right">
                <button type="submit" class="btn btn-primary">ログイン</button>
            </div>
        </form>
    </body>
</html>