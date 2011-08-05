<?= 
  load_view('where_friends_went_view',
             array(
               'date' => college()->today(TRUE),
               'past_link' => TRUE,
             ))
?>
