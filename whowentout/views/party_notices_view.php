<div class="party_notices" for="<?= $party->id ?>">
    <p class="smiles_left link_to_party">
        <span>Smiles left to give (at this party): </span>
        <span class="count"><?= $user->smiles_left($party) ?></span>
    </p>

    <p class="smiles_received link_to_party">
        <span>Smiles received (at this party): </span>
        <span class="count"><?= $user->smiles_received($party) ?></span>
    </p>

    <?php $matches = $user->matches($party); ?>
    <ul class="smile_matches <?= empty($matches) ? 'empty' : '' ?> link_to_party">
        <?php if (empty($matches)): ?>
            <li>
                <span>
                    <?= ucfirst($user->other_gender_word) . 's' ?> you have reciprocal smiles with:
                </span>
                <span class="count">none</span>
            </li>
        <?php else: ?>
            <? foreach ($user->matches($party) as $match): ?>
            <li>
                You and <span class="count"><?= $match->other_user($user)->abbreviated_name ?></span> smiled at each other!
                <?= $match->other_user($user)->anchor_facebook_message() ?>
            </li>
            <? endforeach; ?>
        <?php endif; ?>
    </ul>

    <p class="whats_a_smile">
        <a href="/smilehelp" class="smile_help_link">What is a smile?</a>
    </p>
</div>
    