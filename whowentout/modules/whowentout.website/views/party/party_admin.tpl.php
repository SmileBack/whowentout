<?php
/* @var $party XParty */
?>
<h1><?= anchor("party/$party->id", "Back to Party Gallery") ?></h1>
<section>
    <h1>Flickr Gallery ID</h1>
    <div class="section_body">
        <?= form_open('party/attach_gallery') ?>

        <?= form_input('flickr_gallery_id', $party->flickr_gallery_id) ?>
        <?= form_hidden('party_id', $party->id) ?>

        <?= form_submit('op', 'Save') ?>

        <?= form_close() ?>
    </div>
</section>

<section>
    <h1>Checkin User To Party</h1>
    <div class="section_body">
        <?= form_open('party/admin_checkin_add') ?>

        <?= form_hidden('party_id', $party->id) ?>

        <?= form_input('user_id') ?>

        <?= form_submit('op', 'Checkin') ?>
        
        <?= form_close() ?>
    </div>
</section>

<section>
    <h1>Remove User From Party</h1>
    <div class="section_body">
        <?= form_open('party/admin_checkin_remove') ?>

        <?= form_hidden('party_id', $party->id) ?>

        <?= form_input('user_id') ?>

        <?= form_submit('op', 'Remove') ?>

        <?= form_close() ?>
    </div>
</section>

<?php $smile_engine = new SmileEngine(); ?>
<section>
    <h1>Smiles</h1>
    <div class="section_body">
        <table>
            <tr>
                <th>ID</th>
                <th>Pic</th>
                <th>User</th>
                <th>Smiles Received</th>
            </tr>
            <?php foreach ($party->attendees() as $attendee): ?>
                <tr>
                    <td><?= $attendee->id ?></td>
                    <td>
                        <?php $profile_picture = new UserProfilePicture($attendee); ?>
                        <?= $profile_picture->img('thumb') ?>
                    </td>
                    <td><?= $attendee->full_name ?></td>
                    <td><?= $smile_engine->get_num_overall_smiles_received($attendee) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</section>

<section>
    <h1>Emails</h1>
    <div class="section_body">
        <pre>
        <?php foreach ($party->attendees() as $attendee): ?>
            <?= $attendee->email . "\n" ?>
        <?php endforeach; ?>
        </pre>
    </div>
</section>
