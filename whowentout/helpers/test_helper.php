<?php

/**
 * @return XUser
 */
function get_random_user($party_id) {
  $party = XParty::get($party_id);
  $query = "SELECT id, first_name, last_name FROM users
              WHERE gender = 'F'
              ORDER BY RAND()
              LIMIT 1";
  $rows = ci()->db->query($query)->result();
  return XUser::get($rows[0]->id);
}
