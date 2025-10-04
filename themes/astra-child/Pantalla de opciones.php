<?php
/*
Template Name: Role Options
*/
get_header();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Roles | Neureka GS</title>

 <style>
    body {
      font-family: Helvetica;
      margin: 0;
      background-color: #f7f7f9;
      color: #333;
    }

 
    header {
      background-color: white;
      padding: 1rem 2rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 1px solid #ddd;
    }

    .logo {
      display: flex;
      align-items: center;
    }

    .logo img {
      height: 30px;
      margin-right: 10px;
    }

    .login-area {
      font-size: 0.9rem;
    }

    .login-area a {
      text-decoration: none;
      color: #666;
      margin-right: 10px;
    }

    .login-area button {
      background-color: #c3b8ff;
      color: #fff;
      border: none;
      padding: 0.5rem 1rem;
      border-radius: 8px;
      cursor: pointer;
    }

    .container {
      max-width: 1200px;
      margin: auto;
      padding: 2rem;
    }

    h1 {
      margin-bottom: 2rem;
    }

    .cards {
      display: flex;
      gap: 2rem;
      flex-wrap: wrap;
      justify-content: center;
    }

    .card {
      background-color: white;
      border-radius: 16px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
      padding: 1.5rem;
      text-align: center;
      width: 300px;
    }

    .card img {
      width: 200px;
      height: auto;
      margin-bottom: 1rem;
      justify-content: center;
    }

    .card h2 {
      font-size: 1.2rem;
      margin-bottom: 0.8rem;
    }

    .card p {
      font-size: 0.9rem;
      color: #555;
    }
  </style>

</head>

<body>

  <div class="container">
    <h1>Roles</h1>
    <div class="cards">
     <a href="https://genuinecreators.com/student-register/">
      <div class="card">
        <img src="<?php echo get_stylesheet_directory_uri(); ?>/media/Rodolfo-manitos-alzadas.png" alt="Soy estudiante">
        <h2>Soy estudiante</h2>
        <p>Me dispongo a aprender e interactuar con los retos de pensamiento computacional, mis apuntes y las correcciones del profe.</p>
      </div>
      </a>
      <a href="https://genuinecreators.com/parent-register/">
      <div class="card">
        <img src="<?php echo get_stylesheet_directory_uri(); ?>/media/astronauta.png" alt="Soy padre o madre">
        <h2>Soy padre o madre</h2>
        <p>Acompaño y superviso el proceso de aprendizaje de mi hijo/a, reconociendo su proceso y apoyándolo en el mismo.</p>
      </div>
      </a>
      <a href="https://genuinecreators.com/teacher-register/">
      <div class="card">
        <img src="<?php echo get_stylesheet_directory_uri(); ?>/media/Gato-espacial.png" alt="Soy profesor">
        <h2>Soy profesor</h2>
        <p>Guío, acompaño, superviso y apoyo al estudiante, corrigiéndolo, felicitándolo y llevando una buena comunicación con sus familiares.</p>
      </div>
      </a>
    </div>
  </div>

</body>
</html>
