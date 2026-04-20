<?php
/*
Template Name: Homepage
*/

get_header();
?>
<style>
      .title{
       color: #1C256A;
       text-align: center;
       font-size: 2.2rem;
       font-weight: bold;
       padding-top: 50px;
       margin-bottom: 0.5rem;
       
      }

      .left-images {
        flex: 1;
        /*Ocupa espacio proporcional, ¿Que quierede decir? */ 
        margin-right: 2rem;
        /*Esto separa del lado derecho por que es una margen que separa cosas*/
      }

      .parent {
        display: grid;
        /* Usamos 6 columnas para poder centrar perfectamente 2 elementos debajo de 3 */
        grid-template-columns: repeat(6, 1fr);
        gap: 20px 10px;
        justify-items: center;
        align-items: end;
      }

      .character-card {
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
      }

      .character-card img {
        width: 160px; /* Ajustado para mejor visualización */
        height: auto;
        transition: transform 0.3s ease;
      }

      .character-card:hover img {
        transform: scale(1.05);
      }

      .character-card h4 {
        margin: 10px 0 0 0;
        color: #1C256A;
        font-size: 1.3rem;
        font-weight: 550;
      }

      .character-card p {
        margin: 0;
        color: #9DA7BB; /* Color grisáceo/azulado suave para el subtítulo */
        font-size: 1.1rem;
        font-weight: 500;
      }

      /* Posicionamiento en el Grid */
      /* Fila 1: 3 elementos */
      .Rodolfito { grid-column: 1 / span 2; grid-row: 1; } /*Estos jerogrificos son que empieza en columna 1 y ocupa 2 columnas*/ 
      .Pancracio { grid-column: 3 / span 2; grid-row: 1; }
      .Sandy     { grid-column: 5 / span 2; grid-row: 1; }

      /* Fila 2: 2 elementos centrados (ocupan las columnas centrales de sus respectivos huecos) */
      .Solecito  { grid-column: 2 / span 2; grid-row: 2; }
      .Rene      { grid-column: 4 / span 2; grid-row: 2; }

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
       color: #1C256A;
      }
        
      .right-content p {
         font-size: 1.1rem;
         margin-bottom: 1.5rem;
         font-weight: lighter;
         color: #1C256A;
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
.important-stuff{
  text-align:center;
}
.description b {
  color:#8094f4
  font-weight: 700;
  background: hsla(175, 64%, 76%, 0.15);
  padding: 2px 6px;
  border-radius: 6px;;
}
.parent2{
  display: grid;
  grid-template-columns: repeat(6, 1fr);
  gap: 40px 20px;
  justify-items: center;  
}

 .card{
 background-color: #EDEAFB; /* más suave como mockup */
  width: 260px;
  min-height: 300px;
  border-radius: 20px;
  padding: 18px;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  transition: transform 0.3s ease;
}

.card:hover {
  transform: translateY(-5px); /*Animación*/ 
}

 .sub-title{
  color:#1C256A;
  font-weight: bold;
  font-weight: 700;
  font-size: 1rem;
  margin-bottom: 6px;
}

   .card p {
        margin: 0;
        color: #6B7280; 
        font-size: 0.85rem; /* más pequeño*/
        line-height: 1.3;
      }
.card img{
   width: 150px;
  height: auto;
  align-self: center;
  margin-top: 10px;

}

.cards{
    margin:25px;
}

    /* Posicionamiento en el Grid */
      /* Fila 1: 3 elementos */
      .Rodolfito { grid-column: 1 / span 2; grid-row: 1; } /* grid-column: dice dónde empieza y hasta donde llega en columnas izquierda-> derecha*/ 
      .Pancracio { grid-column: 3 / span 2; grid-row: 1; }/*span: Columnas a ocupar, siendo mas flexible - tamaño-*/
      .Sandy     { grid-column: 5 / span 2; grid-row: 1; }/*grid-row: Lo mismo del grid-column pero en veritical, arriba -> abajo*/

      /* Fila 2: 2 elementos centrados (ocupan las columnas centrales de sus respectivos huecos) */
      .Solecito  { grid-column: 2 / span 2; grid-row: 2; }
      .Rene      { grid-column: 4 / span 2; grid-row: 2; }
    </style>
    
  <div class="container-fluid">
    <section class="centro"
     style="display: flex; justify-content: space-between; align-items: center;height: 80vh; padding: 0.5%">
     <div class="left-images">
    <div>
      <h1 class= "title">Conoce a nuestros amigos</h1>
    </div>
   <div class="parent">
    <div class="character-card Rodolfito">
      <img src="<?php echo get_stylesheet_directory_uri(); ?>/media/Rodolfo.png" alt="Rodolfo">
      <div class="description">
        <h4>Rodolfo</h4>
        <p>TDAH</p>
      </div>
    </div>
    
    <div class="character-card Pancracio">
      <img src="<?php echo get_stylesheet_directory_uri(); ?>/media/Pancracio.png" alt="Pancracio">
      <div class="description">
        <h4>Pancracio</h4>
        <p>Autista</p>
      </div>
    </div>
    
    <div class="character-card Sandy">
      <img src="<?php echo get_stylesheet_directory_uri(); ?>/media/Sandy.png" alt="Sandy">
      <div class="description">
        <h4>Sandy</h4>
        <p>Sensorial</p>
      </div>
    </div>
    
    <div class="character-card Solecito">
      <img src="<?php echo get_stylesheet_directory_uri(); ?>/media/Solecito.png" alt="Solecito">
      <div class="description">
        <h4>Solecito</h4>
        <p>Dislexia</p>
      </div>
    </div>
    
    <div class="character-card Rene">
      <img src="<?php echo get_stylesheet_directory_uri(); ?>/media/Rene.png" alt="Rene">
      <div class="description">
        <h4>Rene</h4>
        <p>Bipolar</p>
      </div>
    </div>
   </div>     
 </div>

  <div class="right-content" style="color: #1C256A;">
    <div style="margin-left: 50px;">
     <h1>¡Inclusión,<br> diversión y <br> aprendizaje!</h1>
     <p>¿Qué más podemos pedir?</p>
     <a href="https://genuinecreators.com/role-options/" class="join-btn">
      <img src="<?php echo get_stylesheet_directory_uri(); ?>/media/Avion-lateral-derecha.png" alt="Avión" class="btn-icon1">
      ¡Únete Ahora!
      <img src="<?php echo get_stylesheet_directory_uri(); ?>/media/Avion-lateral-izquierda.png" alt = "Avión" class="btn-icon2 ">
    </a>
  </div>
  </div>
  </section>
  <section class="about_us">
   <div class="center-text">
        <div class= "important-stuff">
         <h2 class="title" >Objetivo:</h2>
         <br>
         <p>Romper el modelo único de las aulas tradicionales que no se adaptan a 
          mentes inquietas y creativas. <b>Alineandonos con los ODS 4 —Educación de Calidad— y 10 —Reducción de Desigualdades—</b>, 
          demostrando que las herramientas tecnológicas pueden ser profundamente humanas y para todos.</p>
          <h2 class="title">Nuestra Vision: </h2>
          <br>
          <p> Incluir a las personas neurodivergentes en un proceso educativo mas adaptable para todos. Al final,<b> ¡Todos somos diferentes!. </b></p>
       </div>
      </div>
</section>
<section class="cards"> 
        <h3 class= "title"> A traves de : </h3>
        <br>
       <div class="parent2">
        <div class="card Rodolfito">
         <div class="description">
           <h5 class="sub-title">Rodolfo-El alien:</h5>
           <p>Con <b>(TDAH)</b>. Es hiperactivo, muy pocas veces se queda quieto; aún asi, siempre se preocupa por sus amigos,acompañandolos en todo momento.</p>
         </div>
         <img src="<?php echo get_stylesheet_directory_uri(); ?>/media/Rodolfo.png" alt="Rodolfo">
        </div>
    
        <div class="card Pancracio">
         <h5 class="sub-title">Pancracio-El austronauta:</h5>
         <div class="description">
           <p>Con <b>(TEA 1)</b>. Le dificulta expresarse, pero junto a sus amigos aprendera a aceptarse y no ver eso como una desventaja, sino como un don.</p>
         </div>
         <img src="<?php echo get_stylesheet_directory_uri(); ?>/media/Pancracio.png" alt="Pancracio">
      </div>  
    
    <div class="card Sandy">
     <div class="description">
        <h5 class="sub-title">Sandy-La gata espacial:</h5>
        <p>Con <b>(TPA)</b>, sensible al mundo, se proteje en su traje de astronauta; sus amigos la acompañan, por que su condicion no tiene que aislarla.</p>
      </div>
      <img src="<?php echo get_stylesheet_directory_uri(); ?>/media/Sandy.png" alt="Sandy">
    </div>
    
    <div class="card Solecito">
    <div class="description">
        <h5 class="sub-title">Solecito-La Pájarita espacial:</h5>
        <p>Con <b>dislexia</b> se le dificulta comprender, específicamente lo académico, está aprendendiendo que su autenticidad no la definen los demás. </p>
       </div>
      <img src="<?php echo get_stylesheet_directory_uri(); ?>/media/Solecito.png" alt="Solecito">
    </div>
    
    <div class="card Rene">
    <div class="description">
        <h5 class="sub-title">Rene-El sapo volador:</h5>
        <p>Con <b>bipolaridad</b>, nunca baja del platillo, viajando entre galaxias de emociones. Sus cambios no son una debilidad, le permite ver la realidad desde múltiples perspectivas.</p>
      </div>
      <img src="<?php echo get_stylesheet_directory_uri(); ?>/media/Rene.png" alt="Rene">
    </div>
   </div>     
</section>
      
  <?php get_footer(); ?>