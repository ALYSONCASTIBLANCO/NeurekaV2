<?php
/*
Template Name: Student Register
*/
get_header();
?>

    <div class="container" style="background: #fffff2;">
      <div class="form-container" style="background: #fffff2;">
        <div class="central-form" style="width:400px; margin:0; display:flex; flex-direction:column; justify-content:flex-start; font-family: Helvetica; padding:2rem 2.5rem; border-radius:20px; box-shadow:0 0 15px rgba(0,0,0,0.05); z-index:2;">
 <h2 style="margin: 0 0 1.2rem 0; color:#0f0229; width:100%; text-align:center; display:block;">
  Regístrate
</h2>
  <form method="post" action="" style="width:100%; display:flex; flex-direction:column;">
        <div style="margin-bottom:1rem;">
          <label style="font-weight:bold; display:block; margin-bottom:0.3rem; color: #0f0229">Tu Nombre</label>
          <div style="display:flex; align-items:center;">
            <span style="margin-right:8px;">👤</span>
            <input type="text" name="user_username" placeholder="Entra tu nombre" required style="flex:1; padding:10px; border-radius:10px; border:1px solid #ddd;">
          </div>
        </div>
        
        
        <div style="margin-bottom:1rem;">
          <label style="font-weight:bold; display:block; margin-bottom:0.3rem;">Tu Apodo</label>
          <div style="display:flex; align-items:center;">
            <span style="margin-right:8px;">😊</span>
            <input type="text" name="user_login" placeholder="Entra tu apodo" required style="flex:1; padding:10px; border-radius:10px; border:1px solid #ddd;">
          </div>
        </div>

        
        <div style="margin-bottom:1rem;">
          <label style="font-weight:bold; display:block; margin-bottom:0.3rem;">Tu Correo</label>
          <div style="display:flex; align-items:center;">
            <span style="margin-right:8px;">✉️</span>
            <input type="email" name="user_email" placeholder="Entra tu correo" required style="flex:1; padding:10px; border-radius:10px; border:1px solid #ddd;">
          </div>
        </div>

        
        <div style="margin-bottom:1rem;">
          <label style="font-weight:bold; display:block; margin-bottom:0.3rem;">Tu Contraseña</label>
          <div style="display:flex; align-items:center;">
            <span style="margin-right:8px;">🔒</span>
            <input type="password" name="user_password" placeholder="Entra tu contraseña" required style="flex:1; padding:10px; border-radius:10px; border:1px solid #ddd;">
          </div>
        </div>

        <div class="checkbox-group">
          <label><input type="checkbox"> Recuérdame</label>
          <label><input type="checkbox" name="aceptar_terminos"><a href="https://genuinecreators.com/privacy-policy/"> Acepta los términos y condiciones</a></label>
        </div>

            <style>
              .checkbox-group {
                display: flex;
                gap: 2rem;
                align-items: center;
                margin-top: 1rem;
                accent-color: #ffffffff;
              }

              .checkbox-group label {
                display: flex;
                align-items: center;
                font-size: 14px;
                accent-color: #ffffffff;
              }
            </style>
                   <hr style="border: none; height: 1px; background: #fffff2;">
        <button type="submit" name="registro_submit" style="margin-bottom: 10px; background:#d6c6ff; width:100%; padding:10px; border:none; border-radius:10px; font-weight:bold; color:#000; cursor:pointer;">¡Unirse!</button>

          </form>
    </div> 
    
  
    </div>
    

    <div class="rodolfo">
      <div>
          <h1 class="gradienttext" style="text-align:center; margin-top: 1rem">¡Hola Estudiante!</h1>
    </div>
          <img src="<?php echo get_stylesheet_directory_uri(); ?>/media/Rodolfo.png"
  alt="Rodolfo">
        </div>

    <style>
        .site {
  background: #fffff2;
}
        .container{
            margin-top: 50px;
            display: flex;
            align-items: center;
            /*Este padding es el que genera esa brecha blanca con respecto a Rodolfo. Se la he comentado.*/
            /*padding: 15px;*/
            gap: 15px;   
        }

        .gradienttext{
         background: #A8A8F5;
background: linear-gradient(90deg,rgba(168, 168, 245, 1) 35%, rgba(190, 235, 229, 1) 73%);
         -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
         font-weight: 700;
        }

        .form-container {
            display: flex;
            justify-content: center;
            align-items: center;
            background: transparent;
            padding: 30px;
            border-radius: 15px;
            /*box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);*/
            flex-grow: 1;
            flex-basis: 63%;
            margin-top: 10px;
        }

       .central-form {
        color: #681dff !important;
         display: flex;
         justify-content: center;
         align-items: flex-start;
         padding: 2.5rem 3rem;
         position: relative;
         transition: 0.3s ease;
         background: #BEEBE5;
background: linear-gradient(0deg,rgba(190, 235, 229, 1) 42%, rgba(168, 168, 245, 1) 96%);
        }   
        
        .central-form form {
  display: flex;
  flex-direction: column;
  width: 100%;
}

        .central-container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .rodolfo {
            flex-grow: 1;
            flex-basis: 15%;
            justify-content: right;
            overflow: hidden;
        }

        .rodolfo img {
            object-fit: cover;
            object-position: center center;
        }

        /* ===== Tablets y pantallas medianas ===== */
          @media (max-width: 1024px) {
            .container {
              gap: 20px;
            }

            .form-container {
              max-width: 350px;
              /* formulario más pequeño */
              width: 80%;
            }
        }

         /* ===== Celulares ===== */
          @media (max-width: 768px) {
            .container {
              flex-direction: column;
              /* form arriba */
            }

            .form-container {
              max-width: 85%;
              /* ocupa más ancho en cel */
            }

            .rodolfo {
              display: none;
              /* desaparece Rodolfo */
            }
          }

    </style>
    <?php
    if (isset($_POST['registro_submit'])) {
          if (!isset($_POST['aceptar_terminos'])) {
        echo '<p style="color:red;">Debes aceptar los términos y condiciones para registrarte.</p>';
        return; // Detiene la ejecución antes de crear el usuario
        }
            $userdata = array(
        'user_login' => sanitize_user($_POST['user_login']),
        // 'user_area' => sanitize_text_field($_POST['user_area']),'user_organization' => sanitize_text_field($_POST['user_organization']),//
        'user_pass'  => $_POST['user_password'],
        'user_email' => sanitize_email($_POST['user_email']),
        'role' => 'um_student', // Asigna el rol
            );

        $user_id = wp_insert_user($userdata);
        $perfil_url = um_user_profile_url($user->ID);
        if (is_wp_error($user_id)) {
        echo '<p>Error: ' . $user_id->get_error_message() . '</p>';
        } else {                 // Guardar extras como user_meta
        update_user_meta($user_id, 'username', sanitize_text_field($_POST['user_username']));
        // Si quieres integrarlo con UM, disparas el hook de registro
        do_action('um_after_user_register', $user_id, $userdata);
        wp_redirect('/dashboard');
        exit;
        }
      }
        ?>

    <?php get_footer(); ?>