<?php

require_once FIREPATH . 'core/phpclassparser.class.php';

class PHPFileIndexer
{

    /**
     * @var \Index
     */
    private $index;

    function __construct(Index $index)
    {
        $this->index = $index;
    }

    function index($file_metadata)
    {
        $parser = new PHPClassParser();

        $filepath = $file_metadata->filepath;

        $classes_in_filepath = $parser->get_file_classes($filepath);

        foreach ($classes_in_filepath as $class_name => &$class_metadata) {
            $meta = new ClassMetadata();
            foreach ($class_metadata as $k => $v)
                $meta->$k = $v;

            $meta->type = 'class';
            $meta->path = $file_metadata->path . '/' . $class_name;
            $meta->file = $file_metadata->path;

            $this->index->set_resource_metadata($meta->path, $meta);

            $this->index->create_alias($class_name, $meta);
            $this->index->create_alias($class_name . ' class', $meta);
        }
    }

}
