<?php

require_once 'indexers/phpfileindexer.class.php';

class Index
{

    private $root;
    /**
     * @var \FilesystemCache
     */
    private $cache;
    private $data = array();

    function __construct($root, FilesystemCache $cache)
    {
        $this->root = $root;
        $this->cache = $cache;

        if ($this->requires_rebuild()) {
            $this->rebuild();
        }
        else {
            $this->load_from_cache();
        }
    }

    function data()
    {
        return $this->data;
    }

    function root()
    {
        return $this->data['root'];
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

        return isset($this->data['resources'][$resource_path])
                ? $this->data['resources'][$resource_path]
                : null;
    }

    function get_alias_path($alias)
    {
        $alias = strtolower($alias);
        if (!isset($this->data['aliases'][$alias]))
            return false;

        if (count($this->data['aliases'][$alias]) > 1) {
            throw new Exception("Ambiguous alias $alias.");
        }

        return $this->data['aliases'][$alias][0];
    }

    function set_resource_metadata($resource_path, $resource_metadata)
    {
        $this->data['resources'][$resource_path] = $resource_metadata;
    }

    private function save_to_cache()
    {
        $this->cache_set('index', $this->data);
        $this->cache_set('version', $this->fetch_real_version());
    }

    private function load_from_cache()
    {
        krumo::dump('load_from_cache');exit;
        $this->data = $this->cache_get('index');
    }

    private function rebuild()
    {
        krumo::dump('rebuild');exit;
        $this->data = array(
            'root' => realpath($this->root),
        );

        $this->index_directories();
        $this->index_files();
        $this->index_php_files();

        $this->save_to_cache();
        return $this->data;
    }

    private function clear_index($path)
    {
        foreach ($this->data['resources'] as $resource_path => $resource_meta) {
            if ($this->string_starts_with($path . '/', $resource_path))
                unset($this->data['resources'][$resource_path]);
        }

        foreach ($this->data['aliases'] as $alias => $linked_resource_paths) {
            foreach ($linked_resource_paths as $k => $resource_path) {
                if (!isset($this->data['resources'][$resource_path])) {
                    unset($this->data['aliases'][$alias][$k]);
                }
            }

            if (empty($this->data['aliases'][$alias])) {
                unset($this->data['aliases'][$alias]);
            }
            else {
                $this->data['aliases'][$alias] = array_values($this->data['aliases'][$alias]);
            }
        }
    }

    function add_resource_alias($alias, $path)
    {
        $alias = strtolower($alias);
        $this->data['aliases'][$alias][] = $path;
    }

    private function index_directories()
    {
        $directories = $this->scan_directories($this->root(), true);
        foreach ($directories as $directory) {
            $this->index_directory($directory);
        }
    }

    private function index_directory($dirpath)
    {
        $root = realpath($this->root());
        $path = realpath($dirpath);
        $resource_path = $this->string_after_first($root, $path);
        $resource_path = str_replace('\\', '/', $resource_path);
        $resource_path = substr($resource_path, 1);

        $dir_metadata = array();

        $dir_metadata['type'] = 'directory';
        $dir_metadata['path'] = $resource_path;
        $dir_metadata['directorypath'] = $dirpath;
        
        $this->set_resource_metadata($dir_metadata['path'], $dir_metadata);
    }

    private function index_files()
    {
        $files = $this->scan_files($this->root(), true);
        foreach ($files as $filepath) {
            $this->index_file($filepath);
        }
    }

    private function index_php_files()
    {
        foreach ($this->data['resources'] as $resource) {
            if ($resource['type'] == 'file' && $this->is_php_file($resource)) {
                $this->index_php_file($resource);
            }
        }

        $this->index_php_class_heirarchy();
    }

    private function index_php_class_heirarchy()
    {
        foreach ($this->data['resources'] as &$resource) {
            if ($resource['type'] == 'class' && isset($resource['parent'])) {
                $superclass_resource_path = $this->get_alias_path($resource['parent'] . ' class');
                if ($superclass_resource_path) {
                    $superclass_resource_metadata =& $this->data['resources'][$superclass_resource_path];
                    $superclass_resource_metadata['subclasses'][] = $resource['name'];
                }
            }
        }
    }

    private function index_file($filepath)
    {
        $root = realpath($this->root());
        $filepath = realpath($filepath);
        $resource_path = $this->string_after_first($root, $filepath);
        $resource_path = str_replace('\\', '/', $resource_path);
        $resource_path = substr($resource_path, 1);

        $file_metadata = array();
        $file_metadata['type'] = 'file';
        $file_metadata['path'] = $resource_path;

        $file_metadata['filepath'] = $filepath;
        $file_metadata['filename'] = basename($filepath);
        $file_metadata['extension'] = $this->string_after_last('.', $file_metadata['filename']);

        $this->set_resource_metadata($resource_path, $file_metadata);
        $this->add_resource_alias($file_metadata['filename'], $resource_path);
    }

    private function is_php_file($file_metadata)
    {
        return $this->string_ends_with('.php', $file_metadata['filepath']);
    }

    private function index_php_file($file_metadata)
    {
        $php_file_indexer = new PHPFileIndexer($this);
        $php_file_indexer->index($file_metadata);
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

            if ($file->isFile()) {
                $files[] = $filepath;
            }
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

            // Standardize to forward slashes
            $filepath = str_replace('\\', '/', $file->getPathName());

            if ($file->isDir()) {
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
