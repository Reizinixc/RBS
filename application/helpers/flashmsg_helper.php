<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Get the bootstrap alert box.
 *
 * @param string $type type of flashed [warning | error | success | info]
 * @param string $head header text of flash message
 * @param string $msg message to announce the user
 *
 * @return string Alert box in div tag
 */
if (!function_exists('flashmsg')) {
  function flashmsg($type = '', $head = '', $msg = '') {
    $class = '';

    switch ($type) {
      case 'error':
        $class = ' alert-error';
        break;
      case 'success':
        $class = ' alert-success';
        break;
      case 'info':
        $class = ' alert-info';
        break;
      default:
        break;
    }

    return "<div class=\"alert{$class}\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button><h4>{$head}</h4>{$msg}</div>";
  }
}