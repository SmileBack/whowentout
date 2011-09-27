<?= load_section_view('parties_attended_view', "Parties", array(
                                                 'description' => '<p class="user_command_notice"></p>',
                                                          )) ?>

<section>
    <div class="section_body">
        <?= load_view('upcoming_party_view') ?>
    </div>
</section>
    