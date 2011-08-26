<div id="wwo" style="display: none;"
     current-time="<?= current_time()->getTimestamp() ?>"
     doors-closing-time="<?= college()->get_closing_time()->getTimestamp() ?>"
     doors-opening-time="<?= college()->get_opening_time()->getTimestamp() ?>"
     yesterday-time="<?= college()->yesterday()->getTimestamp() ?>"
     tomorrow-time="<?= college()->tomorrow()->getTimestamp() ?>"
     doors-open="<?= college()->doors_are_open() ? 'true' : 'false' ?>"
     current-user-id="<?= logged_in() ? '' : current_user()->id ?>"
     gender="<?= logged_in() ? current_user()->gender : '' ?>"
     other-gender="<?= logged_in() ? current_user()->other_gender : '' ?>"
 >
</div>
