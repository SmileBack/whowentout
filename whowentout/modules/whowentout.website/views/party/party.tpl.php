<?php
if (!$party->chat_is_open() && !$party->smiling_is_open()) {
    $description = 'Chat and smiles have closed for this party';
}
else {
    $description = NULL;
}
?>

<?=
r('section', array(
                  'title' => $party->date->format('l, M. jS') . ' | ' . strip_tags($party->place->name) . ' Gallery',
                  'description' => $description,
                  'class' => 'gallery_view',
                  'body' => r('party_gallery', array(
                                              'user' => current_user(),
                                              'party' => $party,
                                              'smile_engine' => $smile_engine,
                                              'sort' => $sort,
                                              'smiles_left' => $smiles_left,
                                              'party_attendees' => $party_attendees,
                                         )),
             )) ?>

<?php if (FALSE): ?>
<fieldset class="party_invite_section">

    <legend>
        <h1>Invite someone to check in!</h1>
    </legend>

    <?= form_open('party/invite', array('id' => 'party_invite_form')) ?>

    <p>Type their name below and we'll send them an e-mail to check in.</p>

    <p>(Your name  will not show up in the email)</p>

    <a id="invite_to_party"></a>

    <label>Type their name here:</label>

    <div>
        <input class="name autocomplete" type="text" name="name" source="/college/students" extra_class="name"/>
        <input type="hidden" name="party_id" value="<?= $party->id ?>"/>
        <input class="submit_button" type="Submit"/>
    </div>

    <?= form_close() ?>

</fieldset>
<?php endif; ?>
