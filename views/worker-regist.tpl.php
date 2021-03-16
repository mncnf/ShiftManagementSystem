<!DOCTYPE html>
<html lang="ja">
    <?php include('header.php'); ?>
    <body>
        <!-- 
            新規アカウント登録画面
                ・名前，メールアドレス，パスワードを入力しアカウント作成
                ・入力していない箇所がある場合は警告
                ・メールアドレスが間違っている場合は警告
                ・パスワードが間違っている場合は警告
            idの付け方
                ・1回目の入力はinput
                ・2回目の入力はconfirm
                ・入力が不十分な場合の指摘warning
         -->
        <form class="regist-form" method="POST" action="<?= $action_url ?>">
            <div class="mb-2">
                <input type="text" class="form-control" name="last_name" placeholder="姓" value="<?= $_POST['last_name'] ?>">
                <p class="form-text text-danger"><?= $wrong_last_name ?></p>
            </div>
            <div class="mb-4">
                <input type="text" class="form-control" name="first_name" placeholder="名" value="<?= $_POST['first_name'] ?>">
                <p class="form-text text-danger"><?= $wrong_first_name ?></p>
            </div>
            <div class="mb-2">
                <input type="text" class="form-control" name="login_id" placeholder="ログインID" value="<?= $_POST['login_id'] ?>">
                <p class="form-text text-danger"><?= $wrong_login_id ?></p>
            </div>
            <div class="mb-4">
                <input type="text" class="form-control" name="confirm_id" placeholder="ログインIDの確認" value="<?= $_POST['confirm_id'] ?>">
                <p class="form-text text-danger"><?= $wrong_confirm_id ?></p>
            </div>
            <div class="mb-2">
                <input type="password" class="form-control" name="password" placeholder="パスワード" value="<?= $_POST['password'] ?>">
                <p class="form-text text-danger"><?= $wrong_password ?></p>
            </div>
            <div class="mb-4">
                <input type="password" class="form-control" name="confirm_password" placeholder="パスワードの確認" value="<?= $_POST['confirm_password'] ?>">
                <p class="form-text text-danger"><?= $wrong_confirm_password ?></p>
            </div>
            <div class="float-right">
                <button type="submit" id="registButton" class="btn btn-primary">登録</button>
            </div>
        </form>
    </body>
</html>