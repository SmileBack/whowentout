<?php

/**
 * Automatically accept all invites that were sent to the current user
 * for a particular event when that user checks in
 */
class AutoAcceptInvitePlugin extends Plugin
{

    function on_checkin($e)
    {
        $checkin = $e->checkin;

        if (!db()->has_table('invites'))
            return;

        $invites = db()->table('invites')
                       ->where('event.date', $checkin->event->date)
                       ->where('receiver_id', $checkin->user->id);

        foreach ($invites as $invite) {
            if ($invite->event_id == $checkin->event_id) {
                $invite->status = 'accepted';
                $invite->accepted_at = app()->clock()->get_time();
            }
            else {
                $invite->status = 'pending';
            }

            $invite->save();
        }
    }

}
