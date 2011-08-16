
<?= load_section_view('invite_to_party_view') ?>

<?= load_section_view('gallery_view', date("l, F jS", strtotime($party->date)) . ' | ' . $party->place->name); ?>

