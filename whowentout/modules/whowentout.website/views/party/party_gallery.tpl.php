<?php
/* @var $party XParty */
?>

<?=
r('party_notices', array(
                        'user' => $user,
                        'party' => $party,
                        'smile_engine' => $smile_engine,
                   )) ?>

<?php if ($party->has_photo_gallery()): ?>
    <?= r('party_photos_teaser', array('party' => $party)) ?>
<?php endif; ?>

<?php if ($user->is_admin()): ?>
    <?= anchor("party/admin/$party->id", "Party Admin", array('class' => 'admin_link')) ?>
<?php endif; ?>

<div class="gallery_header_top" style="width: 100%; float: left; border-bottom: 1px solid #cdcdcd; margin-top: 8px;"></div>

<div class="sortbar">
    <h3>Sort by:</h3>
    <ul>
        <li class="sort_checkin_time <?= $sort == 'checkin_time' ? 'selected' : '' ?>">
            <?= anchor("party/$party->id?sort=checkin_time", "Most Recent") ?>
        </li>
        <li class="sort_first_name <?= $sort == 'name' ? 'selected' : '' ?>">
            <?= anchor("party/$party->id?sort=name", "Name") ?>
        </li>
        <li class="sort_gender <?= $sort == 'gender' ? 'selected' : '' ?>">
            <?= anchor("party/$party->id?sort=gender", "Gender") ?>
        </li>
    </ul>
</div>

<h2 class="gallery_header">
    <span><?= $party->place->name ?> Attendees</span>
    <span>(<?= $party->date->format('l, M. jS') ?>)</span>
</h2>

<div class="gallery party <?= $party->chat_is_open() ? 'chat_open' : 'chat_closed' ?>"
     data-sort="<?= $sort ?>" data-party-id="<?= $party->id ?>" data-count="<?= $party->count ?>"
     data-smiles-left="<?= $smiles_left ?>"
     party-chat-is-open="<?= $party->chat_is_open() ? 'y' : 'n' ?>"
     party-chat-close-time="<?= $party->chat_close_time()->getTimestamp() ?>">
    <ul>
        <?php foreach ($party_attendees as $key => $attendee): ?>
        <li>
            <?=
            r('party_attendee', array(
                                     'logged_in_user' => $user,
                                     'party' => $party,
                                     'attendee' => $attendee,
                                     'smile_engine' => $smile_engine,
                                ))
            ?>
        </li>
        <?php endforeach; ?>
    </ul>

</div>
