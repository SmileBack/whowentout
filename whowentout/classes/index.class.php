<?php

require_once APPPATH . 'classes/phpclassparser.class.php';
require_once APPPATH . 'modules/debug/krumo.class.php';

class DirectoryIndex
{

    private $path;
    private $cache;

    function __construct($path, $cache)
    {
        $this->path = $path;
        $this->cache = $cache;
    }

    function cache_key()
    {
        return 'blox_index_' . md5($this->path);
    }

    function data()
    {
        return $this->index;
    }

    function resource_data($name)
    {
        if (isset($this->index['aliases'][$name])) {
            $resource_path = $this->index['aliases'][$name];
        }
        else {
            $resource_path = $name;
        }
        return $this->index['resources'][$resource_path];
    }

    function load_from_cache()
    {
        $this->index = $this->cache->get( $this->cache_key() );
    }

    function rebuild()
    {
        $parser = new PHPClassParser();
        
        $index = array();
        
        $files = $this->scan_files($this->path, TRUE);
        foreach ($files as $filepath) {
            $relative_filepath = $this->string_after_first($this->path, $filepath);
            
            if ($this->is_php_file($filepath)) {
                $classes_in_filepath = $parser->get_file_classes($filepath);

                foreach ($classes_in_filepath as $class_name => &$class_data) {
                    $class_data['type'] = 'class';
                    if (isset($index['classes'][$class_name]))
                        throw new Exception("Class $class_name already exists.");;

                    $resource_path = $relative_filepath . '/' . $class_name;
                    
                    $index['resources'][$resource_path] = $class_data;

                    $index['aliases'][ strtolower($class_name) ][] = $resource_path;
                    $index['aliases'][ strtolower($class_name) . ' class'] = $resource_path;
                }
            }

        }
        
        $this->cache->set($this->cache_key(), $index);
        $this->index = $index;

        return $index;
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