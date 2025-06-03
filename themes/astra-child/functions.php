<?php
// Cargar los estilos del tema padre y luego los del hijo
add_action( 'wp_enqueue_scripts', function() {
    // Estilo del tema padre (Astra)
    wp_enqueue_style( 'astra-parent-style', get_template_directory_uri() . '/style.css' );

    // Estilo del tema hijo (asegúrate de que exista style.css en el child theme)
    wp_enqueue_style( 'astra-child-style', get_stylesheet_directory_uri() . '/style.css', array('astra-parent-style') );
});
