<?php

defined('BASEPATH') OR exit('No direct script access allowed');

if (!class_exists('CI_ProxyController')) {
    require_once(__DIR__ . '/Controller.php');
}

// 在 controller 尚未实例化前
// 先生一个替身，方便功能注入
// 在 controller 未生成前， get_instance() 取得的就是它
// 等 controller 产生，再把注入的功能拷贝给 controller
//
class CI_ProxyController extends CI_Controller {

    //put your code here

    public function __construct() {

        parent::$instance = & $this;

        // Assign all the class objects that were instantiated by the
        // bootstrap file (CodeIgniter.php) to local class variables
        // so that CI can run as one big super object.
        foreach (is_loaded() as $var => $class) {
            $this->$var = & load_class($class);
        }

        $this->load = & load_class('Loader', 'core');
        $this->load->initialize();
        log_message('info', 'ProxyController Class Initialized');
    }

}
