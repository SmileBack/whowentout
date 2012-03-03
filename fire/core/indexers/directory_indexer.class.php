<?php

class DirectoryIndexer extends Indexer
{

    function index(DirectoryMetadata $meta)
    {
        /* @var $metas Metadata */
        $metas = array();

        $files = $this->get_files($meta->directory_path);
        foreach ($files as $file) {
            $metas[] = $this->get_file_metadata($file);
        }

        $directories = $this->get_subdirectories($meta->directory_path);
        foreach ($directories as $directory) {
            $metas[] = $this->get_directory_metadata($directory);
        }

        foreach ($metas as $cur_meta) {
            $meta->children[$cur_meta->name] = $cur_meta;
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

    protected function get_subdirectories($path)
    {
        return $this->get_items($path, function($cur) {
            return $cur->isDir();
        });
    }

    protected function get_files($path)
    {
        return $this->get_items($path, function($cur) {
            return $cur->isFile();
        });
    }

    protected function get_items($directory_path, $condition = null)
    {
        if (!$condition)
            $condition = function() { return true; };

        if (!is_dir($directory_path))
            return false;

        $items = array();
        $iterator = $this->get_directory_iterator($directory_path);

        foreach ($iterator as $cur_item) {
            if ($cur_item->isDot())
                continue;

            // Standardize to forward slashes
            $path = str_replace('\\', '/', $cur_item->getPathName());

            if ($condition($cur_item)) {
                $items[] = $path;
            }
        }

        return $items;
    }

    protected function get_directory_iterator($path)
    {
        return new IgnoreFilesIterator(
            new DirectoryIterator($path)
        );
    }

}

class IgnoreFilesIterator extends FilterIterator
{
    public function accept()
    {
        /* @var $current_file SplFileInfo */
        $current_file = $this->current();
        $filename = $current_file->getFilename();
        if ($current_file->isDir() && substr($filename, 0, 1) == '.')
            return false;
        else
            return true;
    }
}
