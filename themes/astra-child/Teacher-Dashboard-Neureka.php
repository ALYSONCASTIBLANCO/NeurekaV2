<?php
/*
Template Name: Teacher Dashboard 
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
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8"> 
  <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
  <title>Dashboard - Neureka GS</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
  
 <link rel="stylesheet" href="https://unpkg.com/@phosphor-icons/web/src/regular/style.css">


  <style>
    body {
      font-family: "Open Sans", sans-serif;
      background-color: #f7f7fb;
      margin: 0;
      color: #333;
    }
    
    .welcome{
        display: block;
    }

    .welcome h1 {
      margin-bottom: 30px;
      font-weight: 600;
    }

    .categories {
      width: 70vw;
      margin-left: 0;
    }

    h2 {
      font-size: 20px;
      font-weight: 600;
      margin-bottom: 15px;
    }

    .class-card {
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      margin-bottom: 15px;
      transition: 0.3s;
    }

    .class-header {
      display: flex;
      gap: 10px;
      align-items: center;
      padding: 15px 20px;
      cursor: pointer;
      background-color: #fff;
      transition: background-color 0.3s ease;
      border-radius: 12px;
    }

    #class-header h2 {
        margin: 0;
        text-align: left;
    }

    .class-header span {
      font-size: 16px;
      font-weight: 600;
    }

    .class-content {
      background-color: #fff;
      border-top: 1px solid #eee;
      padding: 40px;
    }

    .class-add-student {
      padding: 30px 300px;
      display: none;
      
    }
    
    .pancracio-main-div {
    
    }
  
    .little-title{
        font-size: 18px;
        font-weight: 1000;
    }

    .little-text{
      margin-left: 10px;
    }

    .add-button {
        text-align: center;
    }

    .add-button button{
        padding: 16px 60px;
        font-size: 18px;
        font-weight: 300;
        color: #331bcc;
        background-color: #C9C5F6;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        transition: background-color 0.2s ease, transform 0.1s ease;
        margin-bottom: 20px;
    }

    .class-add-student form{
    display: flex;
    justify-content: center;
    gap: 10px;
    }

    .class-add-student form input[type=text]{
      width: 20vw;
      height: 30px;
      border-radius: 10px;
      border: none;
      background-color: #E8E8E8;
    }

    .class-add-student form input[type="text"]:focus{
      border: 1px solid gray;
    }

    .class-add-student form input[type=submit]{
        font-size: 18px;
        font-weight: 30;
        color: #331bcc;
        background-color: #C9C5F6;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        transition: background-color 0.2s ease, transform 0.1s ease;
    }

    button:hover {
        background-color: #bdb8ff; /* a bit darker on hover */
        transform: translateY(-2px); /* subtle lift effect */
    }

    button:active {
        transform: translateY(0); /* resets lift when clicked */
    }

    .class-card.active .class-header .caret {
      transform: rotate(180deg);
    }

    .student {
      margin-bottom: 15px;
    }
    /*New section added, for the progress:*/
    .expandable-section {
      padding-left: 2%;
      margin-top: 1%;
      margin-bottom: 2%;
    }


    .progress-bar {
      height: 10px;
      background-color: #e4e4f0;
      border-radius: 10px;
      overflow: hidden;
      margin-top: 5px;
    }

    .progress {
    width: <?php echo $progress_percent; ?>%;;
      height: 100%;
      background-color: #b9a9ff;
      border-radius: 10px;
      transition: width 0.5s ease;
    }

    .actions {
      display: flex;
      gap: 10px;
    }

    .actions i {
      font-size: 18px;
      color: #666;
      cursor: pointer;
      transition: color 0.2s;
    }

    .actions i:hover {
      color: #5f48ff;
    }
    }
  </style>
</head>

<body>
<div class="pancracio-main-div">
  <div class="welcome">
    <h1>Hello, Mr(s). <?php echo $usr_name ?></h1>
  </div>

  <div class="categories">
    <div class="class-card">
      <div class="class-header">
        <i class="ph-bold ph-student"></i>
        <h2 #class-header-title >Your Students</h2>
      </div>

      <div class="class-content">
        <!--Your Students-->
            <p>No students yet.</p>
          </div>
                  <div class="add-button">
                <button onclick="toggleClass()">Add Student
                </button>
        </div>
        <div class="class-add-student" id="AddStudent">
            <p class="little-text" >Application in development ;) 🔨</p> 
            <form action="" method="post">
            <input type="text" name="students" id="students">
            <input type="submit" value="Add" class="button">
            </form>
        </div>
        </div>

 <!-- closes class-content -->
   <!-- closes class-card -->

  <div class="class-card"> <!--Play Yourself Card-->
      <div class="class-header">
        <i class="ph ph-puzzle-piece"></i>
        <h2 #class-header-title >Play Yourself ;)</h2>
      </div>
          <div class="class-content">
            <div class="expandable-section">
                <?php if ($latest_activity): 
                $h5p_latest_url=get_permalink($levels[$completed_level_index]['post_id']) ;
                $h5p_url = get_permalink($next_level['post_id']); ?>
                <div class="add-button">
                    <button onclick="location.href='<?php echo esc_url($h5p_url); ?>'">Play</button>
                </div>
                <p class="little-title">Current Level:</p>
                <p class="little-text"><?php echo $latest_activity->title; ?></p>
                <div class="progress-bar"><div class="progress"></div></div>
                <?php endif; ?>
         
                <?php if ($next_level):
                    if(!$latest_activity) {
                    $h5p_url = get_permalink($next_level['post_id']);
                    ?>
                    <div class="add-button">
                        <button onclick="location.href='<?php echo esc_url($h5p_url); ?>'">Play</button>
                    </div>   
                  <?php  } ?>
               <?php $h5p_url = get_permalink($next_level['post_id']); ?>
                <p class="little-title">Next level:</h3>
                <p class="little-text"><?php echo esc_html($next_level['title']); ?></p>
                <?php else: ?>
                <p>¡Felicidades! Has completado todos los niveles disponibles.</p>
                <?php endif; ?>
            </div>            
          </div>
        </div>
    <div class="class-card"> <!--Account Information-->
    <div class="class-header">
        <i class="ph-bold ph-caret-down caret"></i>
        <h2 #class-header-title >Account Information</h2>
    </div>

    <div class="class-content">
            <p>User Name: <?php echo $usr_name ?></p>
            <p>Email: <?php echo $user_mail ?></p>
          </div>
        </div>
          </div> <!-- closes categories -->
    </div>

    <script>
      function toggleClass() {
      var AddStudent = document.getElementById("AddStudent");
        if (AddStudent.style.display === "none") {
        AddStudent.style.display = "block";
        } else {
      AddStudent.style.display = "none";
      }
      }

    </script>

</body>

</html>
