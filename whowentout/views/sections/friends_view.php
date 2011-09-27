<?php
$user = current_user();
$party_days = array(
    $user->college->party_day(-1, TRUE),
    $user->college->party_day(-2, TRUE),
    $user->college->party_day(-3, TRUE),
);
?>

<?php foreach ($party_days as $day): ?>
    <div class="friends_breakdown" data-date="<?= $day->format('Y-m-d') ?>">
        <h1><?= $day->format('l, M. jS') ?></h1>

        <div class="loading_message">
            Loading Friends
        </div>

        <div class="piechart"></div>
        <div class="friend_galleries"></div>
    </div>
<?php endforeach; ?>
