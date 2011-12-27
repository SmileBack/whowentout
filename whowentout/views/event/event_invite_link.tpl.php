<?=
a(app()->event_invite_link($event),
        'Invite your friends to ' . $event->name . '!',
    array(
        'class' => 'event_invite_link',
        'data-event-id' => $event->id,
    ))
?>
