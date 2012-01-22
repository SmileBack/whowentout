<?php if ($event && $event->deal): ?>
    <?= a("events/$event->id/deal", "View Your Deal", array('class' => 'action show_deal_link')) ?>
<?php endif; ?>