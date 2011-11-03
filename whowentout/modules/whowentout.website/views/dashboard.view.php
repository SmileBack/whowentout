<?=
r('section', array(
                  'title' => "My Parties",
                  'class' => 'parties_attended_view',
                  'body' => r('parties_attended', array(
                                                       'user' => current_user(),
                                                       'smile_engine' => $smile_engine,
                                                  ))
             ))
?>


