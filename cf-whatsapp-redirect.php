<?php

/**
 * Plugin Name: Converte Fácil: Whatsapp Redirect
 * Plugin URI: https://agencialaf.com
 * Description: Descrição do Converte Fácil: Whatsapp Redirect.
 * Version: 1.0.3
 * Author: Ingo Stramm
 * Text Domain: cf-whatsapp-redirect
 * License: GPLv2
 */

defined('ABSPATH') or die('No script kiddies please!');

define('CWR_DIR', plugin_dir_path(__FILE__));
define('CWR_URL', plugin_dir_url(__FILE__));

function cwr_debug($debug)
{
    echo '<pre>';
    var_dump($debug);
    echo '</pre>';
}

// require_once 'tgm/tgm.php';
// require_once 'classes/classes.php';
// require_once 'scripts.php';

add_action('wp_footer', function () {
?>
    <script>
        // Caso o botão do whatsapp tenha sido adicionado pelo RD Station
        window.onload = () => {
            let count = 1;
            let cwr_verifica_btn = setInterval(() => {

                const cwr_wpp_floating_btns = document.getElementsByClassName('rdstation-popup-js-floating-button');
                Array.prototype.forEach.call(cwr_wpp_floating_btns, (cwr_wpp_floating_btn) => {
                    const cwr_parent_wrapper = cwr_wpp_floating_btn.parentNode;
                    const cwr_fields = cwr_parent_wrapper.getElementsByClassName('bricks-form__input');
                    const cwr_wpp_btns = cwr_parent_wrapper.getElementsByClassName('rdstation-popup-js-submit-button');
                    const cwr_form = cwr_parent_wrapper.getElementsByTagName('form');

                    if (cwr_fields.length > 0 && cwr_wpp_btns.length > 0 && cwr_form.length > 0) {

                        clearInterval(cwr_verifica_btn);
                        cwr_wpp_btns[0].addEventListener('click', (evt) => {

                            let validate_fields = true;
                            let cwr_fields_data_object = {};
                            cwr_fields_data_object.event = 'whatsapp';
                            Array.prototype.forEach.call(cwr_fields, (cwr_field, i) => {
                                if (cwr_field.value.length === 0 || cwr_field.parentNode.classList.contains('has-danger')) {
                                    validate_fields = false;
                                }
                                if (i === 2 && cwr_field.value.length <= 4 || cwr_field.parentNode.classList.contains('has-danger')) {
                                    validate_fields = false;
                                }
                                // console.log(cwr_field);
                                const k = cwr_field.name;
                                const v = cwr_field.value;
                                cwr_fields_data_object[k] = v;
                            });

                            // console.log(cwr_fields_data_object);

                            if (validate_fields === true) {
                                // debugger;
                                dataLayer.push(cwr_fields_data_object);
                            }

                        });

                    } else {
                        console.log('não encontrou todos os elementos na tentantiva #', count)
                    }
                    count++;
                });

            }, 1000);

            const cwr_others_wpp_btns = document.getElementsByClassName('trigger-gtm-wpp');
            Array.prototype.forEach.call(cwr_others_wpp_btns, (cwr_others_wpp_btn) => {
                cwr_others_wpp_btn.addEventListener('click', () => {
                    // debugger;
                    dataLayer.push({
                        'event': 'whatsapp'
                    });
                });
            });
        };
    </script>
    <script>
        // Caso haja botões inseridos através do Elementor (ou de outra forma)
        // basta adicionar a classe "trigger-gtm-wpp" no botão/link
        window.onload = () => {
            const cwr_others_wpp_btns = document.getElementsByClassName('trigger-gtm-wpp');
            Array.prototype.forEach.call(cwr_others_wpp_btns, (cwr_others_wpp_btn) => {
                cwr_others_wpp_btn.addEventListener('click', () => {
                    // debugger;
                    dataLayer.push({
                        'event': 'whatsapp'
                    });
                });
            });
        };
    </script>
    <script>
        // Caso o botão do Whatsapp tenha sido adicionado pelo plugin Click to Chat (padrão nos CF)
        window.onload = () => {
            const ctc_wpp_divs = document.getElementsByClassName('ht-ctc-chat');
            Array.prototype.forEach.call(ctc_wpp_divs, (ctc_wpp_div) => {
                ctc_wpp_div.addEventListener('click', (evt) => {
                    dataLayer.push({
                        'event': 'whatsapp'
                    });
                });
            });
        };
    </script>
<?php
}, 9999);

require 'plugin-update-checker-4.10/plugin-update-checker.php';
$updateChecker = Puc_v4_Factory::buildUpdateChecker(
    'https://raw.githubusercontent.com/IngoStramm/cf-whatsapp-redirect/master/info.json',
    __FILE__,
    'cf-whatsapp-redirect'
);
