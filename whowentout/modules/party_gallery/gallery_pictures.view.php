<?= form_open_multipart("gallery/upload/{$gallery->id}") ?>
    <input type="file" name="pic" />
    <input type="submit" value="Upload"/>
<?= form_close() ?>

<ul>
    <?php foreach ($gallery->pictures() as $pic): ?>
    <li>
        <a class="view_picture" href="<?= $pic->url('large') ?>" style="display: block;">
            <?= img($pic->url('thumb')) ?>
        </a>
    </li>
    <?php endforeach; ?>
</ul>
