<!DOCTYPE html>
<html lang="ja">
    <?php include('header.php'); ?>
    <body>
        <!-- 
            シフト提出ページ
                ・ログインしていない場合ログイン画面へ遷移
                ・希望出勤時刻，希望退勤時刻を指定してシフト希望を提出可能
                ・未入力の箇所を指摘
         -->
        <?php include('menu-btn.php'); ?>
        <form method="POST" action="<?= $action_url ?>">
            <table border="1" class="shift-form table-bordered border-dark">
                <tr>
                    <th>日付</th>
                    <th>希望出勤時刻</th>
                    <th>希望退勤時刻</th>
                </tr>
                <!-- シフトの表示 -->
                <?php foreach ($db_shift as $shift_row) : ?>
                <?php $day_counter++; ?>
                <tr>
                    <td>
                        <!-- 日付配列のどこを参照するか指定(要素0に1日目が入っている) -->
                        <?= $days[$day_counter-1] ?>
                    </td>
                    <td>
                        <!-- 時の計算 -->
                        <select class="<?= $sh_array["start_hours".$day_counter] ?>" name="<?= "start_hours".$day_counter ?>">
                            <option value="99"></option>
                            <?php for($i=10; $i<24; $i++) : ?>
                                <!-- シフトを提出している日があれば選択済み状態にする -->
                                <?php if($shift_row['start_hour'] == $i || (isset($_POST["start_hours".$day_counter]) && $_POST["start_hours".$day_counter] == $i)) : ?>
                                    <option value="<?= $i ?>" selected><?= $i."時" ?></option>
                                <?php else : ?>
                                    <option value="<?= $i ?>"><?= $i."時" ?></option>
                                <?php endif; ?>
                            <?php endfor; ?>
                        </select>
                        <!-- 分の計算 -->
                        <select class="<?= $sm_array["start_minites".$day_counter] ?>" name="<?= "start_minites".$day_counter ?>">
                            <option value="99"></option>
                            <?php for($i=0; $i<12; $i++) : ?>
                                <?php $minites = $i * 5; ?>
                                <!-- シフトを提出している場合選択済みにする -->
                                <?php if($shift_row['start_minite'] == $minites || (isset($_POST["start_minites".$day_counter]) && $_POST["start_minites".$day_counter] == $minites)) : ?>
                                    <option value="<?= $minites ?>" selected><?= $minites."分" ?></option>
                                <?php else : ?>
                                    <option value="<?= $minites ?>"><?= $minites."分" ?></option>
                                <?php endif; ?>
                            <?php endfor; ?>
                        </select>
                    </td>
                    <td>
                        <!-- 時の計算 -->
                        <select class="<?= $eh_array["end_hours".$day_counter] ?>" name="<?= "end_hours".$day_counter ?>">
                            <option value="99"></option>
                            <?php for($i=10; $i<24; $i++) : ?>
                                <!-- シフトを提出している場合選択済みにする -->
                                <?php if($shift_row['end_hour'] == $i || (isset($_POST["end_hours".$day_counter]) && $_POST["end_hours".$day_counter] == $i)) : ?>
                                    <option value="<?= $i ?>" selected><?= $i."時" ?></option>
                                <?php else: ?>
                                    <option value="<?= $i ?>"><?= $i."時" ?></option>
                                <?php endif; ?>
                            <?php endfor; ?>
                        </select>
                        <!-- 分の計算 -->
                        <select class="<?= $em_array["end_minites".$day_counter] ?>" name="<?= "end_minites".$day_counter ?>">
                            <option value="99"></option>
                            <?php for($i=0; $i<12; $i++) : ?>
                                <?php $minites = $i * 5; ?>
                                <!-- シフトを提出している場合選択済みにする -->
                                <?php if($shift_row['end_minite'] == $minites || (isset($_POST["end_minites".$day_counter]) && $_POST["end_minites".$day_counter] == $minites)) : ?>
                                    <option value="<?= $minites ?>" selected><?= $minites."分" ?></option>
                                <?php else : ?>
                                    <option value="<?= $minites ?>"><?= $minites."分" ?></option>
                                <?php endif; ?>
                            <?php endfor; ?>
                        </select>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
            <p class="text-center text-danger"><?= $wrong_shift ?></p>
            <div class="float-right">
                <button type="submit" id="shiftSubmitButton" class="fixed-submit-btn btn btn-primary">提出</button>
            </div>
            <input type="hidden" name="deadline_id" value="<?= $deadline_id ?>">
        </form>
    </body>
</html>