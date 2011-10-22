<?php $date = new DateTime($party->date, $party->college->timezone); ?>

<div class="party_summary"
     id="<?= 'party_summary_' . $party->id ?>"
     data-party-date="<?= $party->date ?>">
    
    <h2>
        <a href="<?= "party/$party->id" ?>">
            <?= $party->college->format_time($date) ?> &nbsp;|&nbsp;  <?= $party->place->name ?> Gallery
        </a>
    </h2>

    <div class="badge">attended</div>

    <div class="body">
        
        <div class="left">
            <?= load_view('recent_attendees_view', array('party' => $party, 'count' => 4)) ?>
        </div>

        <div class="right">
            <?=
                load_view('party_notices_view', array(
                                                     'user' => $user,
                                                     'party' => $party,
                                                     'smile_engine' => $smile_engine,
                                                )) ?>

            <?= form_open("party/$party->id", array('class' => 'see_party_gallery')) ?>
            <input type="submit" class="submit_button" value="Go To Party Gallery" />
            <?= form_close() ?>
        </div>

    </div>
    
</div>
    