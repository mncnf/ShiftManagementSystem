
<!DOCTYPE html>
<html lang="ja">
    <?php include("header.php"); ?>
    <body>
        <!-- 
            マイページ
                ・メールアドレスとパスワードでログイン可能
                ・アカウント登録していない場合新規アカウント登録画面へ遷移
        --> 
        <?php include('menu-btn.php'); ?>
        <form id="admin_shift_form" method="POST">
            <select class="fixed-btn" name="select_day" onchange="submit(this.form)">
                <option value="">日付を選択</option>
                <?php foreach($deadline_id_list as $value) : ?>
                    <option value="<?= $value;?>"><?= $s_days[$select_count]."〜".$e_days[$select_count];?></option>
                    <?php $select_count++; ?>
                <?php endforeach; ?>
            </select>
            <table width="2100" border="1" class="shift-form table-bordered border-dark">
                <tr>
                    <td class="sticky">日付</td>
                    <!-- 日付の表示 -->
                    <?php foreach($days as $day) : ?>
                        <td class="border-red" colspan="2"><?= $day;?></td>
                    <?php endforeach; ?>
                </tr>
                <tr>
                    <td class="sticky">時間</td>
                    <!-- 出勤時間・退勤退勤時間の表示 -->
                    <?php foreach($days as $day) : ?>
                        <td>出勤時間</td>
                        <td class="border-red">退勤時間</td>
                    <?php endforeach; ?>
                </tr>
                <!-- 従業員の希望シフト取得 -->
                <?php if(count($worker_id) != 0): ?>
                    <?php foreach($worker_id as $id) : ?>
                        <tr>
                            <!-- 従業員の名前 -->
                            <td class="sticky"><?= $last_name[$id-1]." ".$first_name[$id-1]; ?></td>
                            <?php 
                                try{
                                    // 希望シフトの表示(一人分)
                                    $sql = "SELECT * FROM t_post_shift WHERE worker_id = '".$id."' AND deadline_id = '".$deadline_id."' ORDER BY shift_day ASC";
                                    $db_shift = $pdo->prepare($sql);
                                    $db_shift->execute();
                                    // 日付のカウント
                                    $day_counter = 0;
                                }catch(PDOException $Exception){
                                    die('接続エラー：' .$Exception->getMessage());
                                }
                            ?>
                            <!-- 従業員ごとの情報を取得 -->
                            <?php foreach($db_shift as $shift_row) : ?>
                                <?php $day_counter++; ?>
                                <td>
                                    <!-- 出勤時刻の表示 -->
                                    <?php if($shift_row['start_hour'] != 99 && $shift_row['start_minite'] != 99) : ?>
                                        <?= $shift_row['start_hour']."時".$shift_row['start_minite']."分";;?>
                                    <?php endif; ?>
                                </td>
                                <td class="border-red">
                                    <!-- 退勤時刻の表示 -->
                                    <?php if($shift_row['end_hour'] != 99 && $shift_row['end_minite'] != 99) : ?>
                                        <?= $shift_row['end_hour']."時".$shift_row['end_minite']."分";;?>
                                    <?php endif; ?>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </table>
        </form>
    </body>
</html>