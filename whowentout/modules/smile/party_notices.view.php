<div class="party_notices" for="<?= $party->id ?>">
    <?php $smiles_left = $smile_engine->get_num_smiles_left_to_give($user, $party); ?>
    <h3 class="smiles_left_heading">Smiles Left to Give (<?= $smiles_left ?>)</h3>

    <div>
        <?php if ($smiles_left == 0): ?>
            -
        <?php else: ?>
            <?php for ($n = 0; $n < $smiles_left; $n++): ?>
            <img src="/assets/images/smiley_icon.png"/>
            <?php endfor; ?>
        <?php endif; ?>
    </div>

    <?php $smiles_received = $smile_engine->get_smiles_received_for_user($user, $party); ?>
    <h3 class="smiles_received_heading">Smiles Received (<?= count($smiles_received) ?>)</h3>

    <?php foreach ($smiles_received AS $smile): ?>
    <div class="smile_sender">
        <img src="/assets/images/anonymous.png" />
        <?php if ($smile->match): ?>
            <div class="smile_sender_name"><?= $smile->sender->full_name ?></div>
        <?php else: ?>
            <div class="smile_sender_name">?</div>
        <?php endif; ?>
    </div>
    <?php endforeach; ?>

    <p class="whats_a_smile">
        <a href="/smilehelp" class="smile_help_link">What is a smile?</a>
    </p>
    
</div>
    