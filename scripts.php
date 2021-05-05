<?php

add_action('wp_enqueue_scripts', 'cwr_frontend_scripts');

function cwr_frontend_scripts()
{

    $min = (in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1', '10.0.0.3'))) ? '' : '.min';

    if (empty($min)) :
        wp_enqueue_script('cf-whatsapp-redirect-livereload', 'http://localhost:35729/livereload.js?snipver=1', array(), null, true);
    endif;

    wp_register_script('cf-whatsapp-redirect-script', CWR_URL . 'assets/js/cf-whatsapp-redirect' . $min . '.js', array('jquery'), '1.0.0', true);

    wp_enqueue_script('cf-whatsapp-redirect-script');

    wp_localize_script('cf-whatsapp-redirect-script', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
    wp_enqueue_style('cf-whatsapp-redirect-style', CWR_URL . 'assets/css/cf-whatsapp-redirect.css', array(), false, 'all');
}
