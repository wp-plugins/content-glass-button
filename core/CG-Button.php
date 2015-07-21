<?php

/**
 * Copyright © 2009-2014 Rhizome Networks LTD All rights reserved.
 *
 * Created by IntelliJ IDEA.
 * User: Tomer Schilman
 * Date: 18/11/14
 * Time: 12:19
 */
require_once(__DIR__ . '/../utils/ModuleUtils.php');

class CgButton {
    public function __construct() {
        add_action('wp_loaded', array($this, 'cg_button_addition'));
    }

    function cg_button_addition() {
        $params = CGModuleUtils::get_customize_button_params();
        $queryParams = http_build_query($params);
        wp_register_script(
            'cg-draggable-button-script',
            plugins_url('/content-glass-button/templates/CustomizeButtonScript.php?' . $queryParams),
            false,
            false,
            false
        );
        wp_enqueue_script('cg-draggable-button-script');
    }
}