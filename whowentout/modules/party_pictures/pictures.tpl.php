<ul class="gallery_pictures">
    <?php foreach ($gallery->pictures() as $pic): ?>
    <li>
        <a class="thumbnail" href="<?= $pic->url('large') ?>" style="display: block;">
            <?= img($pic->url('thumb')) ?>
        </a>
    </li>
    <?php endforeach; ?>
</ul>