<dl id="benchmarks_view">
    <?php $ci =& get_instance(); ?>
    
    <dt>Party Attendees</dt>
    <dd><?= $ci->benchmark->elapsed_time('party_attendees_start', 'party_attendees_end') ?></dd>
    
    <dt>Page Content</dt>
    <dd><?= $ci->benchmark->elapsed_time('page_content_start', 'page_content_end') ?></dd>
    
</dl>
    