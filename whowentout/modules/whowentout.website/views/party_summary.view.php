<?php $date = new DateTime($party->date, $party->college->timezone); ?>
<?php $open_parties = $party->college->open_parties($party->college->get_time()); ?>

<div class="party_summary"
     id="<?= 'party_summary_' . $party->id ?>"
     data-party-date="<?= $party->date ?>">

    <h2>
        <a href="<?= "party/$party->id" ?>">
            <?= $party->college->format_time($date) ?> &nbsp;|&nbsp;  <?= $party->place->name ?>
        </a>
    </h2>

    <div class="badge">
        <?php if ($party->college->get_door()->is_open() && in_array($party, $open_parties)): ?>
        Check-ins will close at <?= $party->college->get_door()->get_closing_time()->format('g a') ?>
        <?php else: ?>
        Check-ins have closed
        <?php endif; ?>
    </div>

    <div class="body">

        <div class="left">
            <?= r('recent_attendees', array('party' => $party, 'count' => 4)) ?>

            <?= form_open("party/$party->id", array('class' => 'see_party_gallery')) ?>
            <input type="submit" class="submit_button" value="Go To Party Gallery"/>
            <?= form_close() ?>

        </div>

        <div class="right">
            <?=
            r('party_notices', array(
                                    'user' => $user,
                                    'party' => $party,
                                    'smile_engine' => $smile_engine,
                               )) ?>
        </div>

    </div>

</div>
    