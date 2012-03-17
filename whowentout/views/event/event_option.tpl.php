<?php
$current_user = auth()->current_user();

/* @var $invite_engine InviteEngine */
$invite_engine = build('invite_engine');

/* @var $checkin_engine CheckinEngine */
$checkin_engine = build('checkin_engine');

benchmark::start('get_invite');
$invite_senders = $invite_engine->get_invite_senders($event, $current_user);
benchmark::end('get_invite');

$data = array(
    'template' => 'event-option',
    'event' => to::json($event),
    'is_selected' => $is_selected,
    'selected_event' => to::json($selected_event),
    'invite_senders' => to::json($invite_senders),
);
?>

<?= html_element_open('div', array('class' => 'event_option render'), $data) ?>
<?= html_element_close('div') ?>
