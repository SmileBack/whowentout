<?php
/* @var $entourage_engine EntourageEngine */
$entourage_engine = build('entourage_engine');
$n = $entourage_engine->get_entourage_count(auth()->current_user());
?>

<?php if (auth()->logged_in()): ?>
    <?= a_open('entourage', array('class' => 'entourage_link')); ?>
        <?= "My Entourage ($n)" ?>

        <div class="tab_tip_wrapper">
            <div class="tab_tip">
                <div class="tip_arrow"></div>
                <div class="tip_content">
                    2 new requests
                </div>
            </div>
        </div>

    <?= a_close(); ?>
<?php endif; ?>
