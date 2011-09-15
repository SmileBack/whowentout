
<?= load_section_view('gallery_view', date("l, M. jS", strtotime($party->date)) . ' | ' . $party->place->name); ?>

<?= load_section_view('invite_to_party_view') ?>
