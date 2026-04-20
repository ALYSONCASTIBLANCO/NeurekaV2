<?php

// Wordpress REST API that asks for the Student Unique Code

function is_neureka_teacher() {
   $user = wp_get_current_user();
   if (empty($user->roles)) return false;
   return in_array('um_tutor', $user->roles);
}



add_action('rest_api_init', function () {

    register_rest_route('school/v1', '/students', [
        'methods' => 'GET',
        'callback' => 'get_student_codes',
        'permission_callback' => 'is_neureka_teacher',
        
    ]);
    register_rest_route('school/v1', '/add_students', [
        'methods' => 'POST',
        'callback' => 'assign_student_codes',
        'permission_callback' => 'is_neureka_teacher',
    ]);
    register_rest_route('school/v1', '/delete_students', [
        'methods' => 'DELETE',
        'callback' => 'delete_students',
        'permission_callback' => 'is_neureka_teacher',
    ]);

});

function get_student_codes() {
    global $wpdb;

    $TeacherID=get_current_user_id();
    $AssignedStudentsArray=get_user_meta($TeacherID, 'assigned_students', true);
    if(!$AssignedStudentsArray) $AssignedStudentsArray = [];
        $students = [];

    foreach ($AssignedStudentsArray as $code) {
            $users_id = get_users([

            'meta_key'   => 'students_code',
            'meta_value' => $code,
            'number'     => 1,
            'fields'     => 'ID'
    ]);
        
        // get_users always returns an array — grab the first item
        if (empty($users_id)) continue;
        $user_id = $users_id[0]; //now it's just the integer 42
        $latest_activity = $wpdb->get_row(
            $wpdb->prepare(
        "SELECT u.*, c.title
         FROM {$wpdb->prefix}h5p_contents_user_data u
         JOIN {$wpdb->prefix}h5p_contents c ON u.content_id = c.id
         WHERE u.user_id = %d
         ORDER BY u.updated_at DESC
         LIMIT 1",
        $user_id
    )
);

        $progress_percent = 0;
        $book_info = null;
        $json=[];
    if($latest_activity){
    $book_info = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT data FROM {$wpdb->prefix}h5p_contents_user_data
             WHERE user_id = %d AND content_id = %d AND data_id = 'state'",
            $user_id, $latest_activity->content_id
        )
    );
    
    
    
    if($book_info){
        $json = json_decode($book_info->data, true);
        if (isset($json["content"]["gameDone"]) && $json["content"]["gameDone"] === true) {
              //If is the case, the percentage will be 100%
                $progress_percent=100;
        }
        
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
            }
    }
    
    else{
        $json=[];
    }
    }
    else{
    $progress_percent = 0;
    }


        $student_info=get_userdata($user_id);
     
        $students[] = [
            'user_id'      => $user_id,
            'student_code' => $code,
            'display_name' => $student_info -> user_login,    // user_login is the name of the login name of users in the database, on wp_users.
            'avatar_url'   => get_avatar_url($user_id),
            'current_level' => $latest_activity ? $latest_activity->title : null,      // $latest_activity->title  
            'progress_percentage' => $progress_percent
        ];
    
    }
    return [
        'students' => $students,
        'total'    => count($students),
    ];

}



function assign_student_codes(WP_REST_Request $request) {
    $teacher_id = get_current_user_id();
    $student_codes = get_user_meta($teacher_id, 'assigned_students', true);
    $new_code = sanitize_text_field($request->get_param('assigned_students'));

    if(!$student_codes) $student_codes = [];
    $new_code = sanitize_text_field($request->get_param('assigned_students'));
          $student = get_users([
            'meta_key'   => 'students_code',
            'meta_value' => $new_code,
            'role'       => 'um_student',
            'number'     => 1,
            'fields'     => 'ID'
        ]); 

    if(!empty($student) && !in_array($new_code, $student_codes)){
        $student_codes[] = $new_code;
        update_user_meta($teacher_id, 'assigned_students', $student_codes);
        return ['success' => true, 'student_code' => $new_code];
        }
    
    return ['success' => false, 'message' => 'Invalid or already assigned student code.', 'code'=>$new_code, 'Student is found'=>$student];

}

function delete_students(WP_REST_Request $request) {
    $teacher_id = get_current_user_id();
    $student_codes = get_user_meta($teacher_id, 'assigned_students', true);
    $delete_code = sanitize_text_field($request->get_param('student-code'));

    if(!$student_codes) $student_codes = [];

    if(!in_array($delete_code, $student_codes)){
        
        return ['success' => false, 'error_message' => "You don't students to delete"]; 
        }

    $new_array = array_filter($student_codes, function($c) use ($delete_code){
        return $c !== $delete_code;
    });

    $student_codes = $new_array;

    update_user_meta($teacher_id, 'assigned_students', $student_codes);

     return ['success' => true, 'success_message' => "The student was deleted"]; 

}
