<div class="party_notices" for="<?= $party->id ?>">
    <p class="smiles_left">
        <span><?= $user->smiles_left_message($party->id) ?></span>
    </p>

    <p class="smiles_received">
        <span><?= $user->smiles_received_message($party->id) ?></span>
    </p>

    <?php $matches = $user->matches($party); ?>
    <ul class="smile_matches <?= empty($matches) ? 'empty' : '' ?>">
        <?php if (empty($matches)): ?>
            <li>
                <span>Any mutual smiles will appear here</span>
                <a class="mutual_smiles_help help">?</a>
            </li>
        <?php else: ?>
            <? foreach ($user->matches($party) as $match): ?>
            <li>
                You and <?= $match->other_user($user)->abbreviated_name ?> smiled at each other!
                <?= $match->other_user($user)->anchor_facebook_message() ?>
            </li>
            <? endforeach; ?>
        <?php endif; ?>
    </ul>

    <p class="whats_a_smile">
        <a href="/smilehelp">What is a smile?</a>
    </p>
</div>
    