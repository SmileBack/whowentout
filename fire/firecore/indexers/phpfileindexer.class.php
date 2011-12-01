<?php

require_once FIREPATH . 'firecore/phpclassparser.class.php';

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

        $filepath = $file_metadata['filepath'];

        $classes_in_filepath = $parser->get_file_classes($filepath);

        foreach ($classes_in_filepath as $class_name => &$class_metadata) {
            $class_metadata['type'] = 'class';
            $class_metadata['path'] = $file_metadata['path'] . '/' . $class_name;
            $class_metadata['file'] = $file_metadata['path'];

            $this->index->set_resource_metadata($class_metadata['path'], $class_metadata);
            $this->index->add_resource_alias($class_name, $class_metadata['path']);
            $this->index->add_resource_alias($class_name . ' class', $class_metadata['path']);
        }
    }

}
