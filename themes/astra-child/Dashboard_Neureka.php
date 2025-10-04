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
$triggers = maybe_unserialize(get_user_meta($current_user_id, '_gamipress_triggered_triggers', true));
$achievements = 0;
if (!empty($triggers) && isset($triggers['gamipress_unlock_planeta'])) {
  //We will assign this value to the variable.
    $achievements = (int) $triggers['gamipress_unlock_planeta'];
}
else {
  //If the user doesn't have achievements, will be zero.
  $achievements=0;
}
$levels = [
    1 => ['title' => 'Easy Level', 'h5p_id' => 1, 'achievement_id' => 74, 'post_id' => 56],
    2 => ['title' => 'Intermediate Level', 'h5p_id' => 2, 'achievement_id' => 79, 'post_id' => 148],
    3 => ['title' => 'Hard Level', 'h5p_id' => 3, 'achievement_id' => 77, 'post_id' => 157],
]; 
global $wpdb;
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
        //This object can give us two parameters: score (amount of correct answers) and maxScore (amount of activities per level).
        if (isset($json['score'], $json['maxScore'])) {
          //We will represent the progress in percentage, doing this operation. We will use this value for the progress bar that you
          //have down.
            $progress_percent = round(($json['score'] / $json['maxScore']) * 100);
            
    if ($json['score'] == $json['maxScore']) {
                // Find index of last completed level
                $completed_level_index = null;
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
            } else {
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
}

else {
    // User has no activity, start from first level
    $next_level = $levels[1];
    $progress_percent = 0;
}
?> 

</DOCTYPE html>

<html lan="es"> 

<head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>Dashboard - Neureka GS</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
     
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/fill/style.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/bold/style.css">

 
    <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f7f7f7;
      /*This margin creates conflicts with the header.*/
      /*margin: 150px;*/
      padding: 20px;
      color: #333;
    }
    .dashboard{
        width: 100vw;
        display: flex;
        flex-direction: column;
        margin-top: 50px;
    }

    h1 {
      font-size: 30px;
      margin-bottom: 4px;
    }

    .subtext {
      color: #777;
      font-size: 16px;
      margin-bottom: 40px;
    }

    .stats {
      display: flex;
      gap: 20px;
      margin-bottom: 25px;
    }

    .card {
      background: white;
      border-radius: 10px;
      /*I will comment this line because this flex is affecting the cards proportion*/
      flex: 0.5;
      display: flex;
      align-items: center;
      padding: 10px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }

    .text {
    display: flex;
    flex-direction: column;
    align-items: center; /* centers 22 + label */
    text-align: center;
    }


    .card img {
      width: 40px;
      height: 40px;
      margin-bottom: 10px;
      flex-shrink: 0;

    }

    #streak-icon {
      width: 30%;
      height: 82%;
      margin-right: 10%;
      margin-left: 10%;
    }

    #points-icon {
      width: 20%;
      height: 60%;
      margin-right: 20%;
      margin-left: 10%;
    }

    #achievements-icon {
      width: 30%;
      height: 88%;
      margin-right: 10%;
      margin-left: 10%;
    }
    .card h2 {
      margin: 0;
      font-size: 22px;
      font-weight: bold;
    }

    .card p {
      margin: 5px 0 0;
      font-size: 14px;
      color: #555;
    }

    .section {
      margin-bottom: 50px;
    }
  
    .expandable-section {
      padding-left: 2%;
      margin-top: 1%;
      margin-bottom: 2%;
    }

    .section h3 {
      font-size: 25px;
      margin-bottom: 10px;
    }

    .progress-bar {
      background: #ddd;
      border-radius: 8px;
      overflow: hidden;
      height: 12px;
      margin: 10px 0;
      margin-right: 58%;
    }

    .progress {
      background: linear-gradient(90deg, #9a6eff, #b9a7ff);
      width: 60%;
      height: 100%;
    }

    .account-box {
      background: white;
      padding: 15px;
      border-radius: 10px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }

    .account-box p {
      margin: 5px 0;
      font-size: 16px;
    }
    
    </style>

</head>

<body>
    <div class="dashboard">
        <h1>Good Morning, <?php echo $usr_name ?></h1>
  <p class="subtext">🔑 Student Unique Code: <?php echo $student_code ?></p>

  <div class="stats">
      
  <!-- <div class="card">
    <img id="streak-icon" src="
    /media/fire-streak-icon.svg" alt="streak">
    <div class="text">
      <h2>7</h2>
      <p>Day Streak</p>
    </div> --> 
    
  <div class="card">
    <img id="points-icon" src="<?php echo get_stylesheet_directory_uri(); ?>/media/point-star-point.png" alt="points">
    <div class="text">
      <h2><?php echo $points_total ?></h2>
      <p>Points</p>
    </div>
  </div>
  <div class="card">
    <img id="achievements-icon" src="<?php echo get_stylesheet_directory_uri(); ?>/media/achievement-icon.png" alt="achievements">
    <div class="text">
      <h2><?php echo $achievements ?></h2>
      <p>Achievements</p>
    </div>
  </div>
</div>
  
    <div class="section">
    <h3>Your Progress</h3>
    
    <div class="expandable-section">
        <?php if ($latest_activity && isset($completed_level_index) && isset($levels[$completed_level_index])): ?>
        <p><strong>Latest activity:</strong> <a href="<?php
        $h5p_latest_url=get_permalink($levels[$completed_level_index]['post_id']) ;
        echo esc_url($h5p_latest_url); ?>"><?php echo $latest_activity->title; ?></a></p>
        <div class="progress-bar"><div class="progress"></div></div>
        <?php endif; ?>
 
        <?php if ($next_level):
        $h5p_url = get_permalink($next_level['post_id']); ?>
        <p>Next Level: <a href="<?php echo esc_url($h5p_url); ?>"><em><?php echo esc_html($next_level['title']); ?></em></a></p>
        <?php else: ?>
        <p>¡Felicidades! Has completado todos los niveles disponibles.</p>
        <?php endif; ?>
    </div>
     
  </div>

  <div class="section">
    <h3>Account Information</h3>
    <div class="account-box">
      <p><strong>User Name:</strong> <?php echo $usr_name ?></p>
      <p><strong>Email: </strong> <?php echo $user_mail ?></p>
      <p><strong>Student Unique Code: </strong> <?php echo $student_code ?></p>
    </div>
  </div>
</div>

</div> <!-- End of the dashboard div --> 
</body>
</html>

