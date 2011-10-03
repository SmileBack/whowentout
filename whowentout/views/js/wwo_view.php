<?php
$wwo = array();
$wwo['currentTime'] = current_time()->getTimestamp();
$wwo['doorsClosingTime'] = college()->get_closing_time()->getTimestamp();
$wwo['doorsOpeningTime'] = college()->get_opening_time()->getTimestamp();
$wwo['yesterdayTime'] = college()->yesterday()->getTimestamp();
$wwo['tomorrowTime'] = college()->tomorrow()->getTimestamp();
$wwo['doorsOpen'] = college()->doors_are_open();

if (logged_in()) {
    $wwo['currentUserID'] = current_user()->id;
    var_dump(logged_in());
    $wwo['chatbar_state'] = current_user()->chatbar_state;
}
?>

<div id="wwo" style="display: none;"><?= json_encode($wwo) ?></div>
