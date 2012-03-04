<?php

class DirectoryIndexer extends Indexer
{

    function matches(Metadata $meta)
    {
        return $meta instanceof DirectoryMetadata;
    }

    function index(DirectoryMetadata $meta)
    {
        /* @var $metas Metadata */
        $metas = array();

        $directories = $this->get_subdirectories($meta->directory_path);
        foreach ($directories as $directory) {
            $metas[] = $this->get_directory_metadata($directory);
        }

        return $metas;
    }

    /**
     * @param $directory_path
     * @return DirectoryMetadata
     */
    private function get_directory_metadata($directory_path)
    {
        $meta = new DirectoryMetadata();
        $meta->type = 'directory';
        $meta->name = basename($directory_path);
        $meta->directory_path = $directory_path;

        return $meta;
    }

}
