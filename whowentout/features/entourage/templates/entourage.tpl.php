<div class="profile">
    <div class="profile_inner">

        <?php if (count($received_entourage_requests) > 0): ?>
        <section class="entourage_incoming_requests gallery">
            <h3>Entourage Requests Received</h3>
            <?= r::entourage_incoming_requests(); ?>
        </section>
        <?php endif; ?>

        <section class="entourage gallery">
            <h3>
                <span>Entourage</span>
                <a class="entourage_request_link action" href="/entourage/invite">Send Entourage Request</a>
            </h3>
            <?= r::profile_gallery(array('users' => $entourage, 'preset' => 'thumb', 'link_to_profile' => true)) ?>
        </section>

        <?php if (count($sent_entourage_requests) > 0): ?>
        <section class="entourage_outgoing_requests gallery">
            <h3>Entourage Requests Sent</h3>
            <?= r::entourage_outgoing_requests(); ?>
        </section>
        <?php endif; ?>

    </div>
</div>
