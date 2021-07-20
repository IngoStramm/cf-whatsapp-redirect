<?php

function cwr_get_option($key = '', $default = false)
{
    if (function_exists('cmb2_get_option')) {
        // Use cmb2_get_option as it passes through some key filters.
        return cmb2_get_option('cwr_page_options', $key, $default);
    }

    // Fallback to get_option if CMB2 is not loaded yet.
    $opts = get_option('cwr_page_options', $default);

    $val = $default;

    if ('all' == $key) {
        $val = $opts;
    } elseif (is_array($opts) && array_key_exists($key, $opts) && false !== $opts[$key]) {
        $val = $opts[$key];
    }

    return $val;
}

add_action('wp_footer', function () {
?>
    <?php
    $cwr_enable_dynamic_event_name = cwr_get_option('cwr_enable_dynamic_event_name');
    $cwr_event_name = 'whatsapp';
    if ($cwr_enable_dynamic_event_name === 'on') {
        global $post;
        $cwr_event_name .=  '.' . $post->post_name;
    }
    // cwr_debug($cwr_event_name);
    ?>
    <script>
        const cwr_trigger_element_evt = (cwr_wpp_element) => {
            cwr_wpp_element.addEventListener('click', (evt) => {
                // evt.preventDefault();
                // console.log('click');
                console.log('<?php echo $cwr_event_name; ?>');
                if (typeof(dataLayer) != 'undefined' && dataLayer != null) {
                    dataLayer.push({
                        'event': '<?php echo $cwr_event_name; ?>'
                    });
                }
            });
        };
    </script>
    <?php
    $cwr_disable_rd_station_tracking = cwr_get_option('cwr_disable_rd_station_tracking');
    // cwr_debug($cwr_disable_rd_station_tracking);
    ?>
    <script>
        console.log('footer');
        // Caso o botão do whatsapp tenha sido adicionado pelo RD Station
        const cwr_rd_wpp = () => {
            <?php if ($cwr_disable_rd_station_tracking === 'on') { ?>
                return;
            <?php } ?>
            console.log('load');
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
                            cwr_fields_data_object.event = '<?php echo $cwr_event_name; ?>';
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
                                if (typeof(dataLayer) != 'undefined' && dataLayer != null) {
                                    dataLayer.push(cwr_fields_data_object);
                                }
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
                cwr_trigger_element_evt(cwr_others_wpp_btn);
            });
        };
    </script>
    <script>
        // Caso haja botões inseridos através do Elementor (ou de outra forma)
        // basta adicionar a classe "trigger-gtm-wpp" no botão/link
        const cwr_trigger_gtm_wpp = () => {
            console.log('cwr_trigger_gtm_wpp');
            const cwr_others_wpp_btns = document.getElementsByClassName('trigger-gtm-wpp');
            Array.prototype.forEach.call(cwr_others_wpp_btns, (cwr_others_wpp_btn) => {
                cwr_trigger_element_evt(cwr_others_wpp_btn);
            });
        };
    </script>
    <script>
        // Caso o botão do Whatsapp tenha sido adicionado pelo plugin Click to Chat (padrão nos CF)
        const cwr_click_to_chat = () => {
            <?php
            $cwr_get_active_plugins = get_option('active_plugins');
            if (!in_array('click-to-chat-for-whatsapp/click-to-chat.php', $cwr_get_active_plugins)) { ?>
                return;
            <?php } ?>
            console.log('cwr_click_to_chat');
            const ctc_wpp_divs = document.getElementsByClassName('ht-ctc-chat');
            Array.prototype.forEach.call(ctc_wpp_divs, (ctc_wpp_div) => {
                cwr_trigger_element_evt(ctc_wpp_div);
            });
        };
    </script>
    <script>
        const cwr_css_classes_evt = (cwr_css_classes_array, parent = false) => {
            if (cwr_css_classes_array.length > 0) {
                for (let index = 0; index < cwr_css_classes_array.length; index++) {
                    const cwr_wpp_elements = document.getElementsByClassName(cwr_css_classes_array[index]);
                    Array.prototype.forEach.call(cwr_wpp_elements, (cwr_wpp_element) => {
                        let cwr_trigger_element;
                        if (!parent) {
                            cwr_trigger_element_evt(cwr_wpp_element);
                        } else {
                            const cwr_wpp_elements_links = cwr_wpp_element.getElementsByTagName('a');
                            Array.prototype.forEach.call(cwr_wpp_elements_links, (cwr_wpp_elements_link) => {
                                cwr_trigger_element_evt(cwr_wpp_elements_link);
                            });
                        }
                    });
                }
            }
        };
    </script>
    <?php
    $cwr_direct_css_classes = cwr_get_option('cwr_direct_css_classes');
    ?>
        <script>
            const cwr_direct_link_classes = () => {
                <?php if (!empty($cwr_direct_css_classes) && !is_null($cwr_direct_css_classes)) { ?>
                    return;
                <?php } ?>
                const cwr_direct_css_classes_array = [];
                <?php foreach ($cwr_direct_css_classes as $cwr_css_class) { ?>
                    cwr_direct_css_classes_array.push('<?php echo $cwr_css_class; ?>');
                <?php } ?>
                cwr_css_classes_evt(cwr_direct_css_classes_array);
            };
        </script>
    <?php
    }
    ?>
    <script>
        const cwr_parent_link_classes = () => {
            <?php
            $cwr_parent_css_classes = cwr_get_option('cwr_parent_css_classes');
            if (empty($cwr_parent_css_classes) || is_null($cwr_parent_css_classes)) { ?>
                return;
            <?php } else { ?>
                // console.log('cwr_parent_link_classes');
                const cwr_parent_css_classes_array = [];
                <?php foreach ($cwr_parent_css_classes as $cwr_css_class) { ?>
                    cwr_parent_css_classes_array.push('<?php echo $cwr_css_class; ?>');
                <?php } ?>
                cwr_css_classes_evt(cwr_parent_css_classes_array, true);
            <?php } ?>
        };
    </script>
    <?php
    $cwr_enable_all_links = cwr_get_option('cwr_enable_all_links');
    // cwr_debug($cwr_enable_all_links);
    ?>
    <script>
        const cwr_all_links_evt = () => {
            <?php if ($cwr_enable_all_links !== 'on') { ?>
                return;
            <?php } ?>
            console.log('cwr_enable_all_links');
            const cwr_all_links = document.getElementsByTagName('a');
            Array.prototype.forEach.call(cwr_all_links, (cwr_link) => {
                if (cwr_link.href.includes('whatsapp')) {
                    console.log(cwr_link.href);
                    cwr_trigger_element_evt(cwr_link);
                }
            });
        };
    </script>
    <script>
        window.onload = () => {
            cwr_rd_wpp();
            cwr_trigger_gtm_wpp();
            cwr_click_to_chat();
            cwr_direct_link_classes();
            cwr_parent_link_classes();
            cwr_all_links_evt();
        };
    </script>
<?php
}, 9999);
