/**
* Detect events in Interactive book
*/
// === JS para H5P Interactive Book + GamiPress (con corrección de errores) ===
add_action('wp_footer', function () {
   ?>
   <script>
        //jQuery es un servicio que me permite hacer peticiones a cualquier servidor
        //para obtener informacion de ahi, es como una comunicacion entre el usuario
        //y el sistema de WordPress.
   jQuery(document).ready(function($){
       // Mapeo libro title → achievement ID
       const booksAchievements = {
           'Easy Level': 74, // Libro 1 → Luna
           'Intermediate Level': 79, // Libro 2 → Achievement libro 2
			'Hard Level': 77,   
           //3: 57  // Libro 3 → Achievement libro 3
       };
        //Esto es para iniciar la deteccion de los eventos. Si el tipo de pagina que
        //se renderiza es de H5P, en el caso de los juegos y el servicio de eventos
        //de H5P esta listo para ser escuchado, entonces comienza el proceso. Eso
        //Evita que se ejecute en otros sitios de la plataforma que no nos interesan.
       if (typeof H5P !== "undefined" && H5P.externalDispatcher) {
            //Esta parte de aca es para que tome los datos de todo el libro, que tipo
            //de libro es y que tiene adentro, y eso nos ayuda a saber cuantos ejercicios
            //tiene el libro adentro.
           for (const key in H5PIntegration.contents) {
                //Guarda toda la informacion del libro aqui, asi podremos extraer todo
                //lo que necesitemos.
               const content = H5PIntegration.contents[key];
                //Este console.log es para saber que esta imprimiendo cuando se ejecuta el libro. Me sirvio para saber las variables reales del documento.
                console.log(content);
               // Solo Interactive Books que estén en el mapeo. Aqui esta identificando
               // si se refiere a un interactive book y si el titulo del book si esta
               // dentro de la base de datos, quiere decir que el libro si existe y
               // pueden ser leidos los eventos.
               if (content.library.includes("H5P.InteractiveBook 1.11") && booksAchievements[content.title]) {
                    //Aqui vamos a hacer que solo tome el titulo del libro, que
                    //es lo que nos interesa.
                   const targetAchievement = booksAchievements[content.title];
                    //Vamos a crear una variable que guarde la cantidad de ejercicios del
                    //libro.
                   let total_max = 0;
                    //Tambien vamos a crear una lista que permita guardar los puntajes
                    //de cada intento del ejercicio.
                   let chaptersScores = {};
                    //Vamos a leer el contenido de la informacion del libro
                   const bookData = JSON.parse(content.jsonContent);
                    //Y solo vamos a tomar la cantidad de ejercicios dentro del libro.
                   total_max = bookData.chapters.length;


                   // Escuchar eventos xAPI
                   H5P.externalDispatcher.on('xAPI', function(event){
                       const st = event.data.statement;
                        //Cuando un usuario hace clic en check, dispara un evento
                        //answered, por eso, aqui lo capturamos.
                       if(st.verb?.display?.['en-US'] === 'answered'){
                            //Capturamos el id del ejercicio
                           const chapterId = st.object.id;
                            //Cuando el usuario responde bien, siempre arroja un 1
                            //va a guardar ese puntaje en caso de que el usuario no
                            //conteste bien, arroja un cero.
                           const score = st.result?.score?.raw || 0;


                           // Guardar o actualizar puntaje si es mayor
                           if(!chaptersScores[chapterId] || score > chaptersScores[chapterId]){
                               chaptersScores[chapterId] = score;
                           }


                           // Recalcular total_raw
                           const total_raw = Object.values(chaptersScores).reduce((a,b)=>a+b,0);


                           // Verificar si se respondieron todos los capítulos
                           if(Object.keys(chaptersScores).length >= total_max){
                                //Calcula el puntaje final dividiendo el total de
                                //aciertos entre el total de ejercicios y lo multiplica
                                //por 100.
                               const final_score = (total_raw / total_max) * 100;
                               console.log("➡️ El libro es:", content.title, "Puntaje final:", final_score);
                                //Su aprueba con un puntaje mayor o igual a 70, se hace
                                //la peticion al servidor y se manda la informacion de la
                                //recompensa a Gamipress.
                               if(final_score >= 70){
                                   $.post('<?php echo admin_url("admin-ajax.php"); ?>', {
                                       action: 'h5p_completed',
                                       score: final_score,
                                       achievement_id: targetAchievement
                                   }, function(response){
                                       console.log(response);
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


// === Handler PHP para otorgar puntos y achievement en GamiPress ===
add_action('wp_ajax_h5p_completed', function () {
   if(!is_user_logged_in()){
       wp_send_json_error("Usuario no logueado");
   }


   $user_id = get_current_user_id();
    //Se obtiene puntaje ganado por la persona.
   $score = floatval($_POST['score'] ?? 0);
    //Se obtiene el link del achievment que se gana con el libro correspondiente.
    $achievement_id = strval($_POST['achievement_id'] ?? 0);


   // Puntaje mínimo para otorgar achievement
   if($score >= 70 && $achievement_id){
       // Otorgar 10 puntos (puedes cambiar cantidad y tipo)
       //gamipress_award_points_to_user($user_id, 10, 'punto');


       // Otorgar achievement (usar ID numérico de tu achievement)
       gamipress_award_achievement_to_user($achievement_id, $user_id);


       wp_send_json_success("Puntos y achievement asignados");
   } else {
       wp_send_json_error("No alcanza el puntaje mínimo");
   }
});


// ========================
// 3️⃣ Shortcode de desbloqueo por achievement
// ========================
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