<?php
 
//Route to show students
register_rest_route('neurekags/v1', '/students', [
    'methods' => 'GET',
    'callback' => 'get_students_data',
    'permission_callback' => function() {
        return current_user_can('um_tutor'); // solo docentes
    },
]);
 
function get_students_data($data) {
    $teacher_id = get_current_user_id();
    $student_codes = get_user_meta($teacher_id, 'students_list', true);
    if(!$student_codes) $student_codes = [];
 
    $students_data = [];
    foreach($student_codes as $code){
        $student_id = get_user_id_by_code($code); // tu función que mapea código a ID
        if(!$student_id) continue;
 
        $students_data[] = [
            'name' => get_userdata($student_id)->display_name,
            'nivel' => get_user_meta($student_id,'nivel_actual',true),
            'progreso' => get_h5p_progress($student_id),
            'gamipress_points' => get_gamipress_points($student_id),
            'student_code' => $code
        ];
    }
    return $students_data;
}
 
//Route to add new student
register_rest_route('neurekags/v1', '/students', [
    'methods' => 'POST',
    'callback' => 'add_student_code',
    'permission_callback' => function() {
        return current_user_can('um_tutor');
    },
]);
 
function add_student_code($data) {
    $teacher_id = get_current_user_id();
    $student_codes = get_user_meta($teacher_id, 'students_list', true);
    if(!$student_codes) $student_codes = [];
 
    $new_code = sanitize_text_field($data['student_code']);
    if(!in_array($new_code, $student_codes)){
        $student_codes[] = $new_code;
        update_user_meta($teacher_id, 'students_list', $student_codes);
    }
 
    return ['success' => true, 'student_code' => $new_code];
}
 
//Route to delete student
register_rest_route('neurekags/v1', '/students/(?P<student_code>[a-zA-Z0-9_-]+)', [
    'methods' => 'DELETE',
    'callback' => 'delete_student_code',
    'permission_callback' => function() {
        return current_user_can('um_tutor');
    },
]);
 
function delete_student_code($data) {
    $teacher_id = get_current_user_id();
    $student_codes = get_user_meta($teacher_id, 'students_list', true);
    if(!$student_codes) $student_codes = [];
 
    $student_codes = array_filter($student_codes, function($code) use ($data){
        return $code != $data['student_code'];
    });
 
    update_user_meta($teacher_id, 'students_list', $student_codes);
 
    return ['success' => true];
}