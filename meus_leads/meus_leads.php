<?php
/*
Plugin Name: Meus Leads
Description: Gerenciamento simples de leads.
Version: 1.0
Author: Alexandre Afonso
Author URI: https://alexandreafonso.com.br
*/

//error_reporting(E_ALL);
//define( ‘WP_DEBUG’, true );


// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}



// Cria a tabela meus_leads no banco de dados do Wordpress
function criar_tabela_meus_leads() {
    global $wpdb;

    $charset_collate = $wpdb->get_charset_collate();

    $table_name = $wpdb->prefix . 'meus_leads';

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id int(11) NOT NULL AUTO_INCREMENT,
        nome varchar(255) NOT NULL,
        email varchar(255),
        whatsapp varchar(255),
        origem varchar(255),
        data_cadastro DATETIME DEFAULT CURRENT_TIMESTAMP,
        data_atualizacao DATETIME ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

    dbDelta( $sql );
}

// Registra os hooks e actions do plugin
register_activation_hook( __FILE__, 'criar_tabela_meus_leads' );

// Remove a tabela meus_leads do banco de dados do Wordpress
function remover_tabela_meus_leads() {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'meus_leads';

    $wpdb->query( "DROP TABLE IF EXISTS $table_name" );
}

//register_deactivation_hook( __FILE__, 'remover_tabela_meus_leads' );
register_uninstall_hook( __FILE__, 'remover_tabela_meus_leads' );

// Adiciona o item Meus Leads ao menu principal do Wordpress
function adicionar_item_meus_leads_menu() {
    add_menu_page(
        'Meus Leads', // Título da página (aba do navegador)
        'Meus Leads', // Nome do menu
        'manage_options', // Permissão necessária
        'meus-leads', // Slug
        'exibir_tela_lista_leads', // Callback
        'dashicons-groups', // Icone
        1, // Posição em que o menu será inserido
    );

    add_submenu_page(
        'meus-leads',
        'Cadastro',
        'Cadastro',
        'manage_options',
        'cadastro-lead',
        'exibir_tela_cadastro_lead'
    );

    add_submenu_page(
        'meus-leads',
        'Edição',
        null, // Null quer dizer que esse menu não será exibido. Isso é util para termos paginas, por exemplo, de edição.
        'manage_options',
        'edicao-lead',
        'exibir_tela_edicao_lead'
    );
}

add_action( 'admin_menu', 'adicionar_item_meus_leads_menu' );

// Exibe a tela de lista de leads
function exibir_tela_lista_leads() {
    $pagina = $_GET['pagina'];
    $quantidade_por_pag = 100;

    if( !isset($pagina) || $pagina < 1 ) {
        $pagina = 1;
    }

    $pesquisa = $_GET['pesquisa'];
    $where_pesquisa = "";
    if(isset($pesquisa) && !empty($pesquisa)) {
        $pesquisa = sanitize_text_field($pesquisa);
        $where_pesquisa = " where nome like '%$pesquisa%' or email like '%$pesquisa%' or origem like '%$pesquisa%'";
    }

    global $wpdb;

    $table_name = $wpdb->prefix . 'meus_leads';

    $info = $wpdb->get_row("select count(id) as quantidade_registros from $table_name " . (empty($where_pesquisa) ? "" : $where_pesquisa));

    $quantidade_paginas = ceil($info->quantidade_registros / $quantidade_por_pag);

    if($pagina > $quantidade_paginas) {
        $pagina = $quantidade_paginas;
    }

    $registro_inicial = ($pagina - 1) * $quantidade_por_pag;

    $sql = "SELECT * FROM $table_name " . (empty($where_pesquisa) ? "" : $where_pesquisa);    

    $leads = $wpdb->get_results( $sql . " limit $registro_inicial, $quantidade_por_pag" );

    include( plugin_dir_path( __FILE__ ) . 'templates/lista-leads.php' );
}

// Exibe a tela de cadastro de leads
function exibir_tela_cadastro_lead() {
    include( plugin_dir_path( __FILE__ ) . 'templates/cadastro-lead.php' );
}

// Exibe a tela de edição de um lead
function exibir_tela_edicao_lead() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'meus_leads';

    $id = $_GET['id'];

    $lead = $wpdb->get_row( "SELECT * FROM $table_name WHERE id = $id" );

    include( plugin_dir_path( __FILE__ ) . 'templates/edicao-lead.php' );
}





// Salva um lead no banco de dados
function cadastrar_lead() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'meus_leads';
    
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $whatsapp = $_POST['whatsapp'];
    $origem = 'admin'; //$_POST['origem'];

    $wpdb->insert( $table_name, 
        array(
            'nome' => $nome,
            'email' => $email,
            'whatsapp' => $whatsapp,
            'origem' => $origem
        )
    );

    wp_redirect( admin_url( 'admin.php?page=meus-leads' ) );

    exit();
}

add_action( 'admin_post_cadastrar-lead', 'cadastrar_lead' );





