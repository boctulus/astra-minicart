<?php

/*
	Plugin Name: Astra MiniCart
	Description: Places a mini-cart icon just in the navbar with access to the cart
	Version: 0.0.1
	Author: Pablo Bozzolo < boctulus@gmail.com >
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// Registra la función para que se ejecute durante la carga de scripts y estilos en WordPress
add_action('wp_enqueue_scripts', function(){
    // Obtener la URL del directorio del plugin
    $plugin_dir_url = plugin_dir_url(__FILE__);

    // Cargar jQuery desde la carpeta del plugin
    wp_enqueue_script('jquery', $plugin_dir_url . 'assets/third_party/jquery/3.3.1/jquery.min.js', array(), '3.3.1', true);
});


### MINICART
function mini_cart_content()
{
    if (function_exists('env') && !empty(env('WC_CART_SLUG'))){
        $cart_slug = env('WC_CART_SLUG');
    } else {
        $config    = include __DIR__ . '/config/config.php';
        $cart_slug = $config['wc_cart_slug'];
    }

    ?>          
        <script>
            jQuery(document).ready(function () {
                // Version PC
                var cartIcon = jQuery('<li class="menu-item cart-icon" id="mini_cart-pc" style="list-style-type: none; margin-left:20px"><a href="<?= $cart_slug ?>"><i class="fas fa-shopping-cart" style="color: #C0C0C0;"></i></a></li>');

                // Insertar el icono del carrito al final del menú
                jQuery('.main-header-menu').append(cartIcon);

                // Version Movil
                var cartIcon = jQuery('<span class="menu-item cart-icon" id="mini_cart-mobile" style="margin-left: 20px;"><a href="<?= $cart_slug ?>"><i class="fas fa-shopping-cart" style="color: #C0C0C0;"></i></a></span>');

                // Insertar el icono del carrito como primer hijo del elemento con la clase .ast-button-wrap
                jQuery('.ast-button-wrap').parent().prepend(cartIcon);
            });
        </script>
    <?php
}

add_shortcode( 'custom_mini_cart', 'mini_cart_content' );

add_action('wp_footer', function(){
    do_shortcode('[custom_mini_cart]');
});


####
#
# Corrije redireccion a /cart si debe ser por ejemplo a /carrello =
#

$cfg       = include __DIR__ . '/config/config.php';
$cart_slug = $cfg['wc_cart_slug'];

$current_url = rtrim($_SERVER['REQUEST_URI'], '/');

if (rtrim($cart_slug, '/') != '/cart' && $current_url == '/cart') {
    header("Location: $cart_slug", true, 301);
    exit();
}