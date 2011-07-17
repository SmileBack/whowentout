<div id="wwo" style="display: none;"
     current-time="<?= current_time()->getTimestamp() ?>"
     doors-closing-time="<?= get_closing_time()->getTimestamp() ?>"
     doors-opening-time="<?= get_opening_time()->getTimestamp() ?>"
     doors-open="<?= doors_are_open() ? 'true' : 'false' ?>">
  
  <div class="where-friends-went-data"><?= json_encode(where_friends_went_pie_chart_data()) ?></div>
  
</div>