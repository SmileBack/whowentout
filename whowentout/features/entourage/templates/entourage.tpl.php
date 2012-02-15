<?php
/* @var $entourage_engine EntourageEngine */
$entourage_engine = build('entourage_engine');
$entourage = $entourage_engine->get_entourage($user);
$entourage_count = count($entourage);
?>

<div class="profile">
    <div class="profile_inner">

        <h1>You have <?= Inflect::pluralize_if($entourage_count, 'person') ?> in your entourage.</h1>

        <?= r::received_entourage_requests_section(array('user' => $user)); ?>

        <?= r::entourage_section(array('user' => $user, 'show_invite_link' => true)); ?>

        <?php if (count($sent_entourage_requests) > 0): ?>
        <section class="entourage_outgoing_requests">
            <h3>Entourage Requests Sent</h3>
            <?= r::entourage_outgoing_requests(); ?>
        </section>
        <?php endif; ?>

    </div>
</div>
