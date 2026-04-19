# 🎓 EduSphere – Academic Management System

EduSphere is a web-based academic management system designed to streamline and automate college academic processes such as attendance tracking, marks management, performance analytics, and student-faculty communication.

This project is developed as part of a B.Tech CSE final-year academic project.

---

## 🚀 Features

### 👨‍🎓 Student Module
- View subject-wise attendance with percentage
- View internal marks (CA1, CA2, CA3)
- Visual analytics using charts (Chart.js)
- Smart attendance prediction system
- Pre-exam checklist (low attendance & pending work)
- Raise and track queries with faculty

### 👨‍🏫 Faculty Module
- Mark and manage attendance
- Enter and update marks
- View student performance analytics
- Access subject-wise attendance overview
- Respond to student queries
- View class performance dashboard

### 🛠️ Admin Module
- Manage users (students & faculty)
- Manage subjects
- View system statistics

---

## 🧠 Key Functionalities

- Role-based login system (Student / Faculty / Admin)
- Attendance percentage calculation using SQL aggregation
- Marks analytics with dynamic averaging
- Smart recommendation system for attendance improvement
- Modal-based detailed attendance view
- Interactive dashboard charts using Chart.js

---

## 🏗️ Tech Stack

**Frontend:**
- HTML5
- CSS3
- JavaScript
- Chart.js

**Backend:**
- PHP (Core PHP)

**Database:**
- MySQL (XAMPP / phpMyAdmin)

---

## 📂 Project Structure

```

EduSphere/
├── assets/
├── includes/
├── dashboard.php
├── login.php
├── logout.php
├── edusphere.sql
├── get_student_full_attendance.php
└── other PHP files

```

---

## ⚙️ Installation & Setup

### Step 1: Clone Repository
```

git clone [https://github.com/spandanse/EduSphere.git](https://github.com/spandanse/EduSphere.git)

```

### Step 2: Move to XAMPP folder
Place project inside:
```

C:\xampp\htdocs\

```

### Step 3: Create Database
- Open phpMyAdmin
- Create database:
```

edusphere_db

````
- Import `edusphere.sql`

### Step 4: Configure Database
Update `includes/db.php` if required:
```php
$conn = new mysqli("localhost", "root", "", "edusphere_db");
````

### Step 5: Run Project

Open browser:

```
http://localhost/EduSphere/
```

---
