<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// 舊有的 view 環境是 建立在 loader 文本上
// 並把 $ci 上的東西都拷貝到 loader 上
// 
// 改進
// 提供創建 template 的環境
// 避免 loader 掛上太多變數
class CI_TemplateFactory {

    protected $_ci_ob_level;
    protected $_ci;
    protected $_ci_return;
    protected $_view_path;

    public function __construct($ci_ob_level, $_view_path, $_ci_return = false) {
        $this->_ci_ob_level = $ci_ob_level;
        $this->_ci = &get_instance();
        $this->_ci_return = $_ci_return;
        $this->_view_path = $_view_path;
        // $this->_cloneVars();
    }

    //--------------------------------------------------------------------------
    // $_view_path: view 放置的位置
    // $_ci_cached_vars: 傳給 view 的參數
    // $_ci_return: 是否要返回 view 的文本
    public function loadView(array $vars = array()) {
        // 把 controller 的 參數拷貝到此
        // 方便 view load controller 的模組
        // $this->_cloneVars();
        // 把 $this->load->view(path, data);
        // 裏的 data 化為此 scope 的全域變數
        // 方便 view 裡面的操作
        extract($vars);

        /*
         * Buffer the output
         *
         * We buffer the output for two reasons:
         * 1. Speed. You get a significant speed boost.
         * 2. So that the final rendered template can be post-processed by
         * 	the output class. Why do we need post processing? For one thing,
         * 	in order to show the elapsed page load time. Unless we can
         * 	intercept the content right before it's sent to the browser and
         * 	then stop the timer it won't be accurate.
         */
        ob_start();

        // If the PHP installation does not support short tags we'll
        // do a little string replacement, changing the short tags
        // to standard PHP echo statements.
        if (!is_php('5.4') && !ini_get('short_open_tag') && config_item('rewrite_short_tags') === TRUE) {
            echo eval('?>' . preg_replace('/;*\s*\?>/', '; ?>', str_replace('<?=', '<?php echo ', file_get_contents($_view_path))));
        } else {
            // include() vs include_once() allows for multiple views with the same name
            // 最大的重點
            // 將 view 限制在這區塊
            include($this->_view_path);
        }

        // log_message('info', 'File loaded: ' . $_view_path);
        $buffer = NULL;

        // Return the file data if requested
        if ($this->_ci_return === TRUE) {
            $buffer = ob_get_contents();
            @ob_end_clean();
        } else {
            /*
             * Flush the buffer... or buff the flusher?
             *
             * In order to permit views to be nested within
             * other views, we need to flush the content back out whenever
             * we are beyond the first level of output buffering so that
             * it can be seen and included properly by the first included
             * template and any subsequent ones. Oy!
             */
            if (ob_get_level() > $this->_ci_ob_level + 1) {
                ob_end_flush();
            } else {
                $buffer = ob_get_contents();
                $this->_ci->output->append_output($buffer);
                @ob_end_clean();
            }
        }
        return $buffer;
    }

    //--------------------------------------------------------------------------
    // 把 controller 的 參數拷貝到此
    // 方便 view load controller 的模組
    public function _cloneVars() {
        $container = &$this->_ci;

        $variableList = get_object_vars($container);
        foreach ($variableList as $_ci_key => $_ci_var) {
            if (!isset($this->$_ci_key)) {
               $this->$_ci_key = &$container->$_ci_key;
            }
        }
    }
    //--------------------------------------------------------------------------
    // 模版的上下文是 GloabalContainer
    public function __get($name) {
        return $this->_ci[$name];
    }
}


