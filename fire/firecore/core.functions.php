<?php

function show_404()
{
    print "<h1>404 page not found</h1>";
    exit;
}

function html_element($tag, $attributes = array(), $content = '')
{
    $html = array();

    $html[] = "<$tag";

    if (!empty($attributes)) {
        $html[] = ' ';
        foreach ($attributes as $prop => $val) {
            $html[] = sprintf('%s="%s"', $prop, $val);
        }
    }

    $html[] = ">";
    $html[] = $content;
    $html[] = "</$tag>";

    return implode('', $html);
}

function a($url, $title, $attributes = array())
{
    $attributes['href'] = $url;
    return html_element('a', $attributes, $title);
}

function conjunct($words)
{
    $last_word = array_pop($words);
    return empty($words) ? $last_word
                         : implode(', ', $words) . ", and $last_word";
}

function check_required_options($options_to_check, $required_options)
{
    $missing = array();

    foreach ($required_options as $key) {
        if ( ! isset($options_to_check[$key]) ) {
            $missing[] = $key;
        }
    }

    if (count($missing) > 0) {
        throw new Exception("You are missing " . conjunct($missing) . '.');
    }
}
