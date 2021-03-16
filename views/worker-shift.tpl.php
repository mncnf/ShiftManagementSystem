<!DOCTYPE html>
<html lang="ja">
    <?php include("header.php"); ?>
    <body>
        <!-- 
            シフト確認ページ
                ・ログインしていない場合ログイン画面へ遷移
                ・確定したシフトを確認可能
        -->
        <?php include('menu-btn.php'); ?>
        <?php  if($shift_count > 0) : ?>
            <form method="POST">
                <select class="tmp-btn mb-2" name="select_day" onchange="submit(this.form)">
                    <option value="">日付を選択</option>
                    <?php foreach($deadline_id_list as $value) : ?>
                        <option value="<?= $value ?>"><?= $s_days[$select_count]."〜".$e_days[$select_count] ?></option>
                        <?php $select_count++; ?>
                    <?php endforeach; ?>
                </select>
                <table border="1" class="tmp-form table-bordered border-dark">
                    <tr>
                        <th>日付</th>
                        <th>出勤時刻</th>
                        <th>退勤時刻</th>
                    </tr>
                    <?php foreach ($db_shift as $shift_row) : ?>
                        <?php $day_counter++; ?>
                        <tr>
                            <td>
                                <!-- 日付配列のどこを参照するか指定(要素0に1日目が入っている) -->
                                <?= $days[$day_counter-1] ?>
                            </td>
                            <td>
                                <!-- 出勤時刻の表示 -->
                                <?php if($shift_row['start_hour'] != 99 && $shift_row['start_minite'] != 99) : ?>
                                    <?= $shift_row['start_hour']."時".$shift_row['start_minite']."分" ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <!-- 退勤時刻の表示 -->
                                <?php if($shift_row['end_hour'] != 99 && $shift_row['end_minite'] != 99) : ?>
                                    <?= $shift_row['end_hour']."時".$shift_row['end_minite']."分" ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </form>
        <?php else : ?>
            <div class="center"><?= $wrong_shift ?></div>
        <?php endif; ?>
    </body>
</html>