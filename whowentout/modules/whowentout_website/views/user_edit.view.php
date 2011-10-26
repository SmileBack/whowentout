<?= r('section', array(
                   'title' => 'Edit Info',
                   'class' => 'my_info_edit_view',
                   'body' => r('my_info_edit', array(
                                                 'user' => current_user(),
                                                 'missing_info' => $missing_info,
                                               )),
                 )) ?>
