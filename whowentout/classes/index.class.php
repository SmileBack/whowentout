<?php

require_once APPPATH . 'classes/phpclassparser.class.php';
require_once APPPATH . 'modules/debug/krumo.class.php';

class Index
{

    private $root;
    private $cache;

    function __construct($root, $cache)
    {
        $this->root = $root;
        $this->cache = $cache;

        if ($this->requires_rebuild())
            $this->rebuild();
        else
            $this->load_from_cache();
    }

    function data()
    {
        return $this->index;
    }

    function get_resource_metadata($name)
    {
        if (isset($this->index['aliases'][$name])) {
            
            if ( count($this->index['aliases'][$name]) > 1) {
                throw new Exception("Ambiguous alias $name.");
            }

            $resource_path = $this->index['aliases'][$name][0];
        }
        else {
            $resource_path = $name;
        }
        return $this->index['resources'][$resource_path];
    }

    function load_from_cache()
    {
        $this->index = $this->cache->get($this->cache_key());
    }

    function rebuild()
    {
        $this->index = array(
            'root' => $this->root,
        );

        $this->index_files($this->index);
        $this->index_php_files($this->index);

        $this->cache->set($this->cache_key(), $this->index);
        return $this->index;
    }

    function index_files(&$index)
    {
        $files = $this->scan_files($index['root'], TRUE);
        foreach ($files as $filepath) {
            $this->index_file($index, $filepath);
        }
    }

    function index_php_files(&$index)
    {
        foreach ($index['resources'] as $resource) {
            if ($resource['type'] == 'file' && $this->is_php_file($resource['filepath'])) {
                $this->index_php_file($index, $resource['filepath']);
            }
        }
    }
    
    function index_file(&$index, $filepath)
    {
        $relative_filepath = $this->string_after_first($index['root'], $filepath);
        $resource_path = $relative_filepath;

        $m = array();
        $m['type'] = 'file';
        $m['filepath'] = $filepath;
        $m['filename'] = basename($filepath);
        $m['relative_filepath'] = $relative_filepath;

        $index['resources'][$relative_filepath] = $m;
        $index['aliases'][strtolower($m['filename'])][] = $resource_path;
    }

    function index_php_file(&$index, $filepath)
    {
        $parser = new PHPClassParser();
        $classes_in_filepath = $parser->get_file_classes($filepath);
        $relative_filepath = $this->string_after_first($index['root'], $filepath);

        foreach ($classes_in_filepath as $class_name => &$class_metadata) {
            $class_metadata['type'] = 'class';

            if (isset($index['classes'][$class_name]))
                throw new Exception("Class $class_name already exists.");

            $resource_path = $relative_filepath . '/' . $class_name;

            $index['resources'][$resource_path] = $class_metadata;

            $index['aliases'][strtolower($class_name)][] = $resource_path;
            $index['aliases'][strtolower($class_name) . ' class'][] = $resource_path;
        }
    }

    function requires_rebuild()
    {
        return !$this->cache->exists($this->cache_key());
    }

    private function cache_key()
    {
        return 'blox_index_' . md5($this->root);
    }

    function is_php_file($filepath)
    {
        return $this->string_ends_with('.php', $filepath);
    }

    function scan_files($path, $include_subdirectories = FALSE)
    {
        if (!is_dir($path))
            return FALSE;

        $files = array();

        $iterator = $include_subdirectories
                ? new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path))
                : new DirectoryIterator($path);

        foreach ($iterator as $file) {
            // isDot method is only available in DirectoryIterator items
            // isDot check skips '.' and '..'
            if ($include_subdirectories == FALSE && $file->isDot())
                continue;
            // Standardize to forward slashes
            $files[] = str_replace('\\', '/', $file->getPathName());
        }

        return $files;
    }

    function scan_folders($path, $include_subdirectories = FALSE)
    {
        if (!is_dir($path))
            return FALSE;

        $folders = array();

        $iterator = $include_subdirectories
                ? new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::SELF_FIRST)
                : new DirectoryIterator($path);

        foreach ($iterator as $file) {
            // isDot method is only available in DirectoryIterator items
            // isDot check skips '.' and '..'
            if ($include_subdirectories == FALSE && $file->isDot())
                continue;

            if ($file->isDir()) {
                // Standardize to forward slashes
                $folders[] = str_replace('\\', '/', $file->getPathName());
            }
        }

        return $folders;
    }


    function string_ends_with($end_of_string, $string)
    {
        return substr($string, -strlen($end_of_string)) === $end_of_string;
    }

    function string_starts_with($start_of_string, $source)
    {
        return strncmp($source, $start_of_string, strlen($start_of_string)) == 0;
    }

    function string_after_first($needle, $haystack)
    {
        $pos = strpos($haystack, $needle);
        if ($pos === FALSE) {
            return FALSE;
        } else {
            return substr($haystack, $pos + strlen($needle));
        }
    }

    function string_before_first($needle, $haystack)
    {
        $pos = strpos($haystack, $needle);
        if ($pos === FALSE) {
            return FALSE;
        } else {
            return substr($haystack, 0, $pos);
        }
    }

    function string_after_last($needle, $haystack)
    {
        $pos = strrpos($haystack, $needle);
        if ($pos === FALSE) {
            return FALSE;
        } else {
            return substr($haystack, $pos + strlen($needle));
        }
    }

    function string_before_last($needle, $haystack)
    {
        $pos = strrpos($haystack, $needle);
        if ($pos === FALSE) {
            return FALSE;
        } else {
            return substr($haystack, 0, $pos);
        }
    }

}
