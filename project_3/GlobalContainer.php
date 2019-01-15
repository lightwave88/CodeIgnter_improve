<?php

defined('BASEPATH') OR exit('No direct script access allowed');

if (!class_exists('CI_ProxyController')) {
    require_once(__DIR__ . '/Controller.php');
}

// 全域容器
// 所有的 controller 都映射到此
// 所有功能都注入到此
class CI_GlobalContainer {

    //put your code here

    public function __construct() {

        CI_Controller::$instance = & $this;

        // Assign all the class objects that were instantiated by the
        // bootstrap file (CodeIgniter.php) to local class variables
        // so that CI can run as one big super object.
        foreach (is_loaded() as $var => $class) {
            $this->$var = & load_class($class);
        }

        $this->load = & load_class('Loader', 'core');
        $this->load->initialize();
        log_message('info', 'CI_GlobalController Class Initialized');
    }

}
