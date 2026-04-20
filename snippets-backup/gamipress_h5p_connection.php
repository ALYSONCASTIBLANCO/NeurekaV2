
/**
 * Detect events in H5P Game Map and award GamiPress achievements
 */
add_action('wp_footer', function () {
    ?>
    <script>
    jQuery(document).ready(function($){
        // === CONFIGURACIÓN: Mapeo Título del Mapa → ID del Logro ===
        const mapAchievements = {
            'Aspirante': 74, 
            'Explorador': 77,
            'Especialista': 79,
			'Comandante': 251,
			'Maestro': 316, 
		
			
        };

        // Verificamos si H5P está cargado en la página
        if (typeof H5P !== "undefined" && H5P.externalDispatcher) {
            
            for (const key in H5PIntegration.contents) {
                const content = H5PIntegration.contents[key];
                
                // Filtramos por librería Game Map y si el título está en nuestra lista
                if (content.library.includes("H5P.GameMap") && mapAchievements[content.title]) {
                    
                    const targetAchievement = mapAchievements[content.title];
                    let total_stages = 0;
                    let stagesScores = {};

                    // Extraer la cantidad de nodos/etapas del mapa desde el JSON
                    try {
                        const gameData = JSON.parse(content.jsonContent);
                        // Estructura específica de Game Map para contar etapas
                        if (gameData.gameMap && gameData.gameMap.stages) {
                            total_stages = gameData.gameMap.stages.length;
                        }
                    } catch (e) {
                        console.error("Error al leer datos del Game Map:", e);
                    }

                    // Escuchar eventos xAPI (cuando el usuario interactúa con los nodos)
                    H5P.externalDispatcher.on('xAPI', function(event){
                        const st = event.data.statement;
                        
                        // Detectamos cuando una etapa es respondida o completada
                        const verb = st.verb?.display?.['en-US'];
                        if(verb === 'answered' || verb === 'completed'){
                            
                            const stageId = st.object.id;
                            const score = st.result?.score?.raw || 0;

                            // Guardamos el puntaje más alto obtenido en cada nodo
                            if(!stagesScores[stageId] || score > stagesScores[stageId]){
                                stagesScores[stageId] = score;
                            }

                            // Cálculo de progreso
                            const total_raw = Object.values(stagesScores).reduce((a,b) => a + b, 0);
                            const completed_count = Object.keys(stagesScores).length;

                            // Si ya pasó por todas las etapas del mapa
                            if(completed_count >= total_stages && total_stages > 0){
                                const final_score = (total_raw / total_stages) * 100;
                                
                                console.log("➡️ Mapa:", content.title, "Puntaje final:", final_score);

                                // Si el promedio es >= 70, enviamos la petición a WordPress
                                if(final_score >= 70){
                                    $.post('<?php echo admin_url("admin-ajax.php"); ?>', {
                                        action: 'h5p_map_completed',
                                        score: final_score,
                                        achievement_id: targetAchievement
                                    }, function(response){
                                        if(response.success) {
                                            console.log("¡Logro asignado exitosamente!");
                                        }
                                    });
                                }
                            }
                        }
                    });
                }
            }
        }
    });
    </script>
    <?php
});

/**
 * Handler PHP para procesar el logro en GamiPress mediante AJAX
 */
add_action('wp_ajax_h5p_map_completed', function () {
    if(!is_user_logged_in()){
        wp_send_json_error("Usuario no logueado");
    }

    $user_id = get_current_user_id();
    $score = floatval($_POST['score'] ?? 0);
    $achievement_id = intval($_POST['achievement_id'] ?? 0);

    // Verificación final de seguridad y puntaje
    if($score >= 70 && $achievement_id > 0){
        // GamiPress otorga el logro al usuario
        gamipress_award_achievement_to_user($achievement_id, $user_id);
        wp_send_json_success("Logro otorgado correctamente.");
    } else {
        wp_send_json_error("Puntaje insuficiente o ID de logro inválido.");
    }
});

function libro_bloqueado_shortcode($atts) {
    //Este shortcode lo que hace, es que recibe unos atributos cuando se inicializa, recibe tres cosas: el id del libro actual, el id del achievement que debe completar
    //previamente antes de seguir el nivel y un mensaje en caso de que no haya desbloqueado el achievement anterior.
   $atts = shortcode_atts([
       'h5p_id' => 0,
       'achievement_id' => 0,
       'mensaje' => 'Debes completar el libro anterior para desbloquear este.'
   ], $atts, 'libro_bloqueado');
    //Obtiene el usuario logueado actualmente.
   $user_id = get_current_user_id();
    //Para saber si el usuario ya gano el achievment previo, hay que escanear todos los
    //achievements ganados por el usuario, por lo que usamos esta linea.
    $achievements = gamipress_get_user_achievements( $user_id );
    //Vamos a usar una especie de bandera que nos avise si el usuario si obtuvo el
    //logro o no.
    $has_earned = false;
    //Como es un array que tiene todos los logros, debe buscar uno por uno hasta que
    //encuente el que nos interesa.
    foreach ( $achievements as $achievement ) {
        //Si encuentra un achievement que coincide con el del atributo, quiere decir
        //que ya gano el logro previo para pasar de nivel.
   if ( $achievement->ID == $atts['achievement_id']) {
        //Pone la bandera en verdadero para que le avise al sistema que si
        //cumplio el logro.
       $has_earned = true;
       break;
   }
}
    //Entonces, si el usuario esta logueado y si gano el logro, entonces renderiza el shortcode que despliega el siguiente nivel.
   if ($user_id && $has_earned) {
       return do_shortcode('[h5p id="' . intval($atts['h5p_id']) . '"]');
   }
    //Si no, muestra un mensaje de que debe desbloquearlo antes de continuar con ese
    //nivel.
   return '<p>' . esc_html($atts['mensaje']) . '</p>';
}
//Instruccion para crear el shortcode y que se pueda usar.
add_shortcode('libro_bloqueado', 'libro_bloqueado_shortcode');