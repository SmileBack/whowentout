<?php if (count($received_entourage_requests) > 0): ?>
<section class="entourage_incoming_requests gallery">
    <h3>Entourage Requests Received</h3>
    <?= r::entourage_incoming_requests(); ?>
</section>
<?php endif; ?>