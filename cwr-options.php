<?php

/**
 * Hook in and register a submenu options page for the Page post-type menu.
 */
add_action('cmb2_admin_init', function () {

    $cmb = new_cmb2_box(array(
        'id'           => 'cwr_options_submenu_page',
        'title'        => esc_html__('Opções do Converte Fácil: Whatsapp Redirect', 'cf-whatsapp-redirect'),
        'object_types' => array('options-page'),
        'option_key'      => 'cwr_page_options', // The option key and admin menu page slug.
        'parent_slug'     => 'options-general.php', // Make options page a submenu item of the themes menu.
        // 'capability'      => 'manage_options', // Cap required to view options-page.
        // 'save_button'     => esc_html__( 'Save Theme Options', 'cf-whatsapp-redirect' ), // The text for the options-page save button. Defaults to 'Save'.
    ));

    $cmb->add_field(array(
        'name'    => esc_html__('Observações', 'cf-whatsapp-redirect'),
        'id'      => 'cwr_instructions_1',
        'type' => 'title',
        'after' => function() {
            $output = '';
            $output .= '&nbsp;';
            $output .= '<ul>';
            $output .= '<li>' . esc_html__('Por padrão, este plugin já faz o rastreio do botão do plugin "Click to Chat". Para isso, é usada como referência a classe CSS "ht-ctc-chat", que o próprio plugin "Click to Chat" adiciona.', 'cf-whatsapp-redirect') . '</li>';
            $output .= '<li>' . esc_html__('Para incluir o rastreio em outros elementos que acionem o chat do Whatsapp, adicione a classe CSS "trigger-gtm-wpp" a estes elementos.', 'cf-whatsapp-redirect') . '</li>';
            $output .= '<li>' . esc_html__('Caso não seja possível adicionar uma classe CSS a um elemento, é possível utilizar uma classe CSS que o próprio elemento possui, através das opções abaixo.', 'cf-whatsapp-redirect') . '</li>';
            $output .= '</ul>';
            $output .= '&nbsp;';
            return $output;
        }
    ));

    $cmb->add_field(array(
        'name'    => esc_html__('Classe CSS direta dos elementos que abrem a janela de chat do Whastapp', 'cf-whatsapp-redirect'),
        'desc'    => esc_html__('Os elementos podem ser um botão, um link, um ícone ou qualquer elemento que seja usado para redirecionar o usuário para a janela de chat do Whastapp.', 'cf-whatsapp-redirect'),
        'id'      => 'cwr_css_classes',
        'type'    => 'text',
        'repeatable' => true,
    ));

    $cmb->add_field(array(
        'name'    => esc_html__('Classe CSS pai dos elementos que abrem a janela de chat do Whastapp', 'cf-whatsapp-redirect'),
        'desc'    => esc_html__('Os elementos podem ser uma div, section ou qualquer outro elemento que possua um botão, link, ícone, etc, que seja usado para redirecionar o usuário para a janela de chat do Whastapp.', 'cf-whatsapp-redirect'),
        'id'      => 'cwr_parent_css_classes',
        'type'    => 'text',
        'repeatable' => true,
    ));

    $cmb->add_field(array(
        'name'    => esc_html__('Opções Extras', 'cf-whatsapp-redirect'),
        'id'      => 'cwr_instructions_2',
        'type' => 'title',
        'after' => function () {
            $output = '';
            $output .= '&nbsp;';
            $output .= '<p>' . esc_html__('Nas opções abaixo, é possível habilitar ou desativar alguns recursos do plugin.', 'cf-whatsapp-redirect') . '</p>';
            $output .= '&nbsp;';
            return $output;
        }
    ));

    $cmb->add_field(array(
        'name' => esc_html__('Desabilitar o rastreio do botão do RD-Station', 'cmb2'),
        'desc' => esc_html__('Por padrão, este plugin procura o botão do whatsapp inserido pelo RD-Station e faz o rastreio dele. Se o seu site não utiliza o botão do Whatsapp gerado pelo RD-Station, deixe esta opção MARCADA para desativar este recurso.', 'cmb2'),
        'id'   => 'cwr_disable_rd_station_tracking',
        'type' => 'checkbox',
    ));

    $cmb->add_field(array(
        'name' => esc_html__('Habilitar a opção de procurar todos os links do site que possuam a palavra "whatsapp" no atributo "href"', 'cmb2'),
        'desc' => esc_html__('Com esta opção ativada, não é necessário utilizar uma classe CSS para buscar os links do botão do Whatsapp. Obs: não funciona com o botão gerado pelo RD Station.', 'cmb2'),
        'id'   => 'cwr_enable_all_links',
        'type' => 'checkbox',
    ));

    $cmb->add_field(array(
        'name' => esc_html__('Habilitar a opção de definir o nome do evento dinamicamente a partir da url da página', 'cmb2'),
        'desc' => esc_html__('Com esta opção ativada, o nome do evento de rastreio terá o sufixo com o slug da página de onde o evento se originou. Por exemplo, na página "https://seusite.com.br/contato", o nome do evento de rastreio ficaria "whatsapp.contato".', 'cmb2'),
        'id'   => 'cwr_enable_dynamic_event_name',
        'type' => 'checkbox',
    ));


});
