<?=
a(app()->event_invite_link($event),
        'Invite Friends',
    array(
        'class' => 'event_invite_link',
        'data-event-id' => $event->id,
    ))
?>
