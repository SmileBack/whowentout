<div class="party_notices" for="<?= $party->id ?>">
    <h2 class="smiles_title">Smiles</h2>

    <div class="smiles_left">
        <?php $smiles_left = $smile_engine->get_num_smiles_left_to_give($user, $party); ?>
        <h3>Left to Give (<?= $smiles_left ?>)</h3>

        <div>
            <?php if ($smiles_left == 0): ?>
                -
            <?php else: ?>
                <?php for ($n = 0; $n < $smiles_left; $n++): ?>
                <img src="/assets/images/smiley_22.png"/>
                <?php endfor; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="smiles_received">
    <?php $smiles_received = $smile_engine->get_smiles_received_for_user($user, $party); ?>
    <h3>Received (<?= count($smiles_received) ?>)</h3>

    <?php foreach ($smiles_received AS $smile): ?>
            
    <div class="smile_sender">
        <img src="/assets/images/anonymous.png" />
        <?php if ($smile->match): ?>
            <div class="smile_sender_name"><?= $smile->sender->full_name ?></div>
            <?= $smile->sender->anchor_facebook_message() ?>
        <?php else: ?>
            <div class="smile_sender_name">?</div>
        <?php endif; ?>
    </div>
    <?php endforeach; ?>
        
    </div>

    <p class="whats_a_smile">
        <a href="/smilehelp" class="smile_help_link">What is a smile?</a>
    </p>
    
</div>
    