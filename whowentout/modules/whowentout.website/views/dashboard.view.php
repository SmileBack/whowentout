<?= r('section', array(
                   'title' => 'My Parties',
                   'body' => '',
                 )) ?>

<?php if (FALSE): ?>
<div class="dashboard_message notice_style" style="margin-top: 20px;">
    <h2>Check-ins have closed for this weekend.</h2>
    <h2>Check-ins will re-open on Thursday for next weekend's parties!</h2>
</div>
<?php endif; ?>

<?=
r('parties_attended', array(
                           'user' => current_user(),
                           'smile_engine' => $smile_engine,
                      ))
?>

