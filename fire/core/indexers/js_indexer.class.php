<?php

require_once FIREPATH . 'core/dependency/topological_sort.class.php';

class JsIndexer extends Indexer
{

    public function run()
    {
        foreach ($this->get_js_file_resources() as $file_metadata) {
            $this->index_file($file_metadata);
        }

        $this->index_all_dependencies();
    }

    function index_file(FileMetadata $file_meta)
    {
        $js_meta = new JsMetadata();
        foreach (get_object_vars($file_meta) as $k => $v) {
            $js_meta->$k = $file_meta->$k;
        }

        $js_meta->type = 'js';
        $js_meta->direct_dependencies = $this->direct_dependencies($file_meta);
        $this->index->set_resource_metadata($js_meta->path, $js_meta);
    }

    private function index_all_dependencies()
    {
        $dependencies = array();
        /* @var $js_meta JsMetadata */
        foreach ($this->get_js_file_resources() as $js_meta) {
            $dependencies[$js_meta->filename] = $js_meta->direct_dependencies;
        }

        $topological_sort = new TopologicalSort($dependencies);
    }

    private function direct_dependencies(FileMetadata $file_meta)
    {
        $dependencies = array();

        $contents = file_get_contents($file_meta->filepath);

        $lines = explode("\n", $contents);
        foreach ($lines as $line) {
            if (string_starts_with('//= require ', $line)) {
                $dependencies[] = trim(string_after_first('//= require ', $line));
            }
        }

        return $dependencies;
    }

    /**
     * @return FileMetadata[]
     */
    private function get_js_file_resources()
    {
        $resources = array();
        /* @var $file_meta FileMetadata */
        foreach ($this->index->get_resources_of_type('file') as $file_meta) {
            if ($file_meta->extension == 'js')
                $resources[] = $file_meta;
        }
        return $resources;
    }

}
