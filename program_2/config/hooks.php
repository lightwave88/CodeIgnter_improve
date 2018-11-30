<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	https://codeigniter.com/user_guide/general/hooks.html
|
*/

$hook['module_loaded'][] = array(
    'function' => 'interceptor_1',
    'filename' => 'pre_cache.php',
    'filepath' => 'hooks',
    'params' => array(),
);
