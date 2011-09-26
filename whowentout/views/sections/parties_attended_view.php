<?php
 $yesterday = college()->yesterday(TRUE)
?>

<div class="parties_attended">

    <div class="checkin_box"></div>
    
    <?= load_view('forms/checkin_form') ?>

    <?php foreach ( current_user()->recently_attended_parties() as $party ): ?>
        
        <?= load_view('party_summary_view', array(
                                              'user' => $user,
                                              'party' => $party,
                                            )); ?>
    <?php endforeach; ?>
    
</div>
