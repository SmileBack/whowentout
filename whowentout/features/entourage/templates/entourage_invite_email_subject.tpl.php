<?= $request->sender->first_name ?> <?= $request->sender->last_name ?> wants you to join <?= Inflect::genderize($request->sender->gender, 'his') ?> entourage.
