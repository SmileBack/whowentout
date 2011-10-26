<div class="party_notices" for="<?= $party->id ?>">
    <?php $smiles_left = $smile_engine->get_num_smiles_left_to_give($user, $party); ?>
    <h2>Smiles Left to Give (<?= $smiles_left ?>)</h2>
    <div class="count">
        <?php for ($n = 0; $n < $smiles_left; $n++): ?>
        <img src="/assets/images/smiley_icon.png" />
        <?php endfor; ?>
    </div>

    <?php $num_smiles_received = $smile_engine->get_num_smiles_received($user, $party) ?>
    <h2>Smiles Received (<?= $num_smiles_received ?>)</h2>
    <div class="count">
        <?php for ($n = 0; $n < $num_smiles_received; $n++): ?>
        <div class="smile_received">
            <div>
                <span>silhouette</span>
                <span>?</span>
            </div>
        </div>
        <?php endfor; ?>
    </div>

    <?php $matches = $smile_engine->get_smile_matches_for_user($user, $party); ?>
    <ul class="smile_matches <?= empty($matches) ? 'empty' : '' ?> link_to_party">
        <?php if (empty($matches)): ?>
        <li>
                <span>
                    <?= ucfirst($user->other_gender_word) . 's' ?> you have reciprocal smiles with:
                </span>
            <span class="count">none</span>
        </li>
        <?php else: ?>
        <? foreach ($matches as $match_user): ?>
            <li>
                You and <span class="count"><?= $match_user->abbreviated_name ?></span> smiled at each other!
                <?= $match_user->anchor_facebook_message() ?>
            </li>
            <? endforeach; ?>
        <?php endif; ?>
    </ul>

    <p class="whats_a_smile">
        <a href="/smilehelp" class="smile_help_link">What is a smile?</a>
    </p>
</div>
    