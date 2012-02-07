<?php
/* @var $entourage_engine EntourageEngine */
$entourage_engine = build('entourage_engine');
$user = auth()->current_user();

$n = $entourage_engine->get_entourage_count($user);
$requests = $entourage_engine->get_pending_requests($user);
$num_requests = count($requests);
?>

<?php if (auth()->logged_in()): ?>
    <?= a_open('entourage', array('class' => 'entourage_link')); ?>

        <?= browser::is_desktop() ? "My Entourage ($n)" : "Entourage ($n)" ?>

        <?php if ($num_requests > 0): ?>
            <div class="tab_tip_wrapper">
                <div class="tab_tip">
                    <div class="tip_arrow"></div>
                    <div class="tip_content">
                       <?= Inflect::pluralize_if($num_requests, 'new request') ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    <?= a_close(); ?>
<?php endif; ?>
