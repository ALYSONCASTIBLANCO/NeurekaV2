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
        $codigo_unico = false;
        $intentos = 0;

        while (!$codigo_unico && $intentos < 10) { // 10 intentos como límite de seguridad
            $codigo = substr(bin2hex(random_bytes(16)), 0, 6);

            // Verificar si ya existe ese código en algún usuario
            $args = array(
                'meta_key'   => 'students_code',
                'meta_value' => $codigo,
                'number'     => 1,
                'fields'     => 'ids',
            );
            $user_query = new WP_User_Query($args);

            if ( empty($user_query->get_results()) ) {
                // No existe, es único
                $codigo_unico = true;
                update_user_meta($user_id, 'students_code', $codigo);
                error_log("✅ Código generado para ESTUDIANTE {$user_id}: {$codigo}");
            } else {
                $intentos++;
            }
        }

        if (!$codigo_unico) {
            error_log("⚠️ No se pudo generar un código único para el usuario {$user_id} después de {$intentos} intentos.");
        }
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

        $current_user = wp_get_current_user();

        // Si el usuario es estudiante o padre
        if ( in_array('um_student', (array) $current_user->roles) || in_array('um_parent', (array) $current_user->roles) ) {
            wp_redirect( site_url('/dashboard/') );
            exit;
        }

        // Si el usuario es tutor
        else if ( in_array('um_tutor', (array) $current_user->roles) ) {
            wp_redirect( site_url('/teacher-dashboard/') );
            exit;
        }

        // Si el usuario es administrador
        else if ( in_array('administrator', (array) $current_user->roles) ) {
            wp_redirect( site_url('/wp-admin/') );
            exit;
        }
    }
}
add_action('template_redirect', 'neureka_restricted_pages');
function identify_roles() {
    if ( is_user_logged_in() ) {

        $current_user = wp_get_current_user();

        // Si el usuario es estudiante o padre
        if ( ( in_array('um_student', (array) $current_user->roles) || in_array('um_parent', (array) $current_user->roles) ) && is_page('teacher-dashboard') ) {
            wp_redirect( site_url('/dashboard/') );
            exit;
        }

        // Si el usuario es tutor
        else if ( in_array('um_tutor', (array) $current_user->roles) && is_page('dashboard') ) {
            wp_redirect( site_url('/teacher-dashboard/') );
            exit;
        }

        // Si el usuario es administrador
        else if ( in_array('administrator', (array) $current_user->roles) && ( is_page('dashboard') || is_page('teacher-dashboard') ) ) {
            wp_redirect( site_url('/wp-admin/') );
            exit;
        }
    }
}
add_action('template_redirect', 'identify_roles');

require_once get_stylesheet_directory() . '/Includes/rest-api-neurekag.php';

