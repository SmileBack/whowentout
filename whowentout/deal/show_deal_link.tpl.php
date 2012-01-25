<?php if ($event && $event->deal): ?>
    <?= a("events/$event->id/deal", 'view deal', array('class' => 'action show_deal_link')) ?>
<?php endif; ?>