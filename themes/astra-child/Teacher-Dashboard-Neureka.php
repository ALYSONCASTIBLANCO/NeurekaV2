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
    1 => ['title' => 'Aspirante', 'h5p_id' => 1, 'achievement_id' => 74, 'post_id' => 56],
    2 => ['title' => 'Explorador', 'h5p_id' => 2, 'achievement_id' => 77, 'post_id' => 148],
    3 => ['title' => 'Especialista', 'h5p_id' => 3, 'achievement_id' => 79, 'post_id' => 157],
    4 => ['title' => 'Comandante', 'h5p_id' => 4, 'achievement_id' => 251, 'post_id' => 331],
    5 => ['title' => 'Maestro', 'h5p_id' => 5, 'achievement_id' => 31, 'post_id' => 336],
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
 
                // Determine next level (FIXED)
                if ($completed_level_index !== null) {
                    $next_index = $completed_level_index + 1;

                    if (isset($levels[$next_index])) {
                    $next_level = $levels[$next_index];
                        } else {
                             $next_level = null; // no more levels
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

<html lang="es">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
  
 <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/regular/style.css"/>
 <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/fill/style.css"/>


  <style>
    #content.site-content {
      /* background-color: #f0eeff; */
      background: linear-gradient(to bottom, #eff0ff, #f7f7fb);
    }
    
    .body {
      font-family: "Open Sans", sans-serif;
      background-color: #f7f7fb;
      margin: 0;
      color: #333;
    }

    .check-button {
      display: none;
    }
    
    .delete-button {
      display: none;
    }
    
    .welcome{
        display: block;
        width: 70vw;
        background-color: #b3dbeb;
          /* pastel-green:b3dbeb lighter-blue:afd3f9 */
        padding: 20px;
        margin-top: 30px;
        margin-bottom: 30px;
        border-radius: 5px;
    }

    .welcome h1 {
      margin-bottom: 5px;
      font-weight: 600;
      color: #1c256a;
    }

    .div-description-icon{
      display: flex;
      gap: 8px;
      align-items: center;
      margin-bottom: 15px;
      color: #1c256a;
    }

    .div-description-icon i{
      font-size: 20px;
    }

    .categories {
      width: 70vw;
      margin-left: 0;
    }

    h2 {
      font-size: 20px;
      font-weight: 600;
      color: #1c256a;
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
      padding: 15px 15px;
      cursor: pointer;
      background-color: #fff;
      transition: background-color 0.3s ease;
      border-radius: 12px;
      margin-left: 20px; 
    }

    .class-header h2 {
      margin: 0px;
    }

    .class-header i{
      font-size: 25px;
      color: #1c256a;
    }

    .class-header-your-students{
      display: flex;
      justify-content: space-between;
      gap: 10px;
      align-items: center;
      padding: 10px 10px;
      cursor: pointer;
      background-color: #fff;
      transition: background-color 0.3s ease;
      border-radius: 12px;
    }

    .title-with-icon{
      display: flex; 
      gap: 10px;
      margin-left: 20px; 
    }

    .title-with-icon i{
    color: #1c256a; 
    }

    .class-header-your-students div i{
       font-size: 25px;
    }

    .edit-and-check {
      margin: 20px; 
    }

    .edit-button {
      background-color: #a9a0ff;
    }

    .edit-button:focus{
      background-color: #d4cfff;
    }

    .check-button {
      background-color: #958afa;
    }

    .check-button:focus{
      background-color: #bbb4ff;
    }

    .delete-button {
      background-color: #978bff;
      padding: 8px 12px;
      font-size: 14px;
    }

    .delete-button:focus {
      background-color: #7869ff;
    }

    .div-text-over-button {
      display: flex;
      gap: 10px;
      align-items: center;
      margin-bottom: 10px;
      justify-content: center;
    }

    .div-text-over-button p {
    margin: 0;
    color: #1c256a;
  }

    .div-text-over-button i{
      font-size: 25px;
      color: #1c256a;
    }

    .text-over-button {
      font-weight: 500;
      font-size: 17px;  
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
      padding: 20px 40px 30px 40px;
      border-radius: 0px 0px 12px 12px;
    }

    .class-add-student {
      padding: 30px 300px;
      display: none;
      
    }

    .student-card{
      display: flex;
      gap: 80px;
      background-color: #f5f4f4;
      padding: 6px 15px; 
    }

    .student-card-left {
      align-items: center;
      width: 35%;
      display: flex;
      gap: 10px; 
    }

    .student-card-left p{
      margin-bottom: 0px;
    }

    .student-card-left div i{
      font-size: 20px; 
    }

    .student-card-right {
      width: 65%;
      display: flex;
    }

    .student-card-right p{
      width: 20%;
    }

    .key-code {
      display: flex;
      vertical-align: center;
    }
    
    .student-unique-code-card{
      font-size: 13px;
      margin-left: 5px;
    }

    .pancracio-main-div {
    }
  
    .little-title{
        font-size: 18px;
        font-weight: 1000;
        margin-bottom: 0;
    }

    .little-text{
      margin-left: 5px;
      margin-bottom: 5px;
    }

    .add-button {
        text-align: center;
    }

    .add-button button{
        padding: 16px 60px;
        font-size: 18px;
        font-weight: 600;
        color: #4733c7;
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


    .student {
      margin-bottom: 15px;
    }
    /*New section added, for the progress:*/
    .expandable-section {
      padding-left: 2%;
      margin-top: 1%;
      margin-bottom: 2%;
    }

    .div-description-left-icon {
      display: flex;
      align-items: center;
      vertical-align: center;
      color: #1c256a;
      gap: 5px;
    }

    .div-description-left-icon p{
      margin: 0;
    }

    .div-description-left-icon i{
      font-size: 18px;
    }

    .progress-bar {
      height: 22px;
      width: 40%;
      background-color: #e4e4f0;
      border-radius: 10px;
      overflow: hidden;
      margin-top: 10px;
      margin-left: 10px; 
      margin-bottom: 20px;
    }

    .progress {
      width: <?php echo $progress_percent; ?>%;
      height: 100%;
      background-color: #b3acff;
      border-radius: 10px;
      transition: width 0.5s ease;
    }

    .progress p{
      font-size: 14px;
      margin-left: 10px;
      vertical-align: center;
    }

    .progress-bar-student {
      width: 50%;
      height: 22px;
      background-color: #fcfbff;
      border-radius: 10px;
      overflow: hidden;
      margin-top: 5px;
      margin-left: 10px;
    }

    .progress-student {
      width: 0;
      height: 100%;
      background-color: #c9c5f6;
      border-radius: 10px;
      transition: width 0.5s ease;
    }

    .progress-student p{
      font-size: 14px;
      margin-left: 10px;
      vertical-align: center;
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

    .account-information {
      gap:10px;
    }

    @media (max-width: 921px) {
      .welcome, .categories{
        width: 90vw;}
      .pancracio-main-div{
        display: flex;
        flex-direction: column;
        justify-content: center}

      .edit-button{width: auto; height: auto;}
      .edit-button i{font-size: 20px !important;}
      .student-card-right {
        flex-direction: column;
        
      }
      .student-card-right p{
        margin-bottom: 0;
        width: 93%;
      }
      .progress-bar-student{
        margin-left: 0;
        width: 93%;
      }
      .progress-bar {
        width: 90%;
      }
      
      .class-add-student{
        padding: 30px 0px;
      }
    }
  </style>


<div class="pancracio-main-div">
  <div class="welcome">
    <h1>¡Hola, Profesor(a). <?php echo $usr_name ?>!</h1>
    <div class="div-description-icon">
     <p class="little-text"> Aprender es mejor si lo hacemos de la mano, todos aprendendemos de todos</p>
      <i class="ph ph-handshake"></i>
    </div>
  </div>

  <div class="categories">
    <div class="class-card">
      <div class="class-header-your-students">
        <div class="title-with-icon">
          <i class="ph ph-student"></i>
          <h2 #class-header-title >Tus Estudiantes</h2>
        </div>
        <div class="edit-and-check">
          <!-- <button class="check-button" type="button" onclick="saveChanges()"> 
          <i class="ph ph-check"></i> 
          </button> -->
          <button class="edit-button" type="button" onclick="editToggleClass()">
            <i class="ph ph-pencil-simple"></i>
          </button>
        </div>
      </div>

      <div class="class-content">
        <!--Your Students-->
            <div id="studentsList"> </div>
            <template id="studentsList-template">
              <article class="student-card">
                  <div class="student-card-left">
                    <div class="trash_can">
                      <button class="delete-button" type="button">
                        <i class="ph ph-trash"></i>
                      </button>
                    </div>
                    <div>
                      <p></p>
                      <div class="key-code"><i class="ph ph-key"></i><p class="student-unique-code-card"></p></div>
                    </div>
                  </div>
                    
                  <div class="student-card-right">
                    <p></p>
                    <div class="progress-bar-student"><div class="progress-student"> <p></p> </div></div>
                  </div>
                    
                </div>
              </article>
          </template>
          </div>
                  <div class="add-button">
                <button onclick="addToggleClass()">Agregar</button>
        </div>
        <div class="class-add-student" id="AddStudent"> 
            <form id="add_student_form" method="post">
            <input type="text" name="students" id="assigned_students">
            <input id="AddStudentButton" type="submit" value="Add" class="button">
            </form>
        </div>
        </div>

 <!-- closes class-content -->
   <!-- closes class-card -->

  <div class="class-card"> <!--Play Yourself Card-->
      <div class="class-header">
        <i class="ph ph-puzzle-piece"></i>
        <h2 #class-header-title ><strong>Juego</strong></h2> 
      </div>
          <div class="class-content">
            <div class="expandable-section">
                
                
                
                <?php if ($latest_activity): 
                    $h5p_latest_url=get_permalink($levels[$completed_level_index]['post_id']) ;
                    $h5p_url = get_permalink($next_level['post_id']); ?>
                <div class="div-text-over-button">
                  <p class="text-over-button">Juega tu mismo!</p>
                  <i class="ph ph-lego"></i>
                </div>
                <div class="add-button">
                    <button onclick="location.href='<?php echo esc_url($h5p_url); ?>'">Jugar</button>
                </div>
                <div class="div-description-left-icon">
                  <i class="ph ph-map-pin"></i>
                  <p class="little-text"><strong>Nivel Actual: </strong><?php echo $latest_activity->title; ?></p>
                </div>
                <div class="progress-bar">
                  <div class="progress">
                    <p><?php echo $progress_percent; ?>%</p>
                  </div>
                </div>
                <?php endif; ?>
         
         
         
                <?php if ($next_level):
                    if(!$latest_activity) {
                    $h5p_url = get_permalink($next_level['post_id']);
                    ?>
                    <div class="div-text-over-button">
                        <p class="text-over-button">Juega tu mismo!</p>
                        <i class="ph ph-lego"></i>
                    </div>
                    <div class="add-button">
                        <button onclick="location.href='<?php echo esc_url($h5p_url); ?>'">Jugar</button>
                    </div>
                    
                    
                    
                    
                  <?php  } ?>
               <?php $h5p_url = get_permalink($next_level['post_id']); ?>
                <div class="div-description-left-icon">
                  <i class="ph ph-arrow-bend-down-right"></i>
                  <p class="little-text"><strong>Siguiente Nivel: </strong><?php echo esc_html($next_level['title']); ?></p>
                </div>
                <?php else: ?>
                <p>¡Felicidades! Has completado todos los niveles disponibles.</p>
                <?php endif; ?>
            </div>            
          </div>
        </div>
    <!-- <div class="class-card"> Account Information
    <div class="class-header">
        <i class="ph ph-user-circle"></i>
        <h2 #class-header-title >Información de Cuenta</h2>
    </div>

    <div class="class-content">
      <div class="account-information">
      <div class="div-description-left-icon">
        <i class="ph ph-user"></i>
        <p>Nombre de usuario: <?php echo $usr_name ?></p>
      </div>
        
          <div class="div-description-left-icon">
            <i class="ph ph-envelope-simple"></i>
            <p>Correo: <?php echo $user_mail ?></p>
          </div>
        </div>
      </div>
        </div>
          </div> closes categories -->
    </div> 

    <script>
        const neurekaAPI = {
        root: "<?php echo esc_url_raw(rest_url()); ?>",
        nonce: "<?php echo wp_create_nonce('wp_rest'); ?>"
        };
 

      function addToggleClass() {
      var AddStudent = document.getElementById("AddStudent");
        if (AddStudent.style.display === "none") {
        AddStudent.style.display = "block";
        } else {
      AddStudent.style.display = "none";
      }
      }

      function editToggleClass() {
        // var CheckChanges=document.getElementsByClassName("check-button")[0];
        var DeleteStudent = document.querySelectorAll("#studentsList .delete-button");

                DeleteStudent.forEach(delete_student=>{
                  if(delete_student.style.display === "none")
                    delete_student.style.display = "block";
                 else {
                  delete_student.style.display ="none";
                }});
}

                /*  if (CheckChanges.style.display === "none") {
                  CheckChanges.style.display = "inline-block";
                  DeleteStudent.forEach(delete_student=>{
                    delete_student.style.display = "block";
                  });
                  } else {
                    CheckChanges.style.display = "none";
                    DeleteStudent.forEach(delete_student=>{
                      delete_student.style.display = "none";
                    });
                  } */

      fetch(neurekaAPI.root + 'school/v1/students', {
          method: 'GET',
          credentials: 'same-origin',
          headers: {
              'X-WP-Nonce': neurekaAPI.nonce
          }
        })
      .then(res => res.json())
      .then(data => {
      console.log(data);
    const container = document.getElementById("studentsList");
    $template=document.getElementById('studentsList-template').content;
    $fragment=document.createDocumentFragment();

    if(data.students.length === 0){
        container.innerHTML = "<p>No students yet.</p>";
        return;
    }

    let html = "";

    data.students.forEach(student => {
    //    html += `<p>Student ID: ${student.user_id}</p>`;
 
    $template.querySelector(".student-card-left p").textContent =
          student.display_name;
 
      $template.querySelector(".student-unique-code-card").textContent =
          student.student_code;
 
      $template.querySelector(".student-card-right p").textContent =
          (student.current_level ? student.current_level : "The student didn't start any level yet 🍃");

      $template.querySelector(".progress-student").style.width =
          student.progress_percentage + "%";
      
      $template.querySelector(".progress-student p").textContent =
          student.progress_percentage + "%";

      $template.querySelector(".student-card").dataset.studentCode = student.student_code;

      if (data.students.indexOf(student)%2==0) {
        $template.querySelector(".student-card").style.backgroundColor='#d3d3fc';
      } else {
        $template.querySelector(".student-card").style.backgroundColor='#e6ebff';
      }
 
      let $clone=document.importNode($template, true);
      $fragment.appendChild($clone);
    });
      document.getElementById('studentsList').appendChild($fragment);

});

      //Now, it's time to add the student doing the fetch to the database:
      //First, we detect the event when we click in the button:
      document.getElementById("add_student_form").addEventListener('submit', (e)=>{
        
        e.preventDefault();
        const codeValue=e.target.assigned_students.value.trim();
        console.log(codeValue);
        //Then, we do the request with the token in the headers, with credentials in the same frontend origin.
        fetch(neurekaAPI.root + 'school/v1/add_students', {
          method: 'POST',
          credentials: 'same-origin',
          //The header will change a little bit here, because the content type that we are transfering has the format application/json.
          headers: {
              'Content-Type': 'application/json',
              'X-WP-Nonce': neurekaAPI.nonce
          },
          //And, with a POST method, we need to pass something: for that reason, we have here the body parameter, passing in JSON format
          //The code value with the parameter assigned_students that you already have in the REST API.
          body: JSON.stringify({
          assigned_students: codeValue
          })
        })
        .then(res => res.json())
        .then(data=>{
            console.log(data.success);
            let successful=data.success;
 
            if(!successful){
                alert("El código es inválido o ya ha sido registrado");}
            
            
            else{
                alert("El estudiante ha sido agregado exitosamente");
                location.reload(true);
            }
                
            })
        .catch(e=>console.error(e))
      });

      document.addEventListener("click", function(e){
        if (e.target.closest(".delete-button")){
        
        const button = e.target.closest(".delete-button");
        const card = button.closest(".student-card");
        const code = card.dataset.studentCode;

        console.log("Delete: ", code);

        fetch(neurekaAPI.root + 'school/v1/delete_students?student-code=' + code, {
          method: 'DELETE',
          credentials: 'same-origin',
          headers: {
              'X-WP-Nonce': neurekaAPI.nonce
              }
          
        })
        .then(res => res.json()) 
        .then(data => {console.log(data);location.reload(true);})
        .catch(e=>console.error(e))
      }});

      function saveChanges() {
        location.reload(true);
      }

    </script>

<?php get_footer(); 
