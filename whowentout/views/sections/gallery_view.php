<?=
load_view('party_notices_view', array(
                                     'user' => $user,
                                     'party' => $party,
                                )) ?>

    <div class="visibilitybar">
        <?php if ($party->chat_is_open()): ?>
        <h3>
            <span>Who Can Chat With You?</span>
            <a class="who_can_chat help">?</a>
        </h3>
        <div class="links">
            <a href="everyone" class="js">Everyone</a>
            <a href="friends" class="js">Facebook Friends</a>
            <a href="none" class="js">Nobody</a>
        </div>
        <?php else: ?>
        <p>Chat has closed.</p>
        <?php endif; ?>
    </div>

    <div class="invite_box">
        <span>Send an anonymous invite!</span>
        <a href="#invite_to_party" class="scroll">Click here</a>
    </div>

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
    <span>(<?= date("l, M. jS", strtotime($party->date)) ?>)</span>
</h2>

<div class="gallery party"
     data-sort="<?= $sort ?>" data-party-id="<?= $party->id ?>" data-count="<?= $party->count ?>"
     data-smiles-left="<?= $smiles_left ?>"
     party-chat-is-open="<?= $party->chat_is_open() ? 'y' : 'n' ?>"
     party-chat-close-time="<?= $party->chat_close_time()->getTimestamp() ?>">
    <ul>
        <li>
            <div class="invite_picture">
            </div>
            <div class="invite_caption">
                <?= form_open('party/invite') ?>

                  <a id="invite_to_party"></a>
                  <label class="inlined">Type name here</label>
                  <input class="name autocomplete" value="Type name here" type="text" name="name" source="/college/students" extra_class="name" />
                  <input class="submit_button" type="submit" value="Send Invite" />

                <?= form_close() ?>
            </div>
        </li>
        <?php foreach ($party_attendees as $key => $attendee): ?>
        <li>
            <?=
            load_view('party_attendee_view', array(
                                                  'party' => $party,
                                                  'attendee' => $attendee,
                                                  'smiles_left' => $smiles_left,
                                             ))
            ?>
        </li>
        <?php endforeach; ?>
    </ul>

</div>

<?php if ($party->admin): ?>
    <p id="party_admin">
        <span>Hosted by:</span>
        <?= $party->admin->first_name; ?> <?= $party->admin->last_name; ?>
    </p>
<?php endif; ?>
