<ul class="parties_attended">
    <?php foreach ($party_groups as $party_group): ?>
    <li>
        <?= r('party_group', array(
                                  'party_group' => $party_group,
                                  'user' => $user,
                             )) ?>
    </li>
    <?php endforeach; ?>
</ul>
