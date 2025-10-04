<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/png" href="Avión lateral derecha.png" />
  <title>Ejemplos | Neureka GS</title>
</head>

<body>
  <div class="container">
    <div class="form-container">
      <div class="central-form" style="width:400px; height:450px; display:flex; justify-content: center; align-items: center;">
        <form>
            <h2 style="text-align:center; margin-bottom:1.5rem;">Registrarse</h2>
           
            <div style="margin-bottom:1rem;">
              <label style="font-weight:bold; display:block; margin-bottom:0.3rem;">Nombre del acudiente</label>
              <div style="display:flex; align-items:center;">
                <span style="margin-right:8px;">👥</span>
                <input type="text" placeholder="Entra tu nombre" required style="flex:1; padding:10px; border-radius:10px; border:1px solid #ddd;">
              </div>
            </div>

            <div style="margin-bottom:1rem;">
              <label style="font-weight:bold; display:block; margin-bottom:0.3rem;">Nombre del estudiante</label>
              <div style="display:flex; align-items:center;">
                <span style="margin-right:8px;">👤</span>
                <input type="text" placeholder="Entra él nombre" required style="flex:1; padding:10px; border-radius:10px; border:1px solid #ddd;">
              </div>
            </div>

            <div style="margin-bottom:1rem;">
              <label style="font-weight:bold; display:block; margin-bottom:0.3rem;">Código Único</label>
              <div style="display:flex; align-items:center;">
                <span style="margin-right:8px;">🛡️</span>
                <input type="text" placeholder="Entra tu código" required style="flex:1; padding:10px; border-radius:10px; border:1px solid #ddd;">
              </div>
            </div>

            <div style="margin-bottom:1rem;">
              <label style="font-weight:bold; display:block; margin-bottom:0.3rem;">Correo</label>
              <div style="display:flex; align-items:center;">
                <span style="margin-right:8px;">✉️</span>
                <input type="email" placeholder="Entra tu correo" required style="flex:1; padding:10px; border-radius:10px; border:1px solid #ddd;">
              </div>
            </div>

            <div style="margin-bottom:1rem;">
              <label style="font-weight:bold; display:block; margin-bottom:0.3rem;">Contraseña</label>
              <div style="display:flex; align-items:center;">
                <span style="margin-right:8px;">🔒</span>
                <input type="password" placeholder="Entra tu contraseña" required style="flex:1; padding:10px; border-radius:10px; border:1px solid #ddd;">
              </div>
            </div>

            <div class="checkbox-group">
              <label><input type="checkbox"> Recuérdame</label>
              <label><input type="checkbox"> Acepta los términos y condiciones</label>
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
            <button type="submit" style="background:#d6c6ff; width:100%; padding:12px; border:none; border-radius:10px; font-weight:bold; color:#000; cursor:pointer;">¡Unirse!</button>
          </form>
        </div>
      </div>

      <div class="rodolfo">
          <img src="https://i.imghippo.com/files/trDj5110eSU.png" alt="Rodolfo">
        </div>

        <style>
          /* ===== Estilos base ===== */
          body {
            /*Quito este display flex de aqui porque el body es demasiado universal, no todos los elementos adentro lo deben tener, en cambio en contenedor
  que contiene el formulario y a Rodolfo si.
    display: flex;
    justify-content: center;
    align-items: center;*/
            min-height: 100vh;
            margin: 0;
            background: #f9f9fb;
            font-family: Helvetica;
          }

          .container {
            display:flex;
            /*Deje de justificar los elementos al centro porque eso afectaba el espaciado de todo. Verticalmente si se queda*/
            /*justify-content: center;*/
            align-items: center;
            padding: 15px;
            gap: 15px;
          }

          .form-container {
            display: flex;
            justify-content: center;
            align-items: center;
            background: transparent;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            /*Esto tambien lo removi porque si no, no se puede asegurar que se corte Rodolfo.
    max-width: 400px;*/
            /*Aqui esta el cambio: si uno usa display flex, puede utilizar estos dos comandos para proporcionar tamaños personalizados a cada contenedor.
  Aqui este va a ocupar un 60% del contenedor padre y esa va a ser su proporcion, esto puede ser cambiado.*/
            flex-grow: 1;
            flex-basis: 63%;
          }

          .central-container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background: white;
          }

          /* Contenedor que contiene la imagen de Rodolfo */
          .rodolfo {
            /*Se hace lo mismo para el contenedor de la imagen, para que la proporcion sea menor.*/
            flex-grow: 1;
            flex-basis: 15%;
            /*Se manda a la derecha del contendor*/
            justify-content: right;
            /*Esto es importante porque para cortar la imagen, hay que hacer que todo lo que se salga del contenedor desaparezca.*/
            overflow: hidden;
          }

          /*Ahora si imagen de Rodolfo*/
          .rodolfo img {
            object-fit: cover;
            object-position: right center;
            /* recortado a la derecha */
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

            /*Comento esto porque corta a Rodolfo cuando esta en tablet
    .rodolfo {
        max-height: 220px;
    }*/
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
      </div>

</body>

</html>