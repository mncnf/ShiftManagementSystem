
<!DOCTYPE html>
<html lang="ja">
    <?php include('header.php'); ?>
    <body>
        <!-- 
            マイページ
                ・メールアドレスとパスワードでログイン可能
                ・アカウント登録していない場合新規アカウント登録画面へ遷移
        -->
        <?php include('menu-btn.php'); ?>
        <p class='fs-4 header-text'>シフト編集ページ</p>
        <form  method="POST" action="<?= $action_url ?>">
            <table width="3700" border="1" class="mb-2 shift-form table-bordered border-dark">
                <tr>
                    <td class="sticky">日付</td>
                    <td></td>
                    <!-- 日付の表示 -->
                    <?php foreach($days as $day) : ?>
                        <td class="border-red" colspan="2"><?= $day?></td>
                    <?php endforeach; ?>
                </tr>
                <tr>
                    <td class="sticky">時間</td>
                    <td></td>
                    <!-- 出勤時間・退勤退勤時間の表示 -->
                    <?php foreach($days as $day) : ?>
                        <td>出勤時間</td>
                        <td class="border-red">退勤時間</td>
                    <?php endforeach; ?>
                </tr>
                <!-- 従業員の希望シフト表示 -->
                <?php if(count($worker_id) != 0): ?>
                    <?php foreach($worker_id as $id) : ?>
                        <tr>
                            <!-- 従業員の名前 -->
                            <td rowspan="2" class="sticky"><?= $last_name[$id-1]." ".$first_name[$id-1]; ?></td>
                            <td>希望</td>
                            <?php
                                // 希望シフトの表示(一人分)
                                $sql = "SELECT * 
                                        FROM t_pre_shift 
                                        WHERE worker_id = '".$id."' 
                                        AND deadline_id = '".$deadline_id."'
                                        ORDER BY shift_day ASC
                                        ";
                                $db_shift = $pdo->prepare($sql);
                                $db_shift->execute();
                                // 日付のカウント
                                $day_counter = 0;
                            ?>
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
                        <tr>
                            <td>確定</td>
                            <!--  JSで入力されているか確認するときに使う -->
                            <input type="hidden"  name="worker_id[]" value="<?= $id; ?>">
                            <?php 
                                try{
                                    // 希望シフトの表示(一人分)
                                    // シフトを確定している場合そのシフトを，確定していない場合確定していない方を表示
                                    $sql = "SELECT COUNT(post.worker_id) 
                                            FROM t_pre_shift pre, t_post_shift post 
                                            WHERE pre.worker_id = post.worker_id 
                                            AND pre.deadline_id = post.deadline_id 
                                            AND pre.shift_day = post.shift_day 
                                            AND post.worker_id = '".$id."'
                                            AND post.deadline_id = '".$deadline_id."'
                                            ";
                                    $db = $pdo->prepare($sql);
                                    $db->execute();
                                    $row = $db->fetch(PDO::FETCH_ASSOC);
                                    $post_shift_count = $row['COUNT(post.worker_id)'];

                                    if($post_shift_count > 0){
                                        $sql = "SELECT * 
                                                FROM t_post_shift 
                                                WHERE worker_id = '".$id."' 
                                                AND deadline_id = '".$deadline_id."'
                                                ORDER BY shift_day ASC
                                                ";
                                    }else{
                                        $sql = "SELECT * 
                                                FROM t_pre_shift 
                                                WHERE worker_id = '".$id."' 
                                                AND deadline_id = '".$deadline_id."'
                                                ORDER BY shift_day ASC
                                                ";
                                    }
                                    $db_shift = $pdo->prepare($sql);
                                    $db_shift->execute();

                                    // 日付のカウント
                                    $day_counter = 0;
                                }catch(PDOException $Exception){
                                    die('接続エラー：' .$Exception->getMessage());
                                }
                            ?>
                            <?php foreach($db_shift as $shift_row) : ?>
                                <?php $day_counter++; ?>
                                <td>
                                    <!-- idは従業員idと開始時間の日付 -->
                                    <select class="<?= $sh_array[$id."start_hours".$day_counter] ?>" name="<?= $id."start_hours".$day_counter; ?>">
                                        <option value="99"></option>
                                        <?php for($i=10; $i<24; $i++) : ?>
                                            <!-- シフトを提出している日があれば選択済み状態にする -->
                                            <?php if($shift_row['start_hour'] == $i || (isset($_POST[$id."start_hours".$day_counter]) && $_POST[$id."start_hours".$day_counter] == $i)) : ?>
                                                <option value="<?= $i; ?>" selected><?= $i."時"; ?></option>
                                            <?php else : ?>
                                                <option value="<?= $i; ?>"><?= $i."時"; ?></option>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                    </select>
                                    <!-- 分の計算 -->
                                    <select class="<?= $sm_array[$id."start_minites".$day_counter] ?>" name="<?= $id."start_minites".$day_counter; ?>">
                                        <option value="99"></option>ｓ
                                        <?php for($i=0; $i<12; $i++) : ?>
                                            <?php $minites = $i * 5; ?>
                                            <!-- シフトを提出している場合選択済みにする -->
                                            <?php if($shift_row['start_minite'] == $minites || (isset($_POST[$id."start_minites".$day_counter]) && $_POST[$id."start_minites".$day_counter] == $minites)) : ?>
                                                <option value="<?= $minites; ?>" selected><?= $minites."分"; ?></option>
                                            <?php else : ?>
                                                <option value="<?= $minites; ?>"><?= $minites."分"; ?></option>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                    </select>
                                </td>
                                <td class="border-red">
                                    <!-- 時の計算 -->
                                    <select class="<?= $eh_array[$id."end_hours".$day_counter] ?>" name="<?= $id."end_hours".$day_counter; ?>">
                                        <option value="99"></option>
                                        <?php for($i=10; $i<24; $i++) : ?>
                                            <!-- シフトを提出している場合選択済みにする -->
                                            <?php if($shift_row['end_hour'] == $i || (isset($_POST[$id."end_hours".$day_counter]) && $_POST[$id."end_hours".$day_counter] == $i)) : ?>
                                                <option value="<?= $i; ?>" selected><?= $i."時"; ?></option>
                                            <?php else: ?>
                                                <option value="<?= $i; ?>"><?= $i."時"; ?></option>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                    </select>
                                    <!-- 分の計算 -->
                                    <select class="<?= $em_array[$id."end_minites".$day_counter] ?>" name="<?= $id."end_minites".$day_counter; ?>">
                                        <option value="99"></option>
                                        <?php for($i=0; $i<12; $i++) : ?>
                                            <?php $minites = $i * 5; ?>
                                            <!-- シフトを提出している場合選択済みにする -->
                                            <?php if($shift_row['end_minite'] == $minites || (isset($_POST[$id."end_minites".$day_counter]) && $_POST[$id."end_minites".$day_counter] == $minites)) : ?>
                                                <option value="<?= $minites; ?>" selected><?= $minites."分"; ?></option>
                                            <?php else : ?>
                                                <option value="<?= $minites; ?>"><?= $minites."分"; ?></option>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                    </select>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </table>
            <p class="text-center fixed-text text-danger"><?= $wrong_shift ?></p>
            <?php if($submit_btn_flag) : ?>
                <button type="submit" class="fixed-submit-btn btn btn-primary">更新</button>
            <?php else : ?>
                <button disabled type="submit" class="fixed-submit-btn btn btn-danger" onclick="event.preventDefault(); AdminSubmitButtonClick();">更新期間外</button>
            <?php endif; ?>
            <input type="hidden" name="deadline_id" value="<?= $deadline_id; ?>">
        </form>
    </body>
</html>