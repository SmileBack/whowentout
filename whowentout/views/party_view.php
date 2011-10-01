<?php
if ($party->chat_is_open() && $party->smiling_is_open()) {
    $description = 'Check out the WhoWentOut smile concept, and chat with users who are online!';
}
elseif ($party->chat_is_open()) {
    $description = 'Chat with users who are online! Smiles have closed for this party.';
}
elseif ($party->smiling_is_open()) {
    $description = 'Check out the WhoWentOut smile concept. Chat has closed for this party.';
}
else {
    $description = 'Chat and smiles have closed for this party';
}
?>

<?= load_section_view('gallery_view',
                      date("l, M. jS", strtotime($party->date)) . ' | ' . $party->place->name . ' Gallery',
                      array(
                          'description' => $description,
                      ))?>

<fieldset class="party_invite_section">

    <legend>
        <h1>Invite someone to check in!</h1>
    </legend>

    <?= form_open('party/invite', array('id' => 'party_invite_form')) ?>

    <p>Did you see someone at the party last night who isn't here? We can send them an email reminding them to check in!</p>
    <p>(Your identity will not show up in the email)</p>

    <a id="invite_to_party"></a>

    <label>Type their name here:</label>

    <div>
        <input class="name autocomplete" type="text" name="name" source="/college/students" extra_class="name"/>
        <input type="hidden" name="party_id" value="<?= $party->id ?>" />
        <input class="submit_button" type="Submit"/>
    </div>

    <?= form_close() ?>
    
</fieldset>