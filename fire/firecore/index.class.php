<?php

require_once 'phpclassparser.class.php';

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

        require_once FIREPATH . 'debug/krumo.class.php';
        krumo::dump($this->data());
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
                : null;
    }

    function get_alias_path($alias)
    {
        $alias = strtolower($alias);
        if (!isset($this->index['aliases'][$alias]))
            return false;

        if (count($this->index['aliases'][$alias]) > 1) {
            throw new Exception("Ambiguous alias $alias.");
        }

        return $this->index['aliases'][$alias][0];
    }

    function set_resource_metadata(&$index, $resource_path, $resource_metadata)
    {
        $index['resources'][$resource_path] = $resource_metadata;
    }

    private function save_to_cache()
    {
        $this->cache_set('index', $this->index);
        $this->cache_set('version', $this->fetch_real_version());
    }

    private function load_from_cache()
    {
        $this->index = $this->cache_get('index');
    }

    private function rebuild()
    {
        $this->index = array(
            'root' => $this->root,
        );

        $this->index_directories($this->index);
        $this->index_files($this->index);
        $this->index_php_files($this->index);

        $this->save_to_cache();
        return $this->index;
    }

    private function clear_index($path)
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

    private function add_resource_alias(&$index, $alias, $path)
    {
        $alias = strtolower($alias);
        $index['aliases'][$alias][] = $path;
    }

    private function index_directories(&$index)
    {
        $directories = $this->scan_directories($index['root'], true);
        foreach ($directories as $directory) {
            $this->index_directory($index, $directory);
        }
    }

    private function index_directory(&$index, $dirpath)
    {
        $dir_metadata = array();

        $dir_metadata['type'] = 'directory';
        $dir_metadata['path'] = $this->string_after_first($index['root'], $dirpath);
        $dir_metadata['directorypath'] = $dirpath;

        $this->set_resource_metadata($index, $dir_metadata['path'], $dir_metadata);
    }

    private function index_files(&$index)
    {
        $files = $this->scan_files($index['root'], true);
        foreach ($files as $filepath) {
            $this->index_file($index, $filepath);
        }
    }

    private function index_php_files(&$index)
    {
        foreach ($index['resources'] as $resource) {
            if ($resource['type'] == 'file' && $this->is_php_file($resource)) {
                $this->index_php_file($index, $resource);
            }
        }
        $this->index_php_class_heirarchy($index);
    }

    private function index_php_class_heirarchy(&$index)
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

    private function index_file(&$index, $filepath)
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

    private function is_php_file($file_metadata)
    {
        return $this->string_ends_with('.php', $file_metadata['filepath']);
    }

    private function index_php_file(&$index, $file_metadata)
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

    private function requires_rebuild()
    {
        return !$this->cache_exists('index')
               || $this->fetch_cached_version() < $this->fetch_real_version();
    }

    private function fetch_real_version()
    {
        return intval(@file_get_contents($this->root . 'version.txt'));
    }

    private function fetch_cached_version()
    {
        return intval($this->cache_get('version'));
    }

    private function cache_key()
    {
        return 'blox_index_' . md5($this->root);
    }

    private function cache_set($key, $value)
    {
        $namespaced_cache_key = $this->cache_key() . '_' . $key;
        $this->cache->set($namespaced_cache_key, $value);
    }

    private function cache_get($key)
    {
        $namespaced_cache_key = $this->cache_key() . '_' . $key;
        return $this->cache->get($namespaced_cache_key);
    }

    private function cache_exists($key)
    {
        $namespaced_cache_key = $this->cache_key() . '_' . $key;
        return $this->cache->exists($namespaced_cache_key);
    }

    private function scan_files($path, $include_subdirectories = false)
    {
        if (!is_dir($path))
            return false;

        $files = array();

        $iterator = $this->get_directory_iterator($path, $include_subdirectories);

        foreach ($iterator as $file) {
            // isDot method is only available in DirectoryIterator items
            // isDot check skips '.' and '..'
            if (method_exists($file, 'isDot') && $file->isDot())
                continue;

            // Standardize to forward slashes
            $filepath = str_replace('\\', '/', $file->getPathName());

            $files[] = $filepath;
        }

        return $files;
    }

    private function scan_directories($path, $include_subdirectories = false)
    {
        if (!is_dir($path))
            return false;

        $folders = array();

        $iterator = $this->get_directory_iterator($path, $include_subdirectories);

        foreach ($iterator as $file) {
            // isDot method is only available in DirectoryIterator items
            // isDot check skips '.' and '..'
            if (method_exists($file, 'isDot') && $file->isDot())
                continue;

            $filepath = str_replace('\\', '/', $file->getPathName());

            if ($file->isDir()) {
                // Standardize to forward slashes
                $folders[] = $filepath;
            }
        }

        return $folders;
    }

    private function get_directory_iterator($path, $include_subdirectories)
    {
        if ($include_subdirectories) {
            return new RecursiveIteratorIterator(
                new IgnoreFilesRecursiveFilterIterator(
                    new RecursiveDirectoryIterator($path)
                ),
                RecursiveIteratorIterator::SELF_FIRST
            );
        }
        else {
            return new IgnoreFilesIterator(
                new DirectoryIterator($path)
            );
        }
    }

    private function string_ends_with($end_of_string, $string)
    {
        return substr($string, -strlen($end_of_string)) === $end_of_string;
    }

    private function string_starts_with($start_of_string, $source)
    {
        return strncmp($source, $start_of_string, strlen($start_of_string)) == 0;
    }

    private function string_after_first($needle, $haystack)
    {
        $pos = strpos($haystack, $needle);
        if ($pos === false) {
            return false;
        } else {
            return substr($haystack, $pos + strlen($needle));
        }
    }

    private function string_before_first($needle, $haystack)
    {
        $pos = strpos($haystack, $needle);
        if ($pos === false) {
            return false;
        } else {
            return substr($haystack, 0, $pos);
        }
    }

    private function string_after_last($needle, $haystack)
    {
        $pos = strrpos($haystack, $needle);
        if ($pos === false) {
            return false;
        } else {
            return substr($haystack, $pos + strlen($needle));
        }
    }

    private function string_before_last($needle, $haystack)
    {
        $pos = strrpos($haystack, $needle);
        if ($pos === false) {
            return false;
        } else {
            return substr($haystack, 0, $pos);
        }
    }

}

class IgnoreFilesRecursiveFilterIterator extends RecursiveFilterIterator
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

class IgnoreFilesIterator extends FilterIterator
{
    public function accept()
    {
        /* @var $current_file SplFileInfo */
        $current_file = $this->current();
        $filename = $current_file->getFilename();
        var_dump($filename);
        if ($current_file->isDir() && substr($filename, 0, 1) == '.')
            return false;
        else
            return true;
    }
}
