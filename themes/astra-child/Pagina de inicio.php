<?php
/*
Template Name: Homepage
*/

get_header();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio | Neureka GS</title>
    <link rel="icon" type="image/png" href="<?php echo get_stylesheet_directory_uri(); ?>/media/Avion-lateral-derecha.png" />
    <link rel="stylesheet" href="Pantalla de opciones.html">
</head>

<body>
  <div class="container-fluid">
    <section class="centro"
      style="display: flex; justify-content: center; align-items: center; height: 80vh;">

  <div class="left -images">
    <div style="display: flex; align-items: center; gap: 20px;">
      <img src="<?php echo get_stylesheet_directory_uri(); ?>/media/Rodolfo-1.png" alt="Rodolfito" style="width: 200px;">
      <img src="<?php echo get_stylesheet_directory_uri(); ?>/media/astronauta.png" alt="Astronauta" style="width: 288px;">
      <img src="<?php echo get_stylesheet_directory_uri(); ?>/media/Gato-espacial.png" alt="Sandy" style="width: 288px;">
  </div>
</div>
  <div class="right-content">
    <div style="margin-left: 50px;">
     <h1>¡Inclusión,<br> diversión y <br> aprendizaje!</h1>
     <p>¿Qué más podemos pedir?</p>
     <a href="https://genuinecreators.com/role-options/" class="join-btn">
      <img src="<?php echo get_stylesheet_directory_uri(); ?>/media/Avion-lateral-derecha.png" alt="Avión" class="btn-icon1">
      ¡Únete Ahora!
      <img src="<?php echo get_stylesheet_directory_uri(); ?>/media/Avion-lateral-izquierda.png" alt = "Avión" class="btn-icon2 ">
    </a>
  </div>
 
    <style>
      .left-images {
        display: flex;
        flex-direction: row; 
        align-items: flex-start;
        gap: 100px;
       margin-right: 2rem;
      }

      .left-images img {
       width: 150px;
     }

      body {
       font-family: 'Helvetica', sans-serif;
       margin: 0;
       padding: 0;
       background-color: white;
      } 

      .right-content h1{
       font-size: 2.2rem;
       font-weight: bold;
       margin-bottom: 0.5rem;
      }
        
      .right-content p {
         font-size: 1.1rem;
         margin-bottom: 1.5rem;
         font-weight: bold;
         color: black;
        }

      .join-btn {
        background-color: #D6C6FF;
        color: #3627DD;
        padding: 10px 25px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: lighter;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-family: 'Helvetica', sans-serif;
        } 

      .btn-icon1{
        width: 30px;
        height: auto;
      }
      .btn-icon2 {
        width: 20px;
        height: auto;
      }    
    </style>

  </div>
  </section>
</body>
</html>