<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class CI_Asset
{

    private $ci;

    private $config = array();
    private $source_js_version = 1;

    private $index = array();

    private $loaded = array();

    function __construct()
    {
        $this->ci =& get_instance();

        $this->ci->load->library('cache');
        $this->cache =& $this->ci->cache;

        $this->config = $this->ci->config->item('asset');
        $this->source_js_version = intval(file_get_contents('assets/js/version.txt'));
    }

    function load($names)
    {
        if (is_string($names))
            $names = array($names);

        foreach ($names as $name) {
            $this->loaded[$name] = TRUE;
        }
    }

    function js()
    {
        $loaded = $this->loaded;

        $require_order = $this->require_order();
        $direct_dependency_tree = $this->direct_dependency_tree();
        
        $js = array();
        foreach ($loaded as $name => $cur_is_loaded) {
            if ($this->string_ends_with('.js', $name)) {
                $js[] = $name;
            }
        }

        $unprocessed = $js;
        while (count($unprocessed) > 0) {
            $cur = array_pop($unprocessed);
            foreach ($direct_dependency_tree[$cur] as $dependency) {
                $loaded[$dependency] = TRUE;
                $unprocessed[] = $dependency;
            }
        }

        $names = array_values( array_intersect($require_order, array_keys($loaded)) );
        $tags = array();
        foreach ($names as $name) {
            $this->tags[] = $this->tag('script', array(
                                                   'type' => 'text/javascript',
                                                   'src' => $this->source_path($name),
                                                 ));
        }
        return implode("\n\n", $this->tags);
    }

    private function require_order()
    {
        $this->update_index();
        return $this->index['require_order'];
    }

    private function direct_dependency_tree()
    {
        $this->update_index();
        return $this->index['direct_dependency_tree'];
    }

    private function index_is_outdated()
    {
        $index = $this->cache->get('asset_js_index');
        return !$index
                || $index['version'] != $this->source_js_version
                || $this->source_js_version == 'refresh';
    }

    private function update_index()
    {
        if ($this->index_is_outdated()) {
            $this->cache->set('asset_js_index', array(
                                                  'version' => $this->source_js_version,
                                                  'require_order' => $this->compute_require_order(),
                                                  'direct_dependency_tree' => $this->compute_direct_dependency_tree(),
                                                ));
        }
        $this->index = $this->cache->get('asset_js_index');
    }

    private function compute_require_order()
    {
        $tree = $this->compute_direct_dependency_tree();

        $dependencies = array();

        while (count($tree) > 0) {
            $has_asset_with_no_dependencies = FALSE;
            foreach ($tree as $item => $item_dependencies) {
                if (empty($item_dependencies)) {
                    $has_asset_with_no_dependencies = TRUE;

                    $dependencies[] = $item;
                    unset($tree[$item]);
                    foreach ($tree as $k => $v) {
                        $tree[$k] = array_diff($v, array($item));
                    }
                }
            }

            if (!$has_asset_with_no_dependencies) { //every remaining asset has 1+ dependencies
                throw new Exception("Circular dependency.");
            }
        }

        return $dependencies;
    }

    private function names()
    {
        $files = $this->files('assets/js', TRUE);
        foreach ($files as &$file) {
            $file = $this->string_after_first('assets/js/', $file);
        }
        return $files;
    }

    private function compute_direct_dependency_tree()
    {
        $tree = array();
        $unprocessed = $this->names();

        while (count($unprocessed) > 0) {
            $cur = array_pop($unprocessed);

            if (isset($tree[$cur]))
                continue;

            $tree[$cur] = $this->direct_dependencies($cur);
            $unprocessed = array_merge($unprocessed, $tree[$cur]);
        }

        return $tree;
    }

    private function direct_dependencies($name)
    {
        $dependencies = array();

        if (!$this->exists($name))
            return array();

        $contents = file_get_contents($this->source_path($name));

        $lines = explode("\n", $contents);
        foreach ($lines as $line) {
            if ($this->string_starts_with('//= require ', $line)) {
                $dependencies[] = trim($this->string_after_first('//= require ', $line));
            }
        }

        return $dependencies;
    }

    function output_path($name)
    {
        return 'js/' . $name;
    }

    function source_path($name)
    {
        return 'assets/js/' . $name;
    }

    function exists($name)
    {
        return file_exists($this->source_path($name));
    }

    function pack($names, $destination, $overwrite = FALSE)
    {
        if (is_string($names)) {
            $names = array($names);
        }

        $output_path = './assets/js/' . $destination;
        $output_url = site_url('/assets/js/' . $destination);

        if (file_exists($output_path) && $overwrite == FALSE)
            return $output_url;

        $js = array();
        $min_js = array();

        foreach ($names as $name) {
            $path = $this->source_path($name);
            $contents = file_get_contents($path);
            $js[] = $contents;
            $min_js[] = "// $name\n" . $this->pack_js($contents) . "\n";
        }

        $output = implode("\n\n", $min_js);
        file_put_contents($output_path, $output);
        return $output_url;
    }

    private function pack_js($contents)
    {
        return $contents;
        //return JSMinPlus::minify($contents);
    }

    private function tag_open($tag, $attributes = array())
    {
        $html = array("<{$tag}");
        if (isset($attributes['class']) && is_array($attributes['class'])) {
            $attributes['class'] = implode(' ', $attributes['class']);
        }
        if (!empty($attributes)) {
            foreach ($attributes as $attribute_name => $attribute_value) {
                if ($attribute_value === NULL) {
                    continue;
                }
                $html[] = sprintf(' %s="%s"', $attribute_name, $attribute_value);
            }
        }
        $html[] = ">";
        return implode('', $html);
    }

    private function tag_close($tag)
    {
        return "</$tag>";
    }

    private function tag($tag, $attributes = array(), $inner_html = '')
    {
        return $this->tag_open($tag, $attributes) . $inner_html . $this->tag_close($tag);
    }

    private function string_starts_with($start_of_string, $source)
    {
        return strncmp($source, $start_of_string, strlen($start_of_string)) == 0;
    }

    private function string_ends_with($end_of_string, $string)
    {
        return substr($string, -strlen($end_of_string)) === $end_of_string;
    }

    private function string_after_first($needle, $haystack)
    {
        $pos = strpos($haystack, $needle);
        if ($pos === FALSE) {
            return FALSE;
        } else {
            return substr($haystack, $pos + strlen($needle));
        }
    }

    private function files($path, $include_subdirectories = FALSE)
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

}
