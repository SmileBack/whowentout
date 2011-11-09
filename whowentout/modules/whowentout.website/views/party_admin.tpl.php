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
