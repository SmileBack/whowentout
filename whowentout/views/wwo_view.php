<div id="wwo" style="display: none;"
     current-time="<?= current_time()->getTimestamp() ?>"
     doors-closing-time="<?= college()->get_closing_time()->getTimestamp() ?>"
     doors-opening-time="<?= college()->get_opening_time()->getTimestamp() ?>"
     tomorrow-time="<?= college()->tomorrow()->getTimestamp() ?>"
     doors-open="<?= college()->doors_are_open() ? 'true' : 'false' ?>">
  
  <div class="where-friends-went-data"><?= json_encode(where_friends_went_pie_chart_data( college()->today() )) ?></div>
  
</div>