<?php
$user = current_user();
$today = $user->college->get_clock()->get_time()->getDay(0);
$party_days = array(
    $today->getPartyDay(-1),
    $today->getPartyDay(-2),
    $today->getPartyDay(-3),
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
