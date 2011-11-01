<?= form_open_multipart('picture/test') ?>

<input type="file" name="pic" id="pic_upload_input"/>

<input type="submit" value="Upload"/>

<?= form_close() ?>

<?php $pics = XObject::load_objects('XPicture', 'SELECT id FROM pictures'); ?>
