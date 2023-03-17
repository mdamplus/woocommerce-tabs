<?php
/*
Plugin Name: Custom Tabs for Woocommerce - Informaticasa
Plugin URI: https://informaticasa.es
Description: This plugin allow to insert a custom tab to any product. <a href="https://github.com/mdamplus/KitDigital">Git Hub Plugin DATA</a> | <a href="https://martinarnedo.es/">Author Website</a> | <a href="https://informaticasa.es/servicios/kit-digital/">Kit Digital</a>
Version:2023.3
Author: Martín D. Arnedo Mahr
Author URI: https://github.com/mdamplus/woocommerce-tabs

License: GPL2
Text Domain:mdamz
*/


// Agrega una custom a la página de productos
add_filter( 'woocommerce_product_tabs', 'custom_tabs_with_editable_content' );

function custom_tabs_with_editable_content( $tabs ) {
	// Agrego la tab de Información nutricional
	$tabs['custom_tab'] = array(
		'title' 	=> __( 'Información nutricional', 'woocommerce' ),
		'priority' 	=> 50,
		'callback' 	=> 'custom_tab_content'
	);

	return $tabs;
}

// Contenido de la pestaña
function custom_tab_content() {
	// Obtenemos el current product ID
	$product_id = get_the_ID();

	// Obtenemos el contenido de la pestaña personalizada para el producto actual
	$custom_tab_content = get_post_meta( $product_id, '_custom_tab_content', true );

	// Muestro el contenido de la pestaña personalizada
	echo do_shortcode( wp_kses_post( $custom_tab_content ) );
}

// Agregar un campo personalizado al metabox del producto
add_action( 'woocommerce_product_options_general_product_data', 'custom_tab_content_field' );

function custom_tab_content_field() {
	global $woocommerce, $post;

	echo '<div class="options_group">';

	// Custom tab dentro del producto, lo que lee el cliente.
	woocommerce_wp_textarea_input(
		array(
			'id'          => '_custom_tab_content',
			'label'       => __( 'Contenido para la pestaña de Información Nutricional', 'woocommerce' ),
			'placeholder' => '',
			'description' => __( 'Ingrese o peque aquí los valores nutricionales para este producto. Este bloque permite el uso de código HTML, shortcodes y texto normal.', 'woocommerce' ),
			'value'       => get_post_meta( $post->ID, '_custom_tab_content', true )
		)
	);

	echo '</div>';
}

// Guarda el contenido generado
add_action( 'woocommerce_process_product_meta', 'save_custom_tab_content_field' );

function save_custom_tab_content_field( $post_id ) {
	// Guardamos!!!
	$custom_tab_content = $_POST['_custom_tab_content'];
	if ( ! empty( $custom_tab_content ) ) {
		update_post_meta( $post_id, '_custom_tab_content', $custom_tab_content );
	} else {
		delete_post_meta( $post_id, '_custom_tab_content' );
	}
}
?>
