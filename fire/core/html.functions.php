<?php


function html_element_open($tag, $attributes = array())
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

    return implode('', $html);
}

function html_element_close($tag)
{
    return "</$tag>";
}

function html_element($tag, $attributes = array(), $content = '')
{
    return html_element_open($tag, $attributes) . $content . html_element_close($tag);
}


function a($path, $title, $attributes = array())
{
    return a_open($path, $attributes) . $title . a_close();
}

function a_open($path, $attributes = array())
{
    $attributes['href'] = url($path);

    if (is_active($path)) {
        $attributes['class'] = isset($attributes['class'])
                ? $attributes['class'] . ' active'
                : 'active';
    }

    return html_element_open('a', $attributes);
}

function a_close()
{
    return html_element_close('a');
}

function img($path, $attributes = array())
{
    $attributes['src'] = $path;
    return html_element('img', $attributes);
}
