<?php
if (!function_exists('get_user_networks')) {
    function get_user_networks($user)
    {
        $networks = array();
        $query = db()->query_statement('SELECT * FROM user_networks WHERE user_id = :id', array('id' => $user->id));
        $query->execute();
        foreach ($query->fetchAll(PDO::FETCH_OBJ) as $row) {
            $networks[] = db()->table('networks')->row($row->network_id);
        }
        return $networks;
    }
}
?>
<ul class="profile_networks">
    <?php foreach (get_user_networks($user) as $network): ?>
    <li><?= $network->name ?></li>
    <?php endforeach; ?>
</ul>
