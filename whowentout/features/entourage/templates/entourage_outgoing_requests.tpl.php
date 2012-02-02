<?php
$current_user = auth()->current_user();
/* @var $entourage_engine EntourageEngine */
$entourage_engine = build('entourage_engine');
$requests = $entourage_engine->get_pending_outgoing_requests($current_user);
?>
<ul>
   <?php foreach ($requests as $request): ?>
        <li>
            <?=
            r::profile_small(array(
                'user' => $request->receiver,
                'show_networks' => false,
                'link_to_profile' => true,
            ))
            ?>
        </li>
   <?php endforeach; ?>
</ul>

