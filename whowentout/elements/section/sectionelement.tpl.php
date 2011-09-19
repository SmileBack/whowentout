<section class="<?= $id ?>">
  
  <?php if ($title): ?>
    <h1><?= $title ?></h1>

    <?php if (isset($vars['description'])): ?>
    <h3><?= $vars['description'] ?></h3>
    <?php endif; ?>
        
  <?php endif; ?>
    
  <div class="section_body">
    <?= $body ?>
  </div>
    
</section>