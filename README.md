# Neureka GS Developer Guide

Neureka GS is an educational platform built on top of **WordPress**, designed to teach **Computational Thinking** to neurodivergent learners.  
The platform provides role-based experiences for **students, tutors, and parents**, including personalized dashboards and a custom REST API for student management.

🌐 Live Deployment: https://genuinecreators.com/

---

## 📌 Project Overview

Neureka GS is deployed in a WordPress environment and implements a custom workflow that includes:

- Role-based access and redirections
- Student unique code generation system
- Tutor dashboard with student assignment management
- REST API for student CRUD operations
- Custom styling and UI improvements for H5P-based learning content

This repository contains the **Astra Child Theme**, backup snippets, and REST API logic used to support the platform's internal features.

---

## 🎯 Target Audience

This developer guide is intended for:

- Internal development team members
- Competition judges and technical reviewers
- Contributors onboarding into the Neureka GS codebase

---

## ⚙️ Tech Stack

### Core Environment
- **WordPress:** 6.8.5
- **PHP:** 8.2.30 (Supports 64bit values)
- **Database (Client):** mysqlnd 8.2.30  
- **Database (Server):** 11.8.6-MariaDB-log

### Frontend
- HTML5
- CSS3
- JavaScript (used for frontend requests and form interactions)

### WordPress Theme
- Astra (Parent Theme)
- Astra Child Theme (custom implementation in this repository)

### Plugins Used
- Elementor
- Astra
- Ultimate Member
- Code Snippets
- H5P
- GamiPress

---

## 🚀 Main Features

### Platform Features
- Homepage and navigation structure
- Registration and login flow by role
- Student and tutor dashboards (custom UI per role)
- Tutor dashboard student management powered by REST API
- Role-based page restrictions and automatic redirections
- Student unique code generation system
- Parent–student relationship restrictions

### Developer Features
- Custom REST API implementation inside the theme
- Custom CSS injected into H5P iframe-based content
- Snippet backup for:
  - Header button behavior
  - H5P + GamiPress interaction logic

---

## 👥 User Roles

The system is built around Ultimate Member custom roles:

- `um_student` (Student)
- `um_parent` (Parent)
- `um_tutor` (Tutor / Teacher)

---

## 📄 Required Pages

The platform relies on a predefined WordPress page structure.  
Make sure these pages exist in the WordPress installation (created manually or via templates):

- Home (Front Page)
- Login
- Register
- Account
- Account Management
- Members
- Logout
- Password Reset
- Dashboard
- Teacher Dashboard
- Configuration
- Notifications
- Parent Register
- Role Options
- Student Register
- Teacher Register

Additional dashboard templates (Elementor-based) include:
- Aspirante
- Comandante
- Explorador
- Especialista
- Maestro

---

## 🧩 Repository Structure
```
NeurekaV2/
├── wp-content/
│ ├── elementor-templates/ # Elementor templates export (.json)
│ ├── mu-plugins/ # Must-use plugins (if applicable)
│ ├── plugins/ # Custom plugin folder (if applicable)
│ ├── snippets-backup/ # Backup of Code Snippets
│ └── themes/
│ └── astra-child/ # Main theme implementation
├── .gitignore
├── index.php
└── README.md
```

Inside the Astra Child Theme:

- `functions.php` contains:
  - Student unique code generation logic
  - Role-based restrictions and redirects
  - H5P iframe style injection
  - Theme hooks and required includes

- `/Includes/rest-api-neurekag.php` contains:
  - Custom REST API endpoints for student management

---

## 🧠 Student Unique Code System

When a student registers (`um_student`), the system automatically generates a unique code:

- Stored in user meta: `students_code`
- Generated using cryptographically secure random bytes
- Output is a **6-character hex-based code**
- Validated to ensure uniqueness (up to 10 attempts)

This code is used by tutors to assign students to their dashboard.

---

## 🔁 Role-Based Redirects & Access Control

The theme enforces page-level access control through `template_redirect`:

- Guests trying to access `/dashboard/` are redirected to `/login/`
- Logged-in users are redirected away from login/register pages
- Users are redirected to the correct dashboard based on role:
  - Students/Parents → `/dashboard/`
  - Tutors → `/teacher-dashboard/`
  - Administrators → `/wp-admin/`

This ensures users cannot access unauthorized dashboards.

---

## 🔌 REST API Documentation

REST API endpoints are documented separately:

📄 See: **[API_DOC.md](API_DOC.md)**

---

## 🛠️ Local Setup Guide

### 1. Install WordPress Locally
Use any local WordPress environment such as:

- LocalWP
- XAMPP

Create a clean WordPress installation.

---

### 2. Install Required Plugins
Install and activate the following plugins:

- Astra Theme
- Elementor
- Ultimate Member
- Code Snippets
- H5P
- GamiPress

---

### 3. Install the Astra Child Theme
Clone this repository and copy the theme folder into:
```
/wp-content/themes/
```

Then activate the theme in the WordPress admin panel:

**Appearance → Themes → Astra Child**

---

### 4. Import Snippets
Install **Code Snippets**, then manually paste/import the snippets found in:
```
wp-content/snippets-backup/
```


Activate the snippets inside the Code Snippets plugin.

---

### 5. Create Required Pages
Create the required pages listed above and ensure their slugs match the expected routes (dashboards, login, register, etc.).

---

## ⚠️ Notes for Developers

- The REST API is restricted to tutors (`um_tutor`) using a custom permission callback.
- Student progress data is extracted directly from H5P database tables.
- H5P UI styles are injected via iframe manipulation using a `wp_footer` script.

---

## 📌 Disclaimer

This repository is currently in a **development / MVP stage** and is intended for internal use and technical evaluation purposes.

All rights reserved.