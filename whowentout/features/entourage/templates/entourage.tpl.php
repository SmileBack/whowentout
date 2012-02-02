<div class="profile">
    <div class="profile_inner">

        <?php if (count($received_entourage_requests) > 0): ?>
        <section class="entourage_incoming_requests gallery">
            <h3>Entourage Requests Received</h3>
            <?= r::entourage_incoming_requests(); ?>
        </section>
        <?php endif; ?>

        <?= r::entourage_section(array('user' => $user, 'show_invite_link' => true)); ?>

        <?php if (count($sent_entourage_requests) > 0): ?>
        <section class="entourage_outgoing_requests gallery">
            <h3>Entourage Requests Sent</h3>
            <?= r::entourage_outgoing_requests(); ?>
        </section>
        <?php endif; ?>

    </div>
</div>
