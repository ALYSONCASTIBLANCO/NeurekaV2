<?php
/*
Template Name: Parent Register
*/
?>
<!DOCTYPE html>
<html lang="en; es;">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="Avión lateral derecha.png" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Padres | Neureka GS</title>
</head>

<body>
    <div class="container">
      <div class="form-container">
        <div class="central-form" style=" width:400px; margin:0; height:450px; display:flex; justify-content: center; align-items: center; font-family: Helvetica; background:#f9f9fb; padding:2rem 2.5rem; border-radius:20px; box-shadow:0 0 15px rgba(0,0,0,0.05); z-index:2;">

          <form method="post" action="">
            <h2 style="text-align:center; margin-bottom:1.5rem;">Registrarse</h2>
            <div style="margin-bottom:1rem;">
              <label style="font-weight:bold; display:block; margin-bottom:0.3rem;">Nombre del acudiente</label>
              <div style="display:flex; align-items:center;">
                <span style="margin-right:8px;">👥</span>
                <input type="text" name="user_login" placeholder="Entra tu nombre" required style="flex:1; padding:10px; border-radius:10px; border:1px solid #ddd;">
              </div>
            </div>

            <div style="margin-bottom:1rem;">
                <label style="font-weight:bold; display:block; margin-bottom:0.3rem;">Nombre del estudiante</label>
                <div style="display:flex; align-items:center;">
                  <span style="margin-right:8px;">👤</span>
                  <input type="text" name="user_namest" placeholder="Entra él nombre" required style="flex:1; padding:10px; border-radius:10px; border:1px solid #ddd;">
                </div>
              </div>
      
    
            <div style="margin-bottom:1rem;">
              <label style="font-weight:bold; display:block; margin-bottom:0.3rem;">Código Único</label>
              <div style="display:flex; align-items:center;">
                <span style="margin-right:8px;">🛡️</span>
                <input type="text" name="students_code" placeholder="Entra tu código" required style="flex:1; padding:10px; border-radius:10px; border:1px solid #ddd;">
              </div>
            </div>
    
            <div style="margin-bottom:1rem;">
              <label style="font-weight:bold; display:block; margin-bottom:0.3rem;">Correo</label>
              <div style="display:flex; align-items:center;">
                <span style="margin-right:8px;">✉️</span>
                <input type="email" name="user_email" placeholder="Entra tu correo" required style="flex:1; padding:10px; border-radius:10px; border:1px solid #ddd;">
              </div>
            </div>

            <div style="margin-bottom:1rem;">
              <label style="font-weight:bold; display:block; margin-bottom:0.3rem;">Contraseña</label>
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
                accent-color: #a78bfa;
              }

              .checkbox-group label {
                display: flex;
                align-items: center;
                font-size: 14px;
                accent-color: #a78bfa;
              }
            </style>
            
                 <hr style="border: none; height: 1px; background: #eee; margin-bottom: 1rem;">
            <button type="submit"name="registro_submit" style="background:#d6c6ff; width:100%; padding:12px; border:none; border-radius:10px; font-weight:bold; color:#000; cursor:pointer;">¡Unirse!</button>
          </form>
    </div> 
     <?php
          if (isset($_POST['registro_submit'])) {
          if (!isset($_POST['aceptar_terminos'])) {
        echo '<p style="color:red;">Debes aceptar los términos y condiciones para registrarte.</p>';
        return; // Detiene la ejecución antes de crear el usuario
        }
        $codigo_ingresado = sanitize_text_field($_POST['students_code']);
            // Validar que el código exista en la base de datos (usermeta de estudiantes)
        $estudiante = get_users([
            'meta_key'   => 'students_code',
            'meta_value' => $codigo_ingresado,
            'role'       => 'um_student',
            'number'     => 1,
            'fields'     => 'ID'
        ]);
        $acudiente = get_users([
            'meta_key'   => 'students_code',
            'meta_value' => $codigo_ingresado,
            'role'       => 'um_parent',
            'fields'     => 'ID'
        ]);
        $cantidad = count($acudiente);

            if (empty($estudiante) || $cantidad==2){
        echo '<p style="color:red;">❌ El código ingresado no es válido.</p>';
        return; // detener ejecución
    }

            $userdata = array(
        'user_login' => sanitize_user($_POST['user_login']),
        // 'user_area' => sanitize_text_field($_POST['user_area']),'user_organization' => sanitize_text_field($_POST['user_organization']),//
        'user_pass'  => $_POST['user_password'],
        'user_email' => sanitize_email($_POST['user_email']),
        'role' => 'um_parent', // Asigna el rol
            );

        $user_id = wp_insert_user($userdata);
        $perfil_url = um_user_profile_url($user->ID);
        if (is_wp_error($user_id)) {
        echo '<p>Error: ' . $user_id->get_error_message() . '</p>';
        } else {                 // Guardar extras como user_meta
        update_user_meta($user_id, 'students_code', sanitize_text_field($_POST['students_code']));
        update_user_meta($user_id, 'name_student', sanitize_text_field($_POST['user_namest']));
        // Si quieres integrarlo con UM, disparas el hook de registro
        do_action('um_after_user_register', $user_id, $userdata);
        wp_redirect('/dashboard');
        exit;
        }
      }
        ?>
    </div>
   
    <div class="rodolfo">
          <img src="https://i.imghippo.com/files/trDj5110eSU.png" alt="Rodolfo">
        </div>

    <style>
        .container{
            display: flex;
            align-items: center;
            /*padding: 15px;*/
            gap: 15px;   
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
        }

       .central-form {
         display: flex;
         justify-content: center;
         align-items: center;
         padding: 2.5rem 3rem;
         position: relative;
         transition: 0.3s ease;
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
            object-position: right center;
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

   </section>
    
</body>
</html>