<dl id="benchmarks_view">
    
    <dt>Party Attendees</dt>
    <dd><?= $this->benchmark->elapsed_time('party_attendees_start', 'party_attendees_end') ?></dd>
    
    <dt>Page Content</dt>
    <dd><?= $this->benchmark->elapsed_time('page_content_start', 'page_content_end') ?></dd>
    
</dl>