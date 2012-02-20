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

        $invites = db()->table('invites')->where('event.date', $checkin->event->date);

        foreach ($invites as $invite) {
            if ($invite->event_id == $checkin->event_id)
                $invite->status = 'accepted';
            else
                $invite->status = 'pending';

            $invite->save();
        }
    }

}
