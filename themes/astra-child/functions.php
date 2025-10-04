<?php
// Cargar los estilos del tema padre y luego los del hijo
add_action( 'wp_enqueue_scripts', function() {
    // Estilo del tema padre (Astra)
    wp_enqueue_style( 'astra-parent-style', get_template_directory_uri() . '/style.css' );

    // Estilo del tema hijo (asegúrate de que exista style.css en el child theme)
    wp_enqueue_style( 'astra-child-style', get_stylesheet_directory_uri() . '/style.css', array('astra-parent-style') );
});

// Create Configuration pages on theme activation
function create_configuration_pages() {
    // Create parent page - Configuration
    $parent_page = array(
        'post_title'    => 'Configuration',
        'post_content'  => '',
        'post_status'   => 'publish',
        'post_type'     => 'page'
    );
    
    $parent_id = wp_insert_post($parent_page);

    // Create child page - Edit
    $child_page = array(
        'post_title'    => 'Edit',
        'post_content'  => '',
        'post_status'   => 'publish',
        'post_type'     => 'page',
        'post_parent'   => $parent_id,
        'page_template' => 'configuration.php'  // Use our custom template
    );
    
    wp_insert_post($child_page);
}

add_action('after_switch_theme', 'create_configuration_pages');

//Este fragmento debe ir dentro del functions.php del astra child y de ese modo 
//se ejecutara en todo el flujo de WordPress.

//Se hace uso del gancho um_registration_complete para que cuando se registre un estudiante, se genere un codigo unico en la base de datos en la tabla user_meta con el tag codigo_estudiante.
add_action('user_register', 'neureka_generar_codigo_estudiante', 10, 1);

function neureka_generar_codigo_estudiante($user_id) {
    $user = get_userdata($user_id);

    // Solo si es estudiante
    if ( in_array('um_student', (array) $user->roles) ) {
        $codigo = substr(bin2hex(random_bytes(16)), 0, 6);
        update_user_meta($user_id, 'students_code', $codigo);
        error_log("✅ Código generado para ESTUDIANTE {$user_id}: {$codigo}");
    }
}

//Update: if a person is already logged if, can have access to the Dashboard. 
function neureka_restricted_dashboard() {
    if ( is_page( 'dashboard') && !is_user_logged_in() ) {
        wp_redirect( site_url('/login/') ); 
        exit;
    }
}
add_action('template_redirect', 'neureka_restricted_dashboard');

//To avoid that a person who is already logged in can go to the login or register pages, we will make that the person can watch only the dashboard in these cases.
function neureka_restricted_pages() {
    if ( ( is_front_page() || is_page('login-2') || is_page('role-options') || is_page('parent-register') || is_page('student-register') || is_page('teacher-register') ) && is_user_logged_in() ) {
        wp_redirect( site_url('/dashboard/') ); // cambia "/login/" por tu slug real
        exit;
    }
}
add_action('template_redirect', 'neureka_restricted_pages');