<section>
  <h1>Debug</h1>
  <div class="section_content">
    <pre>
      <?= session_id() ?>
    </pre>
    <pre>
      <?php var_dump($_SESSION) ?>
    </pre>
  </div>
</section>

<?= load_section_view('last_night_view', 'Where Did You Go Out Last Night?'); ?>

<?= load_section_view('my_info_view', 'My Info'); ?>

<?= load_section_view('parties_attended_view', "Parties I've Attended"); ?>

<?= load_section_view('friends_view', "Where Did Your Friends Go Out?") ?>

<?= load_section_view('top_parties_view', "Top Parties"); ?>

<?= load_section_view('upcoming_parties_view', "Upcoming Parties on WhoWentOut"); ?>
