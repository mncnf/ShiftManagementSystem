<!DOCTYPE html>
<html lang="ja">
    <?php include('header.php'); ?>
    <body>
        <!-- 
            パスワード変更フォーム
         -->
         <?php include('menu-btn.php'); ?>
        <form class="password-form" method="POST">
            <div class="mb-4">
                <input type="text" class="form-control" name="password"placeholder="現在のパスワード" value="<?= $_POST['password'] ?>" >
                <p class="form-text text-danger"><?= $wrong_message1 ?></p>
            </div>
            <div class="mb-2">
                <input type="password" class="form-control" name="new_password" placeholder="パスワード" value="<?= $_POST['new_password'] ?>">
            </div>
            <div class="mb-2">
                <input type="password" class="form-control" name="confirm_new_password" placeholder="新しいパスワードの確認" value="<?= $_POST['confirm_new_password'] ?>">
                <p class="form-text text-danger"><?= $wrong_message2 ?></p>
            </div>
            <div class="float-right">
                <button type="submit" id="registButton" class="btn btn-primary">変更</button>
            </div>
        </form>
    </body>
</html>