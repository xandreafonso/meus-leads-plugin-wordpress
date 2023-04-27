<?php

/*
/wp-content/plugins/meus_leads/cadastrar-lead-landingpage.php
*/

require_once('../../../wp-load.php');

global $wpdb;

$table_name = $wpdb->prefix . 'meus_leads';

$nome = $_POST['nome'];
$email = $_POST['email'];
$whatsapp = $_POST['whatsapp'];
$origem = $_POST['origem'];

$lead_existente = $wpdb->get_row(
    $wpdb->prepare( "SELECT * FROM $table_name WHERE email = %s and whatsapp = %s and origem = %s limit 1", $email, $whatsapp, $origem)
);

if($lead_existente) {
    $wpdb->update( $table_name, array(
        'nome' => $nome,
        'email' => $email,
        'whatsapp' => $whatsapp
    ), array(
        'id' => $lead_existente->id,
    ) );
} else {
    $wpdb->insert( $table_name, 
        array(
            'nome' => $nome,
            'email' => $email,
            'whatsapp' => $whatsapp,
            'origem' => $origem
        )
    );
}

$url = $_POST['url'];

wp_safe_redirect( $url );

exit();
?>