<?php
/* @var $checkin_engine CheckinEngine */
$checkin_engine = factory()->build('checkin_engine');
$current_user = auth()->current_user();

$checkins = array();

function get_days_checkins_for_event($event, $current_user, $checkin_engine)
{
    $days_checkins = array();
    if ($event) {
        $events_on_date = db()->table('events')
                ->where('date', $event->date);

        foreach ($events_on_date as $cur_event) {
            if ($cur_event != $event) {
                $event_checkins = $checkin_engine->get_checkins_for_event($cur_event);
                $checkins[] = $event_checkins;
            }
        }

        usort($checkins, function($a, $b)
        {
            $count_a = count($a);
            $count_b = count($b);
            if ($count_a == $count_b)
                return 0;
            else
                return count($a) > count($b) ? 1 : -1;
        });

        array_unshift($checkins, $checkin_engine->get_checkins_for_event($event));

        foreach ($checkins as $checkins_for_event) {
            foreach ($checkins_for_event as $checkin) {
                if ($checkin->user_id != $current_user->id)
                    $days_checkins[] = $checkin;
                else
                    array_unshift($days_checkins, $checkin); // you are on the beginning 
            }
        }

    }

    return $days_checkins;
}

$after_event = $event->date->getDay(+1);
$now = app()->clock()->get_time();

$checkins = get_days_checkins_for_event($event, $current_user, $checkin_engine);
?>

<div class="checkin_gallery">
    <?= r::event_invite_link(array('event' => $event)) ?>
    <ul>
        <?php foreach ($checkins as $checkin): ?>
        <li>
            <?= r::profile_small(array('user' => $checkin->user)) ?>
            <?php if ($now >= $after_event): ?>
            <div>attended <?= $checkin->event->name ?></div>
            <?php else: ?>
            <div>attending <?= $checkin->event->name ?></div>
            <?php endif; ?>
        </li>
        <?php endforeach; ?>
    </ul>
</div>
