<?php

function interceptor_1() {
    

    $ci = get_instance();

    $ci->load->model("news_model");
    
    $data = $ci->news_model->getName();

    printf("<p>interceptor_1</p>");
    echo '<pre>';
    print_r($data);
    echo '</pre>';

    // throw new Exception('interceptor_1');
}
