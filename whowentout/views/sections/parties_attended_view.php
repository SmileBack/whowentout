<?php
 $yesterday = college()->yesterday(TRUE)
?>

<?php if (empty($parties_attended)): ?>
<h2>The parties you checkin to will appear here</h2>
<?php else: ?>
<div class="parties_attended">

    <?= load_view('forms/checkin_form') ?>

    <?php foreach ( current_user()->recently_attended_parties() as $party ): ?>
        
        <?= load_view('party_summary_view', array(
                                              'user' => $user,
                                              'party' => $party,
                                            )); ?>

    <?php endforeach; ?>
</div>
<?php endif; ?>
