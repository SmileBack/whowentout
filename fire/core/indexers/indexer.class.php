<?php

class Indexer
{

    function matches(Metadata $meta)
    {
        return false;
    }

    function index(Metadata $meta)
    {
        return $meta instanceof Metadata;
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

    private function get_items($directory_path, $condition = null)
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

    private function get_directory_iterator($path)
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
