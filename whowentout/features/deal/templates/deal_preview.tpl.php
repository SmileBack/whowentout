<div class="deal_preview <?= $orientation ?>">
    <img class="phone" alt="sample deal" src="/images/iphone_blank.png"/>
    <?= r::deal_image(array('event' => $event, 'user' => $user)) ?>
    <?php if (!browser::is_mobile()): ?>
    <?= a('profile/picture/edit', 'change pic', array('class' => 'edit_pic_link')) ?>
    <?php endif; ?>
</div>