function script_inyector_h5p() {
?>
<script>
document.addEventListener("DOMContentLoaded", function() {
    // 1. Buscamos TODOS los iframes de H5P en la página
    var allIframes = document.querySelectorAll(".h5p-iframe");
    
    allIframes.forEach(function(iframe) {
        
        // Función para inyectar el estilo
        var injectStyles = function(targetIframe) {
            try {
                var style = targetIframe.contentDocument.createElement("style");
                style.textContent = `
                

                    .media-screen .media-screen-buttons-wrapper .media-screen-button button {
                        background-color: #857a8f !important; /* tu morado de las cartas */
                        color: #ecd8ff !important;
                        border: none !important;
                        border-radius: 8px !important;
                        padding: 12px 32px !important;
                        font-size: 16px !important;
                        font-family: 'your-pixel-font', monospace !important; /* la fuente de Neureka */
                        cursor: pointer !important;
                        transition: background-color 0.2s !important;
                    }
                    
                    .media-screen .media-screen-buttons-wrapper .media-screen-button button: hover{
                        background-color: #746a7d !important;
                    }
                    
                    
                    .media-screen-introduction {
                        margin-bottom: 20px !important;
                        width: 90% !important;
                        text-align: center !important;
                    }
                    
                    .media-screen-bar {
                        display: none !important;
                    }
                   
                   .h5p-alternative-container {
                        background-color: #d8cfed !important;
                        border: 2px solid #d1d9e6 !important;
                        border-radius: 12px !important;
                        margin-bottom: 25px !important;
                       
                   }
                   
                   .h5p-game-map-exercise-instance.h5p-image {
                        weight: auto !important;
                        height: 300px !important;
                   }
                   
                   .h5p-game-map-overlay-dialog-content {
                        padding: 16px !important;
                        color: black !important;
                        font-size: 1.1em !importat;
                        line-height: 1.65 !important;
                        background-color: #f5f0ff !important;
                        
                   }
                   
                   .h5p-question-introduction {
                        font-size: 1.1em !importat;
                        line-height: 1.65 !important;
                        color: #3d3660 !important;
                        margin-bottom: 18px !important;
                        max-width: 480px !important;
                   }
                   
                   .h5p-answers {
                        background: #faf9ff !important;
                        border: 1.5px solid #ddd8fb !important;
                        border-radius: 12px !important;
                        padding: 10px 14px !important;
                        transition: all 0.15s ease !important;
                        cursor: pointer !important;
                   }
                   
                    .h5p-memory-game > ul {
                        display: grid !important;
                        grid-template-columns: repeat(2, 1fr) !important;
                        gap: 16px !important;
                        padding: 0 !important;
                        margin: 0 auto !important;
                        max-width: 700px !important;
                        list-style: none !important;
                    }

                    .h5p-memory-game > ul > li {
                        height: 250px !important;
                        margin: 0 !important;
                    }

                    .h5p-memory-game div.h5p-memory-card {
                        width: 100% !important;
                        height: 100% !important;
                        min-width: unset !important;
                        min-height: unset !important;
                    }

                    .h5p-memory-game .h5p-front,
                    .h5p-memory-game .h5p-back {
                        width: 100% !important;
                        height: 100% !important;
                    }
                    
                    .h5p-memory-game .h5p-back {
                        display: flex !important;
                        align-items: center !important;
                        justify-content: center !important;
                        width: 100% !important;
                        height: 100% !important;
                        overflow: hidden !important;
                    }

                    .h5p-memory-game .h5p-back img {
                        width: auto !important;
                        height: 100% !important;
                        max-width: 100% !important;
                        object-fit: contain !important;
                    }
                    
                    .h5p-joubelui-button.h5p-question-check-answer {
                        background-color: #857a8f !important;
                        color: #ecd8ff !important;
                        border: none !important;
                        border-radius: 8px !important;
                        padding: 12px 32px !important;
                        font-size: 16px !important;
                        cursor: pointer !important;
                        transition: background-color 0.2s !important;
                        font-family: 'your-pixel-font', monospace !important;
                    }

                    .h5p-joubelui-button.h5p-question-check-answer:hover {
                        background-color: #746a7d !important;
                    }
                    
                    .h5p-joubelui-button.h5p-game-map-exercise-instance-continue-button {
                        background-color: #857a8f !important; /* tu morado de las cartas */
                        color: #ecd8ff !important;
                        border: none !important;
                        border-radius: 8px !important;
                        padding: 12px 32px !important;
                        font-size: 16px !important;
                        font-family: 'your-pixel-font', monospace !important; 
                        cursor: pointer !important;
                        transition: background-color 0.2s !important;
                    }

                    .h5p-joubelui-button.h5p-game-map-exercise-instance-continue-button:hover {
                        background-color: #746a7d !important; 
                    }
                    
                    .h5p-game-map-toolbar-tool-bar {
                        background-color: #c8c3e8 !important; /* lavanda del fondo */
                        border-radius: 12px !important;
                        padding: 10px 20px !important;
                        font-family: inherit !important;
                        color: #2d2a6e !important; /* azul oscuro del diseño */
                    }

                    /* Título "Comandante" */
                    .toolbar-headline {
                        font-weight: 800 !important;
                        color: #2d2a6e !important;
                        font-size: 18px !important;
                    }

                    /* Contenedores de stats */
                    .status-container {
                        background-color: #ffffff88 !important;
                        border-radius: 8px !important;
                        padding: 4px 10px !important;
                        color: #2d2a6e !important;
                        font-weight: 600 !important;
                    }

                    /* Botón Finish */
                    .toolbar-button-finish {
                        background-color: #5c52b5 !important;
                        color: #ffffff !important;
                        border: none !important;
                        border-radius: 8px !important;
                        padding: 8px 20px !important;
                        font-weight: 700 !important;
                        font-size: 14px !important;
                        cursor: pointer !important;
                        
                    }
                    .toolbar-button-finish:hover {
                        background-color: #857a8f !important;
                    }
                    
                    .toolbar-button-fullscreen {
                        display: none !important;
                    }
                    
                    
                    .ui-draggable.ui-draggable-handle {
                        background-color: #857a8f !important;
                        color: #ecd8ff !important;
                        border: none !important;
                        -radius: 8px !important;
                    }

                    .ui-draggable.ui-draggable-disabled {
                        background-color: #857a8f !important;
                        color: #ecd8ff !important;
                        border: none !important;
                        border-radius: 8px !important;
                    }
                    
                    .h5p-drag-dropzone {
                        border: 2px dashed #857a8f !important;
                        border-radius: 8px !important;
                        background-color: #f5f0ff !important;
                    }
                    
                    .h5p-joubelui-button.h5p-question-game-map-continue {
                        background-color: #857a8f !important;
                        color: #ecd8ff !important;
                        border: none !important;
                        border-radius: 8px !important;
                        padding: 12px 32px !important;
                        font-size: 16px !important;
                        cursor: pointer !important;
                        transition: background-color 0.2s !important;
                    }

                    .h5p-joubelui-button.h5p-question-game-map-continue:hover {
                        background-color: #746a7d !important;
                    }
                   
                `;
                targetIframe.contentDocument.head.appendChild(style);
            } catch (e) {
                console.log("Esperando carga completa del nivel...");
            }
        };

        // Si ya cargó, inyectamos de una vez
        if (iframe.contentDocument && iframe.contentDocument.readyState === 'complete') {
            injectStyles(iframe);
        }

        // Por si tarda un poco en cargar el contenido del nivel
        iframe.addEventListener("load", function() {
            injectStyles(iframe);
        });
    });
});
</script>
<?php
}
add_action('wp_footer', 'script_inyector_h5p');

