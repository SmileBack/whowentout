<?=
r('party_notices', array(
                        'user' => $user,
                        'party' => $party,
                        'smile_engine' => $smile_engine,
                   )) ?>

<div class="visibilitybar">
    <?php if ($party->chat_is_open()): ?>
    <h3>
        <span>Chat Availability:</span>
    </h3>
    <div class="links">
        <label>
            <input type="radio" value="online" name="chat_visibility"/> <span>Online</span>
        </label>
        <label>
            <input type="radio" value="offline" name="chat_visibility"/> <span>Offline</span>
        </label>
    </div>
    <?php else: ?>
    <h3>
        <span>Chat has closed for this party</span>
        <a class="chat_has_closed help">?</a>
    </h3>
    <?php endif; ?>
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
