<?php

class FileIndexer extends Indexer
{

    function matches(Metadata $meta)
    {
        return $meta instanceof DirectoryMetadata;
    }

    function index(DirectoryMetadata $meta)
    {
        /* @var $metas Metadata */
        $metas = array();

        $files = $this->get_files($meta->directory_path);
        foreach ($files as $directory) {
            $metas[] = $this->get_file_metadata($directory);
        }

        return $metas;
    }


    private function get_file_metadata($file_path)
    {
        $meta = new FileMetadata();
        $meta->type = 'file';
        $meta->name = basename($file_path);

        $meta->filepath = $file_path;
        $meta->filename = $meta->name;
        $meta->extension = string_after_last('.', $meta->filename);

        return $meta;
    }

}
