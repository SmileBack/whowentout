<?=
load_view('party_notices_view', array(
                                     'user' => $user,
                                     'party' => $party,
                                     'smile_engine' => $smile_engine,
                                )) ?>

    <div class="visibilitybar">
        <?php if ($party->chat_is_open()): ?>
        <h3>
            <span>Online Visibility?</span>
            <a class="who_can_chat help">?</a>
        </h3>
        <div class="links">
            <a href="online" class="js">Online</a>
            <a href="offline" class="js">Offline</a>
        </div>
        <?php else: ?>
        <h3>
            <span>Chat has closed for this party</span>
            <a class="chat_has_closed help">?</a>
        </h3>
        <?php endif; ?>
    </div>

    <div class="invite_to_party_box">
        <span>Invite someone to check in.</span>
        <a href="#invite_to_party" class="scroll">Click Here</a>
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

<div class="gallery party <?= $party->chat_is_open() ? 'chat_open' : 'chat_closed' ?>"
     data-sort="<?= $sort ?>" data-party-id="<?= $party->id ?>" data-count="<?= $party->count ?>"
     data-smiles-left="<?= $smiles_left ?>"
     party-chat-is-open="<?= $party->chat_is_open() ? 'y' : 'n' ?>"
     party-chat-close-time="<?= $party->chat_close_time()->getTimestamp() ?>">
    <ul>
        <?php foreach ($party_attendees as $key => $attendee): ?>
        <li>
            <?=
            load_view('party_attendee_view', array(
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
