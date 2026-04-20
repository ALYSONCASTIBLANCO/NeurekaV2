# Neureka GS REST API Documentation

This document describes the custom WordPress REST API endpoints implemented in Neureka GS.

The API is used primarily by the **Tutor Dashboard** to manage student assignments and retrieve student progress data.

---

## 🔐 Authentication & Permissions

All endpoints require:

- WordPress authentication session
- User role: `um_tutor`

Access is restricted via the permission callback:

```php
function is_neureka_teacher() {
   $user = wp_get_current_user();
   if (empty($user->roles)) return false;
   return in_array('um_tutor', $user->roles);
}
```
🌐 Base URL

All endpoints are registered under:
```
/wp-json/school/v1
```
Example:
```
https://genuinecreators.com/wp-json/school/v1/students
```

## 📌 Endpoints Summary
| Method | Endpoint           | Description                                                                 |
| ------ | ------------------ | --------------------------------------------------------------------------- |
| GET    | `/students`        | Returns all students assigned to the current tutor, including progress data |
| POST   | `/add_students`    | Assigns a student to the current tutor using a student unique code          |
| DELETE | `/delete_students` | Removes a student from the current tutor assignment list                    |

## Description

Returns a list of students assigned to the current tutor, based on the assigned_students user_meta array.

It also retrieves student progress by checking the latest H5P activity in:

```
wp_h5p_contents_user_data
wp_h5p_contents
```

Progress is calculated based on:

```
gameDone === true → 100%
exerciseBundles[].exerciseBundle.isCompleted
```

### Request
```
GET /wp-json/school/v1/students
```
Response Example
```json
{
  "students": [
    {
      "user_id": 45,
      "student_code": "a2f91b",
      "display_name": "student01",
      "avatar_url": "https://.../avatar.jpg",
      "current_level": "Level 1 - Introduction",
      "progress_percentage": 75
    }
  ],
  "total": 1
}
```

POST /add_students

### Description

Assigns a student to the current tutor using the student's unique code.

The student code must:

Exist in user_meta.students_code
Belong to a user with role um_student
Not be already assigned to the tutor

If valid, the code is appended into tutor user_meta:

```
assigned_students = [ "code1", "code2", ... ]
```

### Request
```
POST /wp-json/school/v1/add_students
```

### Body Parameters
| Parameter      | Type   | Required | Description                   |
| -------------- | ------ | -------- | ----------------------------- |
| `student-code` | string | Yes      | Student unique code to remove |

### Example Request (JSON)
```json
{
  "assigned_students": "a2f91b"
}
```
Success Response
``` json
{
  "success": true,
  "student_code": "a2f91b"
}
```
Error Response
```json
{
  "success": false,
  "message": "Invalid or already assigned student code.",
  "code": "a2f91b",
  "Student is found": []
}
```
DELETE /delete_students

### Description

Removes a student assignment from the current tutor.

The tutor must already have the student code stored in:

assigned_students
### Request
DELETE /wp-json/school/v1/delete_students
| Parameter      | Type   | Required | Description                   |
| -------------- | ------ | -------- | ----------------------------- |
| `student-code` | string | Yes      | Student unique code to remove |

### Example Request
```json
{
  "student-code": "a2f91b"
}
```
### Success Response
```json
{
  "success": true,
  "success_message": "The student was deleted"
}
```
### Error Response
```json
{
  "success": false,
  "error_message": "You don't students to delete"
}
```
## 📌 Notes
All endpoints assume that the tutor is authenticated in WordPress.
Student progress depends entirely on the H5P state JSON stored in the database.
The API is designed for internal dashboard usage, not for public exposure.