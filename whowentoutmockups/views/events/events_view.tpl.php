<div id="dates">
    <a href="/" class="prev">Prev</a>

    <a href="/" class="active">Today</a>
    <a href="/">Fri, Nov 25</a>
    <a href="/">Sat, Nov 26</a>
    <a href="/">Sun, Nov 27</a>

    <a href="/" class="next">Next</a>
</div>

<?php if ($role == 'admin'): ?>
<a class="create_event" href="/events/create">Create Event</a>
<?php endif; ?>

<ul class="event_list">
    <li>
        <?= r('event', array('role' => $role)) ?>
    </li>
    <li>
        <?= r('event', array('role' => 'user')) ?>
    </li>
    <li>
        <?= r('event', array('role' => 'user')) ?>
    </li>
    <li>
        <?= r('event', array('role' => 'user')) ?>
    </li>
    <li>
        <?= r('event', array('role' => 'user')) ?>
    </li>
    <li>
        <?= r('event', array('role' => 'user')) ?>
    </li>
</ul>
    