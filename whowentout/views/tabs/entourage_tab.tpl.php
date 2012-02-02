<?php if (auth()->logged_in()): ?>
    <?= a('entourage', 'My Entourage', array('class' => 'entourage_link')); ?>
<?php endif; ?>