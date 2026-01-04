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
     style="display: flex; justify-content: center; align-items: center;height: 80vh;">

  <div class="left -images">
    <div style="display: flex; align-items: center; gap: 20px;">
      <div id= "Rodolfito"><img src="<?php echo get_stylesheet_directory_uri(); ?>/media/Rodolfo-1.png" alt="Rodolfito" style="width: 200px;"></div>
      <div id= "Pancracio"><img src="<?php echo get_stylesheet_directory_uri(); ?>/media/astronauta.png" alt="Astronauta" style="width: 288px;"></div>
      <div id= "Sandy"> <img src="<?php echo get_stylesheet_directory_uri(); ?>/media/Gato-espacial.png" alt="Sandy" style="width: 288px;"></div>
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
      
      /* Tablets grandes y pantallas medianas (hasta 1200px) */
       @media (max-width: 1200px) {
           h1, h2 {
           font-size: 1.8rem;
         }

        .right-content{
           flex-direction: column;
           text-align: center;
         }

         .left-images {
           max-width: 60%;
         }

        }

      /* Tablets pequeñas y pantallas intermedias (hasta 992px) */
      @media (max-width: 992px) {
        .centro {
          flex-direction: column;
          align-items: center;
        }
        /* 1) Ocultar elemento */
        #Rodolfito, #Pancracio { 
          display: none !important;
        }

        /* 2) Hacer que el contenedor no reserve ancho innecesario */
        .left-images {
          width: auto !important;      /* quita el width:70%/max-width */
          max-width: none !important;
          margin-right: 0 !important;  /* quita margen que deja hueco a la derecha */
          gap: 12px !important;        /* reduce espacio entre items */
          justify-content: flex-start; /* alinear al inicio */
          align-items: center;
        }

        /* 3) Asegurar que los items no mantengan ancho mínimo */
        .left-images > * {
          min-width: 0 !important;
          flex: 0 0 auto !important;
          margin: 0 !important;
          padding: 0 !important;
        }

        /* 4) Ajustes de la imagen que queda visible */
        #Sandy img {
          display: block;
          width: auto;
          max-width: 100%;
          height: auto;
        }
        .right-content  {
          margin-top: 20px;
        }

      }

/* Celulares grandes (hasta 768px) */
@media (max-width: 768px) {
  h1 {
    font-size: 1.5rem;
  }

  .left-images  {
    width: 80%;
  }
}

/* Celulares pequeños (hasta 480px) */
@media (max-width: 480px) {
  body {
    padding: 0 10px;
  }

  h1 {
    font-size: 1.3rem;
  }

  p {
    font-size: 0.9rem;
  }

  .left-images{
    width: 100%;
  }
}
    </style>

  </div>
  </section>
  </section>
  <section class="about_us">
    <style>
      .about_us{
        background-color: white;
        position: relative;
        display: grid;
        grid-template-columns: 1fr 2fr 1fr;
        border-radius: 24px;
        padding: 40px;
        /*box-shadow: -4px 12px 52px -1px rgba(112,112,112,0.38);
        //-webkit-box-shadow: -4px 12px 52px -1px rgba(112,112,112,0.38);
        -moz-box-shadow: -4px 12px 52px -1px rgba(112,112,112,0.38);*/
      }
      .col-1, .col-2, .col-3 {
        position: relative;
        margin-right: 20px;
      }
      .col-1, .col-2{
        padding-right: 10px;
        border-right: 1px #79c1f3 solid;
      }
      .col-1 h3, .col-2 h3, .col-3 h3{
        text-align: center;
        font-weight: bold;
      }
      .col-1 p, .col-2 p, .col-3 p{
        text-align: justify;
      }
      .pancracio{
        position: absolute;
        right: 80%;
        height: 20%;
        bottom: 0;
      }
      .pancracio img{
        width: auto;
        height: 100%;
      }
      .sandy{
        position: absolute;
        bottom: 0;
        right: 55%;
        height: 15%;
      }
      .sandy img{
        width: auto;
        height: 100%;
      }
      .rodolfo{
        position: absolute;
        bottom: 0;
        left: 65%;
        height: 15%;
      }
      .rodolfo img{
        width: auto;
        height: 100%;
      }
      @media (max-width: 992px) {
        .sandy, .rodolfo, .pancracio{
          display: none;
        }
        .about_us{
          display: flex;
          flex-direction: column;
        }
        .col-1, .col-2{
        border-right: 0;
        border-bottom: 1px #79c1f3 solid;
      }
      }
    </style>
      <div class="col-1">
        <h3>Nuestra vision</h3>
        <br>
        <p>Incluir a las personas neurodivergentes en un proceso educativo mas adaptable para todos. 
Al final, ¡Todos somos diferentes!</p>
      </div>
      <div class="col-2">
        <h3> A traves de : </h3>
        <br>
        <p><em style="color: #99CF15">Rodolfo- El alien- :</em> Con (TDAH). Por eso es tan activo, ya que no puede quedarse quieto. 
          Está aquí para aprender en su forma.</p>
        <p><em style="color: #79c1f3">Pancracio - El astronauta- :</em> Con primer de autismo, es algo tímido para expresar sus ideas 
          y tiene dificultades para socializar. Junto con sus amigos intenta superar esa barrera. 
          Por eso también está aquí: romper  barreras.</p>
        <p><em style="color: #8094f3">Sandy - La gata astronauta- :</em> Con trastorno del procesamiento sensorial, con sus 5 sentidos 
          sensibles al mundo, se proteje  en su traje de astronauta, y sus amigos la acompañan para incluirla. 
          Por que su condicion no tiene que  aislarla.</p>
      </div>
      <div class="col-3">
        <h3> Objetivo: </h3>
        <br>
        <p> Romper el modelo único de las aulas tradicionales que no se adaptan a 
          mentes inquietas y creativas.</p>
        <p>Alineandonos con los ODS 4 —Educación de Calidad— y 10 —Reducción de Desigualdades—, 
          demostrando que las herramientas tecnológicas pueden ser profundamente humanas y para todos.</p>
      </div>
      <div class="pancracio"><img src="<?php echo get_stylesheet_directory_uri(); ?>/media/pancracio_about_us.jpeg" alt="Pancracio"/></div>
      <div class="sandy"><img src="<?php echo get_stylesheet_directory_uri(); ?>/media/sandy_about_us.png" alt="Sandy"/></div>
      <div class="rodolfo"><img src="<?php echo get_stylesheet_directory_uri(); ?>/media/rodolfo_about_us.png" alt="Rodolfo"/></div>
  </section>

</body>
</html>