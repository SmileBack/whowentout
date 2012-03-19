<div class="event_day">

   <?= html_element_open('div', array('class' => 'event_picker render'), $data); ?>
   <?= html_element_close('div') ?>

    <?= r::event_day_summary(array(
        'user' => $current_user,
        'date' => $date,
    )) ?>

</div>
