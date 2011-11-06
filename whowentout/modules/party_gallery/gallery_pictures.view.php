<ul>
    <?php foreach ($gallery->pictures() as $pic): ?>
    <li>
        <a class="view_picture" href="<?= $pic->url('large') ?>" style="display: block;">
            <?= img($pic->url('thumb')) ?>
        </a>
    </li>
    <?php endforeach; ?>
</ul>
