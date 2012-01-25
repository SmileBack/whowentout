<div class="deal_preview <?= $orientation ?>">
    <img class="phone" alt="sample deal" src="/images/iphone_blank.png"/>
    <img class="ticket" src="<?= $ticket_url ?>"/>
    <?php if (!browser::is_mobile()): ?>
    <?= a('profile/picture/edit', 'change', array('class' => 'edit_pic_link')) ?>
    <?php endif; ?>
</div>
