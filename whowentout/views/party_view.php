<?= load_section_view('gallery_view', date("l, M. jS", strtotime($party->date)) . ' | ' . $party->place->name) ?>

<section>
    <div class="section_body">
        <?= form_open('party/invite', array('id' => 'party_invite_form')) ?>

        <a id="invite_to_party"></a>

        <label>Type their name here:</label>

        <div>
            <input class="name autocomplete" type="text" name="name" source="/college/students" extra_class="name"/>
            <input class="submit_button" type="Submit"/>
        </div>

        <?= form_close() ?>
    </div>
</section>
    