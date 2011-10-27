<section class="<?= isset($class) ? $class : '' ?>">

  <?php if ($title): ?>
    <h1><?= $title ?></h1>

    <?php if (isset($description)): ?>
    <h3>
        <?= $description ?>
    </h3>
    <?php endif; ?>

  <?php endif; ?>

  <div class="section_body">
    <?= $body ?>
  </div>

</section>
    
