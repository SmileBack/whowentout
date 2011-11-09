<h1>
    <?= anchor("party/{$party->id}", "Go Back to {$party->place->name} Party Gallery") ?>
</h1>
    
<?=
r('section', array(
                  'title' => "Photos for {$party->place->name}",
                  'body' => r('pictures', array(
                                               'gallery' => $gallery,
                                          )),
             ))
?>
