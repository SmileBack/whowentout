<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once '../fire/core/boot.php';
boot();

// edit pic flow
$flow = new Flow();

$flow->connect('START', 'EditPicDialog');

$flow->connect('EditPicDialog', 'CropPic', 'click_save');

$flow->connect('EditPicDialog', 'UseFacebookPic', 'click_use_facebook');
$flow->connect('UseFacebookPic', 'EditPicDialog');

$flow->connect('EditPicDialog', 'UploadPic', 'click_upload_pic');
$flow->connect('UploadPic', 'EditPicDialog');

$flow->connect('CropPic', 'END');

// deal flow
$flow = new Flow();

$flow->connect('START', 'DealDialog', 'event_has_deal');

$flow->connect('DealDialog', 'DealConfirm', 'click_save');
$flow->connect('DealConfirm', 'END');

$flow->connect('START', 'END', 'event_has_no_deal');

// invite flow
$flow = new Flow();

$flow->connect('START', 'InviteDialog', 'has_not_sent_invites');
$flow->connect('InviteDialog', 'SendInvites', 'click_save');
$flow->connect('SendInvites', 'END');

$flow->connect('START', 'END', 'has_sent_invites');

// checkin flow
$flow = new Flow();

$flow->connect('START', 'Checkin');
$flow->connect('Checkin', 'END');

?>