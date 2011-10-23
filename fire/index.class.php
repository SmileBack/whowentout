<?php

require_once 'phpclassparser.class.php';
require_once APPPATH . 'packages/debug/krumo.class.php';

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
        $alias_path = $this->get_alias_path($name);
        if ($alias_path) {
            $resource_path = $alias_path;
        }
        else {
            $resource_path = $name;
        }

        return isset($this->index['resources'][$resource_path])
                ? $this->index['resources'][$resource_path]
                : NULL;
    }

    function get_alias_path($alias)
    {
        $alias = strtolower($alias);
        if (!isset($this->index['aliases'][$alias]))
            return FALSE;

        if (count($this->index['aliases'][$alias]) > 1) {
            throw new Exception("Ambiguous alias $alias.");
        }

        return $this->index['aliases'][$alias][0];
    }

    function set_resource_metadata(&$index, $resource_path, $resource_metadata)
    {
        $index['resources'][$resource_path] = $resource_metadata;
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

        $this->index_directories($this->index);
        $this->index_files($this->index);
        $this->index_php_files($this->index);

        $this->cache->set($this->cache_key(), $this->index);
        return $this->index;
    }

    function clear_index($path)
    {
        foreach ($this->index['resources'] as $resource_path => $resource_meta) {
            if ($this->string_starts_with($path . '/', $resource_path))
                unset($this->index['resources'][$resource_path]);
        }

        foreach ($this->index['aliases'] as $alias => $linked_resource_paths) {
            foreach ($linked_resource_paths as $k => $resource_path) {
                if (!isset($this->index['resources'][$resource_path])) {
                    unset($this->index['aliases'][$alias][$k]);
                }
            }

            if (empty($this->index['aliases'][$alias])) {
                unset($this->index['aliases'][$alias]);
            }
            else {
                $this->index['aliases'][$alias] = array_values($this->index['aliases'][$alias]);
            }
        }
    }

    function add_resource_alias(&$index, $alias, $path)
    {
        $alias = strtolower($alias);
        $index['aliases'][$alias][] = $path;
    }

    function index_directories(&$index)
    {
        $directories = $this->scan_directories($index['root'], TRUE);
        foreach ($directories as $directory) {
            $this->index_directory($index, $directory);
        }
    }

    function index_directory(&$index, $dirpath)
    {
        $dir_metadata = array();

        $dir_metadata['type'] = 'directory';
        $dir_metadata['path'] = $this->string_after_first($index['root'], $dirpath);
        $dir_metadata['directorypath'] = $dirpath;

        $this->set_resource_metadata($index, $dir_metadata['path'], $dir_metadata);
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
            if ($resource['type'] == 'file' && $this->is_php_file($resource)) {
                $this->index_php_file($index, $resource);
            }
        }
        $this->index_php_class_heirarchy($index);
    }

    function index_php_class_heirarchy(&$index)
    {
        foreach ($index['resources'] as &$resource) {

            if (!isset($resource['type']))
                krumo::dump($resource);

            if ($resource['type'] == 'class' && isset($resource['parent'])) {
                $superclass_resource_path = $this->get_alias_path($resource['parent'] . ' class');
                if ($superclass_resource_path) {
                    $superclass_resource_metadata =& $index['resources'][$superclass_resource_path];
                    $superclass_resource_metadata['subclasses'][] = $resource['name'];
                }
            }
        }
    }

    function index_file(&$index, $filepath)
    {
        $resource_path = $this->string_after_first($index['root'], $filepath);

        $file_metadata = array();
        $file_metadata['type'] = 'file';
        $file_metadata['path'] = $resource_path;

        $file_metadata['filepath'] = $filepath;
        $file_metadata['filename'] = basename($filepath);
        $file_metadata['extension'] = $this->string_after_last('.', $file_metadata['filename']);

        $this->set_resource_metadata($index, $resource_path, $file_metadata);
        $this->add_resource_alias($index, $file_metadata['filename'], $resource_path);
    }

    function is_php_file($file_metadata)
    {
        return $this->string_ends_with('.php', $file_metadata['filepath']);
    }

    function index_php_file(&$index, $file_metadata)
    {
        $parser = new PHPClassParser();

        $filepath = $file_metadata['filepath'];

        $classes_in_filepath = $parser->get_file_classes($filepath);

        foreach ($classes_in_filepath as $class_name => &$class_metadata) {
            $class_metadata['type'] = 'class';
            $class_metadata['path'] = $file_metadata['path'] . '/' . $class_name;
            $class_metadata['file'] = $file_metadata['path'];

            $this->set_resource_metadata($index, $class_metadata['path'], $class_metadata);
            $this->add_resource_alias($index, $class_name, $class_metadata['path']);
            $this->add_resource_alias($index, $class_name . ' class', $class_metadata['path']);
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

    function scan_directories($path, $include_subdirectories = FALSE)
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
