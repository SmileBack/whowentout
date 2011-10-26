<?php

$wwo = array();
$wwo['currentTime'] = college()->get_time()->getTimestamp();
$wwo['doorsOpeningTime'] = college()->get_door()->get_opening_time()->getTimestamp();
$wwo['doorsClosingTime'] = college()->get_door()->get_closing_time()->getTimestamp();
$wwo['yesterdayTime'] = college()->get_time()->getDay(-1)->getTimestamp();
$wwo['tomorrowTime'] = college()->get_time()->getDay(+1)->getTimestamp();
$wwo['doorsOpen'] = college()->get_door()->is_open();

if (logged_in()) {
    $wwo['currentUserID'] = current_user()->id;
    $wwo['chatbar_state'] = current_user()->chatbar_state;
}
?>

<div id="wwo" style="display: none;"><?= json_encode($wwo) ?></div>
