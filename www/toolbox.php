<?php

define('CSS_PATH', '/css');
define('JS_PATH', '/js');
define('IMG_PATH', '/img');

function css($name)
{
    $path = CSS_PATH . '/' . $name;
    $ext = string_after_last('.', $name);
    $uncached_path = string_before_last(".$ext", $path) . '.' . filemtime("./$path") . ".$ext";

    $attributes = array(
        'rel' => 'stylesheet',
        'type' => "text/$ext",
        'href' => $uncached_path,
    );
    return html_element('link', $attributes);
}

function js($name)
{
    $path = JS_PATH . '/' . $name;
    $ext = string_after_last('.', $name);
    $uncached_path = string_before_last(".$ext", $path) . '.' . filemtime("./$path") . ".$ext";

    $attributes = array(
        'type' => "text/javascript",
        'src' => $uncached_path,
    );
    return html_element('script', $attributes);
}

function img($name)
{
    $path = IMG_PATH . '/' . $name;
    $ext = string_after_last('.', $name);
    $uncached_path = string_before_last(".$ext", $path) . '.' . filemtime("./$path") . ".$ext";

    $attributes = array(
        'src' => $uncached_path,
    );
    return html_element('img', $attributes);
}

function html_element_open($tag, $attributes = array())
{
    $html = array();

    $html[] = "<$tag";

    if (!empty($attributes)) {
        foreach ($attributes as $prop => $val) {
            $html[] = sprintf(' %s="%s"', $prop, $val);
        }
    }

    $html[] = ">";

    return implode('', $html);
}

function html_element_close($tag)
{
    return "</$tag>";
}

function html_element($tag, $attributes = array(), $content = '')
{
    $element = html_element_open($tag, $attributes) . $content . html_element_close($tag);
    if (!$content && $tag != 'script')
        $element = str_replace("></$tag>", " />", $element);
    return $element;
}

function environment()
{
    if (getenv('environment')) {
        return getenv('environment');
    }
    else {
        $environment = $_SERVER['SERVER_NAME'];
        $parts = explode('.', $environment);
        $parts = array_diff($parts, array('www', 'com'));
        return array_shift($parts);
    }
}

function site_url($path = '')
{
    $protocol = 'http://';
    $host = $_SERVER['HTTP_HOST'];
    if ($path)
        return $protocol . $host . url($path);
    else
        return $protocol . $host;
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

