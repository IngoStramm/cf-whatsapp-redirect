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
                cwr_others_wpp_btn.addEventListener('click', () => {
                    if (typeof(dataLayer) != 'undefined' && dataLayer != null) {
                        dataLayer.push({
                            'event': 'whatsapp'
                        });
                    }
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
                    if (typeof(dataLayer) != 'undefined' && dataLayer != null) {
                        dataLayer.push({
                            'event': 'whatsapp'
                        });
                    }
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
                    if (typeof(dataLayer) != 'undefined' && dataLayer != null) {
                        dataLayer.push({
                            'event': 'whatsapp'
                        });
                    }
                });
            });
        };
    </script>
    <?php
    $cwr_css_classes = cwr_get_option('cwr_css_classes');
    if (!empty($cwr_css_classes) && !is_null($cwr_css_classes)) {
    ?>
        <script>
            window.onload = () => {
                const cwr_css_classes_array = [];
                <?php foreach ($cwr_css_classes as $cwr_css_class) { ?>
                    cwr_css_classes_array.push('<?php echo $cwr_css_class; ?>');
                <?php } ?>
                if (cwr_css_classes_array.length > 0) {
                    for (let index = 0; index < cwr_css_classes_array.length; index++) {
                        const cwr_wpp_elements = document.getElementsByClassName(cwr_css_classes_array[index]);
                        Array.prototype.forEach.call(cwr_wpp_elements, (cwr_wpp_element) => {
                            cwr_wpp_element.addEventListener('click', (evt) => {
                                if (typeof(dataLayer) != 'undefined' && dataLayer != null) {
                                    dataLayer.push({
                                        'event': 'whatsapp'
                                    });
                                }
                            });
                        });
                    }
                }
            };
        </script>
    <?php
    }
    ?>
<?php
}, 9999);
