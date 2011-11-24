<div class="event">
    <h2>Blacklight Party at Eden with DJ Shah</h2>

    <div class="body">
        <div class="description">Come join us at this exclusive 18+ event. Blah blah blah.</div>
        <nav class="links">
            <a href="/">Attend</a>
            <a href="/">Reserve Table</a>
            <?php if ($role == 'promoter'): ?>
                <a href="/events/invite">Invite</a>
            <?php endif; ?>
        </nav>
    </div>

    <a class="more_info" href="/">
        More Info
    </a>

    <?php if ($role == 'admin'): ?>
        <a href="/" class="admin_link">Admin</a>
    <?php elseif ($role == 'promoter'): ?>
        <a href="/events/guestlist" class="admin_link">My Guestlist</a>
    <?php endif; ?>

</div>