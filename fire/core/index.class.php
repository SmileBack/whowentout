<?php

require_once 'indexers/class_indexer.class.php';

require_once 'meta/metadata.class.php';
require_once 'meta/directory_metadata.class.php';
require_once 'meta/file_metadata.class.php';
require_once 'meta/class_metadata.class.php';
require_once 'meta/config_metadata.class.php';

require_once 'indexers/indexer.class.php';
require_once 'indexers/directory_indexer.class.php';
require_once 'indexers/file_indexer.class.php';
require_once 'indexers/class_indexer.class.php';

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

    /**
     * @param $type
     * @return Metadata[]
     */
    function get_resources_of_type($type)
    {
        $resources = array();

        $meta_class = $type . 'metadata';

        foreach ($this->data['resources'] as $meta)
            if ($meta instanceof $meta_class)
                $resources[] = $meta;

        return $resources;
    }

    /**
     * @param $resource_name
     *
     * @return Metadata|null
     */
    function get_metadata($resource_name)
    {
        $alias_path = $this->get_alias_path($resource_name);
        if ($alias_path) {
            $resource_path = $alias_path;
        }
        else {
            $resource_path = $resource_name;
        }

        $data = isset($this->data['resources'][$resource_path])
                ? $this->data['resources'][$resource_path]
                : null;

        return $data;
    }

    function set_resource_metadata($path, Metadata $metadata)
    {
        $this->data['resources'][$path] = $metadata;
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

    private function save_to_cache()
    {
        $this->cache_set('index', $this->data);
        $this->cache_set('version', $this->fetch_real_version());
    }

    private function load_from_cache()
    {
        $this->data = $this->cache_get('index');
    }

    private function rebuild()
    {
        $this->data = array(
            'root' => realpath($this->root),
        );

        $this->index_directories();
        $this->index_files();
        $this->index_php_files();
        $this->index_config_files();

        $this->save_to_cache();
        
        return $this->data;
    }

    function create_alias($alias, Metadata $metadata)
    {
        $alias = strtolower($alias);
        $this->data['aliases'][$alias][] = $metadata->path;
    }

    private function index_directories()
    {
        $indexer = new DirectoryIndexer($this);
        $indexer->run();
    }

    private function index_config_files()
    {
        foreach ($this->data['resources'] as $resource) {
            if ($resource->type == 'file' && $this->is_config_file($resource)) {
                $this->index_config_file($resource);
            }
        }
    }

    private function index_config_file(FileMetadata $file_metadata)
    {
        $meta = new ConfigMetadata();
        $meta->type = 'config';
        $meta->path = $file_metadata->path;
        $meta->filepath = $file_metadata->filepath;
        $meta->filename = $file_metadata->filename;
        $meta->extension = $file_metadata->extension;
        $meta->data = array(1, 2, 3);

        $this->set_resource_metadata($meta->path, $meta);;
    }

    private function is_config_file($file_metadata)
    {
        return $this->string_ends_with('.yml', $file_metadata->filepath);
    }

    private function index_files()
    {
        $file_indexer = new FileIndexer($this);
        $file_indexer->run();
    }

    private function index_php_files()
    {
        foreach ($this->data['resources'] as $resource) {
            if ($resource->type == 'file' && $this->is_php_file($resource)) {
                $this->index_php_file($resource);
            }
        }

        $this->index_php_class_heirarchy();
    }

    private function index_php_class_heirarchy()
    {
        foreach ($this->data['resources'] as &$resource) {
            if ($resource->type == 'class' && isset($resource->parent)) {
                $superclass_resource_path = $this->get_alias_path($resource->parent . ' class');
                if ($superclass_resource_path) {
                    $superclass_resource_metadata =& $this->data['resources'][$superclass_resource_path];
                    $superclass_resource_metadata->subclasses[] = $resource->name;
                }
            }
        }
    }

    private function is_php_file($file_metadata)
    {
        return $this->string_ends_with('.php', $file_metadata->filepath);
    }

    private function index_php_file($file_metadata)
    {
        $php_file_indexer = new PHPFileIndexer($this);
        $php_file_indexer->index($file_metadata);
    }

    private function requires_rebuild()
    {
        if (!$this->cache_exists('index'))
            return true;

        $cached_version = $this->fetch_cached_version();
        $real_version = $this->fetch_real_version();

        return version_compare($cached_version, $real_version, '<');
    }

    private function fetch_real_version()
    {
        return @file_get_contents($this->root . 'version.txt');
    }

    private function fetch_cached_version()
    {
        return $this->cache_get('version');
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
