<?php
/* @var $checkin_engine CheckinEngine */
$checkin_engine = build('checkin_engine');
$checkins = $checkin_engine->get_checkins_on_date($date);

$breakdown = array();
foreach ($checkins as $checkin) {
    if (!isset($breakdown[$checkin->event->id])) {
        $breakdown[$checkin->event->id] = array(
            'event' => $checkin->event,
            'checkins' => array(),
            'count' => 0,
        );
    }
    $breakdown[$checkin->event->id]['checkins'][] = $checkin;
    $breakdown[$checkin->event->id]['count']++;
}
?>
<table class="event_day_summary">
    <?php foreach ($breakdown as $summary): ?>
        <tr>
            <td><?= $summary['event']->name ?></td>
            <td>
                <div class="barchart">
                    <div class="innerbar" style="width: <?= $summary['count'] * 5 ?>%;"></div>
                </div>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
