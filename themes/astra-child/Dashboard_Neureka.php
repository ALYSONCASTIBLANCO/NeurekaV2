<?php
/*
Template Name: Dashboard Page
*/
 
get_header();
$current_user_id = get_current_user_id();
$current_user    = wp_get_current_user();

// Clear cache to ensure fresh data
clean_user_cache($current_user_id);
wp_cache_delete($current_user_id, 'users');
global $wpdb;
$post_type="planeta";


if (!$current_user_id || empty($current_user->roles)) {
    echo "<p>No hay usuario logueado.</p>";
    return;
}

$student_code = in_array('um_student', (array)$current_user->roles)
                ? get_user_meta($current_user_id, 'students_code', true)
                : 0;
$usr_name = $current_user->display_name;
$user_mail = $current_user->user_email;
$points_total = get_user_meta($current_user_id, '_gamipress_punto_points', true);
$achievements = 0;
$achievements = $wpdb->get_var(
    $wpdb->prepare(
        "SELECT COUNT(DISTINCT title)
         FROM {$wpdb->prefix}gamipress_user_earnings
         WHERE user_id = %d
         AND post_type = %s",
        $current_user_id,
        $post_type
    )
);


$levels = [
    1 => ['title' => 'Aspirante', 'h5p_id' => 1, 'achievement_id' => 74, 'post_id' => 56],
    2 => ['title' => 'Explorador', 'h5p_id' => 2, 'achievement_id' => 77, 'post_id' => 148],
    3 => ['title' => 'Especialista', 'h5p_id' => 3, 'achievement_id' => 79, 'post_id' => 157],
    4 => ['title' => 'Comandante', 'h5p_id' => 4, 'achievement_id' => 251, 'post_id' => 331],
    5 => ['title' => 'Maestro', 'h5p_id' => 5, 'achievement_id' => 31, 'post_id' => 336],
]; 

//We will save this info in this variable.
$latest_activity = $wpdb->get_row(
    $wpdb->prepare(
        "SELECT u.*, c.title
         FROM {$wpdb->prefix}h5p_contents_user_data u
         JOIN {$wpdb->prefix}h5p_contents c ON u.content_id = c.id
         WHERE u.user_id = %d
         ORDER BY u.updated_at DESC
         LIMIT 1",
        $current_user_id
    )
);
//This variable will be useful to say to the system what will be the next level.
$next_level = null;
//We will need this variable to capture the progress level.
$progress_percent = 0;

if ($latest_activity) {
  //We will do another SQL Query to get all the information about this activity and the content. At the end, we will have a JSON
  //Object. Here we can extract information like max score, partial score, chapters, etc.
    $book_info = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT data FROM {$wpdb->prefix}h5p_contents_user_data
             WHERE user_id = %d AND content_id = %d AND data_id = 'state'",
            $current_user_id, $latest_activity->content_id
        )
    );
    
    if ($book_info) {
      //As I told you previously, the query result will be a JSON file, and we need to decode to process the information better.
        $json = json_decode($book_info->data, true);
            //Verifying that the json with the level information is present, if is not the case, we won't extract nothing.
        if (!$json || !isset($json["content"])) {
            $progress_percent = 0;
            $next_level = $levels[1];
 
          }

            // Find index of last completed level
            $completed_level_index = null;
        //Verifying if the user completed the level
            if (isset($json["content"]["gameDone"]) && $json["content"]["gameDone"] === true) {
              //If is the case, the percentage will be 100%
                $progress_percent=100;
                //to get the last level completed, we need to find the index if this activity is done, because we will use the levels
                //array to get the information. Basically, we're gonna check element by element of this array, and if element has the same
                //name of the last element, we will take the index.
                foreach ($levels as $i => $lvl) {
                    if ($latest_activity->title == $lvl['title']) {
                        $completed_level_index = $i;
                        break;
                    }
                }
 
                // Determine next level
                if (isset($completed_level_index)) {
                    $next_index = $completed_level_index + 1;
                    if (isset($levels[$next_index])) {
                        $next_level = $levels[$next_index];
                    }
                }
            }
            
            //If the user didn't complete the level, then we will count the completed excercise. Each exercise is a bundle.
            else {
                // Finding the exercises or bundles in the level. If doesn't find anything, will return an empty array.
                $bundles = $json["content"]["exerciseBundles"] ?? [];
                //We will count the amount of exercises.
                $total = count($bundles);
                //A variable that will count the completed exercises.
                $completed = 0;
                //For each bundle, will verify if this exercise was completed or not. If is the case, will increase the completed variable
                //in one.
                foreach ($bundles as $bundleWrapper) {
                  if (isset($bundleWrapper["exerciseBundle"]["isCompleted"]) && $bundleWrapper["exerciseBundle"]["isCompleted"]) {
                      $completed++;
                  }
              }
              //Calculating the progress percentage and rounding the result for removing decimals.
              $progress_percent = ($total > 0) ? round(($completed / $total) * 100) : 0;
                foreach ($levels as $i => $lvl) {
                    if ($latest_activity->title == $lvl['title']) {
                        $completed_level_index = $i;
                        break;
                    }
                }
                
                // Level not completed, next_level is same as current
                foreach ($levels as $lvl) {
                    if ($latest_activity->title == $lvl['title']) {
                        $next_level = $lvl;
                        break;
                    }
                }
                
                
            }
    }
}
    

