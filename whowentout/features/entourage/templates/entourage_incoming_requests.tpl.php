<ul class="entourage_requests">
   <?php foreach ($requests as $request): ?>
        <li>
            <?=
            r::profile_small(array(
                'user' => $request->sender,
                'show_networks' => false,
                'link_to_profile' => true,
            ))
            ?>
            <form class="entourage_request_accept" method="post" action="/entourage/accept">
                <input type="hidden" name="request_id" value="<?= $request->id ?>" />
                <input type="submit" name="op" class="accept" value="accept" />
                <input type="submit" name="op" class="ignore" value="ignore" />
            </form>
        </li>
   <?php endforeach; ?>
</ul>
