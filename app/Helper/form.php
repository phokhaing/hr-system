<?php


/**
 * @param array $att
 */
function form_label(array $att)
{
    $value = '';
    if (isset($att['value'])) {
        $value = $att['value'];
        unset($att['value']);
    }

    $html = '';
    $html .= '<label ';
    foreach ($att as $key => $item) {
        $html .= $key.'="'.$item.'" ';
    }
    $html .= '>'.$value;
    $html .= '</label>';
    echo $html;
}

/**
 * @param array $att
 */
function form_text(array $att)
{
    $html = '';
    $html .= '<input type="text" ';
    foreach ($att as $key => $value) {
        $html .= $key.'="'.$value.'" ';
    }
    $html .= ' />';

    echo $html;
}

/**
 * @param array $att
 */
function form_file(array $att)
{
    $html = '';
    $html .= '<input type="file" ';
    foreach ($att as $key => $value) {
        $html .= $key.'="'.$value.'" ';
    }
    $html .= ' />';

    echo $html;
}

/**
 * @param array $att
 */
function form_number(array $att)
{
    $html = '';
    $html .= '<input type="number" ';
    foreach ($att as $key => $value) {
        $html .= $key.'="'.$value.'" ';
    }
    $html .= ' />';

    echo $html;
}

/**
 * @param array $att
 */
function form_textarea(array $att)
{
    $html = '';
    $html .= '<textarea ';
    $val = '';
    if (isset($att['value'])) {
        $val = $att['value'];
        unset($att['value']);
    }
    foreach ($att as $key => $value) {

        $html .= $key.'="'.$value.'" ';
    }
    $html .= ' />';

    if ($val) {
        $html .= $val;
    }
    $html .= '</textarea>';

    echo $html;
}