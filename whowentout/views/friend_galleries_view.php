<?php foreach ($user->where_friends_went($date) as $party_id => $friend_ids): ?>
        
<?php
    $party = party($party_id);
    $count = count($friend_ids);
    ?>
        
<div class="friends_at_party party" data-party-id="<?= $party->id ?>" data-count="<?= $count ?>">
    <h2><?= $party->place->name ?> (<?= $count ?>)</h2>
    <ul class="user_list">
        <?php foreach ($friend_ids as $friend_id): ?>
        <li class="user">
            <div class="user_thumb">
                <?= user($friend_id)->thumb ?>
            </div>
            <div class="user_name">
                <div><?= user($friend_id)->first_name ?></div>
                <div><?= user($friend_id)->last_name ?></div>
            </div>
        </li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endforeach ?>