// Atualiza um lead no banco de dados
function atualizar_lead() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'meus_leads';

    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $whatsapp = $_POST['whatsapp'];
    $origem = $_POST['origem'];

    $wpdb->update( $table_name, array(
        'nome' => $nome,
        'email' => $email,
        'whatsapp' => $whatsapp,
        'origem' => $origem
    ), array(
        'id' => $id,
    ) );

    wp_redirect( admin_url( 'admin.php?page=meus-leads' ) );
    exit();
}

add_action( 'admin_post_atualizar-lead', 'atualizar_lead' );





// Remove um lead do banco de dados
function remover_lead() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'meus_leads';

    $id = $_POST['id'];

    $wpdb->delete( $table_name,
        array(
            'id' => $id,
        )   
    );

    wp_redirect( admin_url( 'admin.php?page=meus-leads' ) );

    exit();
}

add_action( 'admin_post_remover-lead', 'remover_lead' );


function download_leads() {
    //ini_set(default_charset, "utf-8");
    header("Content-Type: application/csv; charset=UTF-8"); //; charset=UTF-8
    header('Content-Disposition: attachment; filename="meus_leads.csv";');
    header("Content-Encoding: UTF-8");
    
    // $delimitador = ";";
    // $output = fopen('php://output', 'w');    
    // fputcsv($output, array("ID", "NOME", "EMAIL", "WHATSAPP", "ORIGEM", "DATA CADASTRO", "DATA ATUALIZAÇÃO"), $delimitador);

    $csv = "ID;NOME;EMAIL;WHATSAPP;ORIGEM;DATA CADASTRO;DATA ATUALIZAÇÃO\n";
    
    global $wpdb;
    $table_name = $wpdb->prefix . 'meus_leads';
    $leads = $wpdb->get_results( "SELECT id, nome, email, whatsapp, origem, data_cadastro, data_atualizacao FROM $table_name" );

    foreach ( $leads as $lead ) {
        $linha = $lead->id . ";" . $lead->nome . ";" . $lead->email . ";" . $lead->whatsapp . ";" . $lead->origem . ";" . $lead->data_cadastro . ";" . $lead->data_atualizacao . "\n";
        $csv .= $linha;

        //$linha = array($lead->id, $lead->nome, $lead->email, $lead->whatsapp, $lead->origem, $lead->data_cadastro, $lead->data_atualizacao);
        //fputcsv($output, $linha, $delimitador);
    }

    //fclose($output);

    echo $csv;

    exit();
}

add_action( 'admin_post_download-leads', 'download_leads' );












/*
function download_leads() {
    header("Content-Type: application/csv; charset=UTF-8"); //; charset=UTF-8
    header('Content-Disposition: attachment; filename="meus_leads.csv";');
    header("Content-Encoding: UTF-8");
    
    $delimitador = ";";
    $output = fopen('php://output', 'w');    
    fputcsv($output, array("ID", "NOME", "EMAIL", "WHATSAPP", "ORIGEM", "DATA CADASTRO", "DATA ATUALIZAÇÃO"), $delimitador);
    
    global $wpdb;
    $table_name = $wpdb->prefix . 'meus_leads';
    $leads = $wpdb->get_results( "SELECT id, nome, email, whatsapp, origem, data_cadastro, data_atualizacao FROM $table_name" );

    foreach ( $leads as $lead ) {
        //$linha = $lead->id . ";" . $lead->nome . ";" . $lead->email . ";" . $lead->whatsapp . ";" . $lead->origem . ";" . $lead->data_cadastro . ";" . $lead->data_atualizacao . "\n";
        //fputcsv($output, $linha, $delimitador);

        $linha = array($lead->id, $lead->nome, $lead->email, $lead->whatsapp, $lead->origem, $lead->data_cadastro, $lead->data_atualizacao);
        fputcsv($output, $linha, $delimitador);
    }

    fclose($output);

    exit();
}
*/

/*
Exemplo de form html da langindpage

<style>
    .cadastro input {
        display: block;
        width: 100%;
        margin: 10px 0;
        padding: 10px;
        border: 1px solid #09A8EC;
        border-radius: 3px;
    }
    .cadastro button {
        display: block;
        width: 100%;
        background-color: transparent;
        background-image: linear-gradient(180deg, var( --e-global-color-057a9ac ) 0%, #0E8BC0 100%);
        box-shadow: 0px 0px 7px -2px rgba(0,0,0,0.5);
        color: #ffffff;
        border: 0;
        text-transform: uppercase;
        font-size: 24px
    }
    .cadastro button:hover {
        transform: scale(1.1);
    }
</style>

<form class="cadastro" method="post" action="/wp-content/plugins/meus_leads/cadastrar-lead-landingpage.php">
    <input type="hidden" name="action" value="cadastrar-lead-landingpage">

    <input type="hidden" name="origem" value="recado-aos-pais" />
    <input type="hidden" name="url" value="/recado-aos-pais-video" />

    <input placeholder="Nome" name="nome" required />
    <input placeholder="Email" name="email" type="email" required/>
    <input placeholder="WhatsApp" name="whatsapp" required/>

    <button>Quero saber AGORA!</button>
</form>

*/

?>