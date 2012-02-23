<?php
/* @var $checkin_engine CheckinEngine */
$checkin_engine = build('checkin_engine');
$checkins = $checkin_engine->get_checkins_on_date($date);

$breakdown = array();

$friends = array();
foreach (auth()->current_user()->friends as $friend) {
    $friends[$friend->id] = $friend;
}

foreach ($checkins as $checkin) {
    if (!isset($breakdown[$checkin->event->id])) {
        $breakdown[$checkin->event->id] = array(
            'event' => $checkin->event,
            'checkins' => array(),
            'count' => 0,
            'friends' => array(),
        );
    }
    $breakdown[$checkin->event->id]['checkins'][] = $checkin;
    $breakdown[$checkin->event->id]['count']++;

    if (isset($friends[$checkin->user->id]))
        $breakdown[$checkin->event->id]['friends'][] = $checkin->user;
}
?>

<table class="event_day_summary">
    <?php foreach ($breakdown as $summary): ?>
        <tr>

            <td class="event_name"><?= $summary['event']->name ?></td>

            <td class="event_attendance">
                <div class="barchart">
                    <div class="innerbar" style="width: <?= $summary['count'] * 2 ?>%;"></div>
                    <span><?= $summary['count'] ?></span>
                </div>
            </td>

            <td class="event_friends">
                <?php if (count($summary['friends']) > 0): ?>
                <?= format::people($summary['friends']) ?> are attending.
                <?php endif; ?>
            </td>

        </tr>
    <?php endforeach; ?>
</table>
