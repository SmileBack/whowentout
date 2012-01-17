<?php

class Asset
{

    /**
     * @var Index
     */
    private $index;

    /**
     * @var FileRepository
     */
    private $storage;

    private $js = array();

    function __construct(Index $index, FileRepository $storage)
    {
        $this->index = $index;
        $this->storage = $storage;
    }

    function load_js($js_name)
    {
        $this->js[] = $js_name;
    }

    function get_loaded_js()
    {
        $tree = $this->get_dependency_tree();
        $ts = new TopologicalSort($tree);
        return $ts->dependencies_of($this->js);
    }

    function get_dependency_tree()
    {
        $dependencies = array();
        /* @var $js_meta JsMetadata */
        foreach ($this->index->get_resources_of_type('js') as $js_meta) {
            if (empty($js_meta->direct_dependencies))
                continue;

            $dependencies[$js_meta->filename] = $js_meta->direct_dependencies;
        }
        return $dependencies;
    }

}
