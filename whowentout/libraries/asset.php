<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class CI_Asset
{

    function __construct()
    {
        
    }

    function dependencies($name)
    {
        $tree = $this->dependency_tree($name);

        $dependencies = array();

        while (count($tree) > 0) {
            $independent_item = FALSE;
            foreach ($tree as $item => $item_dependencies) {
                if (empty($item_dependencies)) {
                    $dependencies[] = $item;
                    unset($tree[$item]);
                    foreach ($tree as $k => $v) {
                        $tree[$k] = array_diff($v, array($item));
                    }
                    $independent_item = TRUE;
                }
            }

            if (!$independent_item)
                throw new Exception("Circular dependency.");
        }

        return $dependencies;
    }

    function dependency_tree($name)
    {
        $tree = array();

        if (is_string($name))
            $unprocessed = array($name);
        else
            $unprocessed = $name;

        while (count($unprocessed) > 0) {
            $cur = array_pop($unprocessed);

            if (isset($tree[$cur]))
                continue;

            $tree[$cur] = $this->direct_dependencies($cur);
            ;
            $unprocessed = array_merge($unprocessed, $tree[$cur]);
        }

        return $tree;
    }

    function direct_dependencies($name)
    {
        $dependencies = array();

        if (!$this->exists($name))
            return array();

        $contents = file_get_contents($this->path($name));

        $lines = explode("\n", $contents);
        foreach ($lines as $line) {
            if ($this->string_starts_with('//= require ', $line)) {
                $dependencies[] = trim($this->string_after_first('//= require ', $line));
            }
        }

        return $dependencies;
    }

    function path($name)
    {
        return 'assets/js/' . $name;
    }

    function exists($name)
    {
        return file_exists($this->path($name));
    }

    function js($filenames, $pack = FALSE)
    {
        $tags = array();
        $names = $this->dependencies($filenames);

        if ($pack) {
            $this->pack($names, 'app.js');
            $names = array('app.js');
        }

        foreach ($names as $name) {
            $path = $this->path($name);
            $tags[] = $this->tag('script', array(
                                                'type' => 'text/javascript',
                                                'src' => site_url($path),
                                           ));
        }

        return implode("\n", $tags);
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
            $path = $this->path($name);
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

    private function string_after_first($needle, $haystack)
    {
        $pos = strpos($haystack, $needle);
        if ($pos === FALSE) {
            return FALSE;
        } else {
            return substr($haystack, $pos + strlen($needle));
        }
    }

}