else {
    // User has no activity, start from first level
    $next_level = $levels[1];
    $progress_percent = 0;
}

?> 

<html lan="es"> 

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
     
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/fill/style.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/bold/style.css">
     <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/regular/style.css"/>
 <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/fill/style.css"/>

 
<style>    /* =====================
     BASE & LAYOUT
  ===================== */
  body {
    font-family: Arial, sans-serif;
    background: linear-gradient(to bottom, #f4f5ff, #f9f6ff);    /* margin: 150px; — conflicts with the header, keep commented */
    padding: 20px;
    color: #333;
  }
 
  p {
    margin-bottom: 0;
  }
 
  .dashboard {
    width: 100vw;
    display: flex;
    flex-direction: column;
  }

  .intercalated-background {

  }
 
 
  /* =====================
     WELCOME BANNER
  ===================== */
  .welcome {
    display: block;
    width: 70vw;
    background-color: #fce3c8;
          /* pastel-green:b3dbeb lighter-blue:afd3f9 light-orange:fce3c8 */
    padding: 30px;
    margin-top: 25px;
    margin-bottom: 30px;
    border-radius: 5px;
  }
 
  .welcome h1 {
    margin: 0 0 6px 0;
    font-size: 28px;
    font-weight: 700;
    color: #1c256a;
    font-size: 35px;
  }
 
  .welcome .welcome-code {
    font-size: 16px;
    color: #1c256a;
  }

  .icon-subtitle {
    display: flex;
    align: center;
    gap: 5px; 
  }

  .icon-subtitle i{
    margin: 0;
    font-size: 18px;
    color: #1c256a;
  }
 
  .welcome .welcome-subtitle {
    font-size: 17px;
    font-weight: 400;
    color: #1c256a;
    margin-top: 10px;
  }
 
 
  /* =====================
     STATS CARDS
  ===================== */
  .stats {
    display: flex;
    gap: 20px;
    justify-content: center;
    padding: 50px;
    border-radius: 10px 10px 0 0;
    background-color: #ccc7ff;

  }
 
  .card {
    background: white;
    border-radius: 10px;
    padding: 20px;
    color: #fff9f5;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    flex: 0 0 auto;
    width: 210px;
  }

  .card-title {
  
  }
  
  .card-title h3{
    margin-bottom: 10px;
    font-size: 16px;
    font-weight: 550;
    color: #1c256a;
    align-self: flex-start;
  }
 
  .card h2 {
    margin: 0;
    font-size: 60px;
    font-weight: bold;
    color: #9999fb;
  }

  .card-data {
    display: flex;
    align-items: center;
    gap: 6px;
  }

  .card-data i{
    font-size: 45px;
    color: #9999fb;
  }
 
  .card p {
    margin: 5px 0 0;
    font-size: 14px;
    color: #555;
  }
 
  .card-text {
    display: block;    
  }

  .text {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;

  }

 
 
  /* =====================
     SECTIONS
  ===================== */
  .play-progress-sections {
    display: flex;
    gap: 120px;
    justify-content: center;
    background-color: #dbd8ff;
  }
  
  .section {
    margin-top: 65px;
    margin-bottom: 65px;
  }
 
  .section h3 {
    font-size: 25px;
    font-weight: 550;
    margin-bottom: 10px;
    color: #1c256a;
  }

  .section-title {
    text-align: center;
    
  }
 
  .section-content {
    margin-top: 1%;
    margin-bottom: 2%;
    display: flex;
    flex-direction: column;
    align-items: center;
    
  }

  .section-with-rodolfo {
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .rodolfo-with-puzzle img {
    width: 220px;
    height: auto;
  }

  .section-with-pancracio {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 25px;
  }

  .pancracio-rocket img{
    width: 250px;
    height: auto;
  }
 
  .play-button {
    margin-bottom: 2em;
    display: flex;
    justify-content: center;
  }

  .play-button button {
    padding: 16px 60px;
    font-size: 18px;
    font-weight: 300;
    color: #331bcc;
    background-color: #C9C5F6;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    transition: background-color 0.2s ease, transform 0.1s ease;
  }

 
  /* =====================
     PROGRESS
  ===================== */
  .level {
    display: flex;
    gap: 5px;
  }
  
  
  .progress-bar {
    height: 26px;
    width: 100%;
    background-color: #f5f5f5;
    border-radius: 10px;
    overflow: hidden;
    margin: 10px 0 2em 0;
  }
 
  .progress {
    width: <?php echo $progress_percent; ?>%;
    height: 100%;
    background-color: #a59dff;
    border-radius: 10px;
    transition: width 0.5s ease;
  }

  .progress p{
    margin-left: 15px;
  }


  .level-section{
    padding: 30px;
    background-color: #eae9ff; 
    border-radius: 0 0 10px 10px;
  }
  
  .level-section h3 {
    font-size: 25px;
    font-weight: 550;
    margin-bottom: 10px;
    color: #1c256a;
  }

  .level-section-title {
    text-align: left;
    margin-left: 40px;
  }

  .levels {
    display: flex;
    gap: 20px;
    margin-bottom: 25px;
    justify-content: center;
  }

  .level-character  {

  }

  .card-character {
    background: white;
    border-radius: 10px;
    padding: 20px;
    color: #fff9f5;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    flex: 0 0 auto;
    width: 210px;
  }

  .character-title {
  
  }
  
  .character-title h3{
    margin-bottom: 10px;
    font-size: 16px;
    font-weight: 550;
    color: #1c256a;
    align-self: flex-start;
  }
 
  .card-character h2 {
    margin: 0;
    font-size: 60px;
    font-weight: bold;
    color: #9999fb;
  }

  .character-data {
    display: flex;
    align-items: center;
    gap: 6px;
  }

  .card-character i{
    font-size: 45px;
    color: #9999fb;
  }
 
  .card-character p {
    margin: 5px 0 0;
    font-size: 14px;
    color: #555;
    display: block;
  }
  
  .a {
      width: 210px;
  }
  
  a:hover{
    cursor: pointer !important;
    transform: translateY(-2px); /* subtle lift effect */
  }
  
a:active {
    transform: translateY(0); /* resets lift when clicked */
    }
    
    button:hover {
        cursor: pointer !important;
        transform: translateY(-2px); /* subtle lift effect */
    }
    
    button:active {
        transform: translateY(0); /* resets lift when clicked */
    }
 
 
 
    /* =====================
     RESPONSIVE — phones, tablet, computer.
  ===================== */
  


@media (min-width: 1400px) {
    .play-progress-sections {gap: 5px;}
    .welcome,
    .stats,
    .play-progress-sections, 
    .level-section {
      max-width: 1300px;
      width: 93vw;
    }
    .levels {gap: 2px;}
    .card-character {width: 80%;}
    .card-character p{display: none;}
    .levels {gap: 20px;}
    .a {width: 80%;}
}  

@media (max-width: 1399px) {
    .play-progress-sections {gap: 5px;}
    .welcome,
    .stats,
    .play-progress-sections,
    .level-section {
      width: 93vw;
      max-width: 1300px;
    }
    .card-character {width: 100%;}
    .card-character p{display: none;}
    .levels {gap: 20px;}
    .a {width: 60%;}
}  

@media (max-width: 921px) {

  .welcome {width: 93vw;}
    .stats {width: 93vw;}
    .card-title h3{font-size: 15px;}
    .card-text {display: none;}
    .card-data h2{font-size: 35px;}
    .card-data i{font-size: 25px;}
    .card {width: 30%;}
    .stats {gap: 10px;}
    .play-progress-sections {
      flex-direction: column;
      justify-content: center;
      gap: 0px;
      width: 93vw;
    }
    .section {margin-top: 10px; margin-bottom: 10px;}

    .section-with-rodolfo img{width: 160px;}
    .section-with-rodolfo {padding-left: 50px; margin-top: 30px; margin-bottom: 0;}

    .section-with-pancracio {padding-left: 50px;}
    .section-with-pancracio h3{font-size: 25px;}
    .section-with-pancracio img{width: 160px;}
    .section-with-pancracio {margin-top: 0px; margin-bottom: 30px;}
    .levels {
      flex-direction: column;
      text-align: center;
  }
    .level-section {width: 93vw;}
    .card-character {
      width: 60%;
      align-items: center;
      margin: 0 auto;
    }
    .card-character img{width: 70%;}
}

@media (max-width: 520px) {
    .welcome {width: 93vw;}
    .stats {width: 93vw;}
    .card-title h3{font-size: 15px;}
    .card-text {display: none;}
    .card-data h2{font-size: 35px;}
    .card-data i{font-size: 25px;}
    .card {width: 40%;}
    .stats {gap: 10px;}
    .play-progress-sections {
      flex-direction: column;
      justify-content: center;
      gap: 0px;
      width: 93vw;
    }
    .section {margin-top: 10px; margin-bottom: 10px;}

    .section-with-rodolfo img{width: 90%;}
    .section-with-rodolfo {padding-left: 50px; margin-top: 30px; margin-bottom: 0;}

    .section-with-pancracio {padding-left: 50px;}
    .section-with-pancracio h3{font-size: 25px;}
    .section-with-pancracio img{width: 90%;}
    .section-with-pancracio {margin-top: 0px; margin-bottom: 30px;}
    .levels {
      flex-direction: column;
      text-align: center;
  }
    .level-section {width: 93vw;}
    .card-character {
      width: 60%;
      align-items: center;
      margin: 0 auto;
    }
    .card-character img{width: 70%;}
}
    

 
</style>
 
 
<!-- =====================
     DASHBOARD WRAPPER
===================== -->
<div class="dashboard">
 
 
  <!-- WELCOME BANNER -->
  <div class="welcome">
    <h1>¡Hola, <?php echo $usr_name ?>!</h1>
    <div class="icon-subtitle"><i class="ph ph-key"></i><p class="welcome-code">Codigo unico de estudiante : <?php echo $student_code ?></p></div>
    <p class="welcome-subtitle">Bienvenido(a) a la aventura, aprendiendo de la mano juntos.</p>
  </div>
 

<div class="intercalated-background"></div> <!-- Intercalated Background of the dashboard content -->

  
<!-- STATS CARDS -->
  <div class="stats">

    <div class="card">
      <div class="card-title"><h3>Nivel Actual</h3></div>
      <div class="card-data">
        <h2><?php echo $completed_level_index?></h2> <!-- $completed_level_index -->
        <div class="card-icon"><i class="ph ph-map-pin"></i></div>
      </div>
      <div class="card-text">
        <p>Vamos con toda para el seguiente! Tu puedes!</p>
      </div>
    </div>
 
    <div class="card">
      <div class="card-title"><h3>Puntos</h3></div>
      <div class="card-data">
        <h2><?php echo $points_total ?></h2>
        <div class="card-icon"><i class="ph ph-star-four"></i></div>
      </div>
      <div class="card-text">
        <p>!Sigue jugando para seguir aprendendo juntos!</p>
      </div>
      </div>
 
    <div class="card">
      <div class="card-title"><h3>Insignias</h3></div>
      <div class="card-data">
        <h2><?php echo $achievements ?></h2>
        <div class="card-icon"><i class="ph ph-medal"></i>
      </div>
      </div>
      <div class="card-text">
        <p>Tus insignias no define tu valor, mide tu progresso</p>
      </div>
    </div>

    <!-- <div class="card">
      <div class="card-title"><h3>Partidas Jugadas</h3></div>
      <div class="card-data">
        <h2>67</h2>
        <div class="card-icon"><i class="ph ph-puzzle-piece"></i>
      </div>
      </div>
      <div class="text">
        <p>!Felicitaciones, a por mas!</p>
      </div>
    </div> --> 
 
  </div><!-- /stats --> 
  


<!-- PLAY AND PROGRESS SECTIONS WITH SIDE BY SIDE ALIGNMENT -->
<div class="play-progress-sections">
<?php $h5p_latest_url = get_permalink($levels[$completed_level_index]['post_id']);
        $h5p_url= get_permalink($next_level['post_id']); ?>
  <div class="section-with-rodolfo">

    <div class="section"> <!-- Play Area -->
      <div class="section-title"><h3>Juega con Rodolfo</h3></div>
      <div class="play-button">
      <button onclick="location.href='<?php echo esc_url($h5p_url); ?>'">Juega</button>
    </div>
    </div><!-- /play area -->

  <div class="rodolfo-with-puzzle"> <!-- Dear Rodolfo -->
    <img src="<?php echo get_stylesheet_directory_uri(); ?>/media/rodolfo-with-puzzle.svg" alt="Rodolfo">  
  </div><!-- /dear rodolfo -->

  </div>

 

  <div class="section-with-pancracio">

    <div class="section"> <!-- YOUR PROGRESS -->
      <div class="section-title"><h3>Tu Progreso</h3></div>
 
    <div class="section-content">
 
      <?php if ($latest_activity):
        $h5p_latest_url = get_permalink($levels[$completed_level_index]['post_id']);
        $h5p_url        = get_permalink($next_level['post_id']); ?>
 
  
 
        <div class="level"><p class="text">Current Level: </p> <p class="text"><?php echo $latest_activity->title; ?></p></div>
         
        <div class="progress-bar">
          <div class="progress">
            <p><?php echo $progress_percent; ?>%</p>
          </div>
        </div>

      <?php endif; ?>
 
      <?php if ($next_level): ?>
 
        <?php $h5p_url = get_permalink($next_level['post_id']); ?>
        <p class="little-title">Next level:</p>
        <p class="little-text"><?php echo esc_html($next_level['title']); ?></p>
 
      <?php else: ?>
        <p>¡Felicidades! Has completado <br>todos los niveles disponibles.</p>
      <?php endif; ?>
 
      </div><!-- /section-content -->
    </div><!-- /section: progress -->

      <div class="pancracio-rocket"> <!-- Dear Rodolfo -->
        <img src="<?php echo get_stylesheet_directory_uri(); ?>/media/pancracio-rocket.svg" alt="PancracioR">  
      </div><!-- /dear Pancracio -->

    </div><!-- /section-with-pancracio-->

</div><!-- play-progress-sections    -->

<div class="level-section"> <!-- Elige la aventura que deseas jugar -->
        <div class="level-section-title"><h3>Elige la aventura que deseas jugar</h3></div>
  <div class="levels">
    
    <a href="<?php echo esc_url(get_permalink(56));?>"> 
      <div class="card-character">
      <div class="character-title"><h3>Nivel 1</h3></div>
      <div class="character-data">
        <div class="level-character"><img src="<?php echo get_stylesheet_directory_uri(); ?>/media/pancracio.svg" alt="Pancracio"></img></div>
      </div>
      <div class="card-text">
        <p>!Se un astronauta!</p>
      </div>
    </div>
    </a>

    <a href="<?php echo esc_url(get_permalink(148));?>">
    <div class="card-character">
      <div class="character-title"><h3>Nivel 2</h3></div>
      <div class="character-data">
        <div class="level-character"><img src="<?php echo get_stylesheet_directory_uri(); ?>/media/rodolfo.svg" alt="Pancracio"></img></div>
      </div>
      <div class="card-text">
        <p>Aventura con Rodolfo</p>
      </div>
    </div>
    </a>
    
    <a href="<?php echo esc_url(get_permalink(157));?>">
    <div class="card-character">
      <div class="character-title"><h3>Nivel 3</h3></div>
      <div class="character-data">
        <div class="level-character"><img src="<?php echo get_stylesheet_directory_uri(); ?>/media/sandy.svg" alt="Sandy"></img></div>
      </div>
      <div class="card-text">
        <p>Atraves del sistema solar com Sandy</p>
      </div>
    </div>
    </a>
    
    <a href="<?php echo esc_url(get_permalink(331));?>">
    <div class="card-character">
      <div class="character-title"><h3>Nivel 4</h3></div>
      <div class="character-data">
        <div class="level-character"><img src="<?php echo get_stylesheet_directory_uri(); ?>/media/solecito.svg" alt="Solecito"></img></div>
      </div>
      <div class="card-text">
        <p>Solecitos believes in you</p>
      </div>
    </div>
    </a>
    
    <a href="<?php echo esc_url(get_permalink(336));?>">
    <div class="card-character">
      <div class="character-title"><h3>Nivel 5</h3></div>
      <div class="character-data">
        <div class="level-character"><img src="<?php echo get_stylesheet_directory_uri(); ?>/media/rene.svg" alt="Rene"></img></div>
      </div>
      <div class="card-text">
        <p>Go beyond with Rene</p>
      </div>
    </div>
    </a>


  
  </div>
    </div><!-- /Elige la aventura que deseas jugar -->

</div> <!-- /Intercalated Background of the dashboard content -->



 
 
</div><!-- /dashboard -->
 