# 🎓 EduSphere – Academic Management System

EduSphere is a **web-based academic management system** designed to streamline and automate key college processes such as student attendance tracking, marks management, performance analytics, and faculty-student communication.

This project is developed as part of a B.Tech CSE final-year academic initiative.

---

## 🚀 Features

### 👨‍🎓 Student Module
- View attendance subject-wise with percentage analysis
- Track internal assessment marks (CA1, CA2, CA3)
- Visual analytics using charts (Chart.js)
- Smart attendance prediction system
- Pre-exam checklist (low attendance + incomplete submissions)
- Raise and track queries with faculty

### 👨‍🏫 Faculty Module
- Mark and manage student attendance
- Enter and update internal marks
- View subject-wise student performance
- Access student attendance analytics
- Respond to student queries
- View overall class performance dashboard

### 🛠️ Admin Module
- Manage users (students & faculty)
- Manage subjects
- View system statistics (users, subjects)

---

## 🧠 Key Functionalities

- Role-based authentication (Student / Faculty / Admin)
- Attendance percentage calculation using SQL aggregation
- Marks analysis with dynamic averaging
- Smart recommendation engine for attendance improvement
- Modal-based detailed attendance view
- Real-time dashboard analytics

---

## 🏗️ Tech Stack

**Frontend:**
- HTML5
- CSS3
- JavaScript
- Chart.js

**Backend:**
- PHP (Core PHP)
- MySQL

**Database:**
- MySQL (XAMPP / phpMyAdmin)

---

## 📂 Project Structure


EduSphere/
│
├── assets/ # CSS, JS, images
├── includes/ # Core PHP functions & DB connection
├── dashboard.php # Main dashboard (student/faculty/admin)
├── login.php # Login system
├── logout.php
├── edusphere.sql # Database file
├── get_student_full_attendance.php
└── other PHP modules


---

## ⚙️ Installation & Setup

### 🔹 Step 1: Clone Repository
```bash
git clone https://github.com/spandanse/EduSphere.git
🔹 Step 2: Move to XAMPP directory

Place the project inside:

C:\xampp\htdocs\
🔹 Step 3: Database Setup
Open phpMyAdmin

Create database:

edusphere_db

Import:

edusphere.sql
🔹 Step 4: Configure DB Connection

Update includes/db.php if needed:

$conn = new mysqli("localhost", "root", "", "edusphere_db");
🔹 Step 5: Run Project

Open in browser:

http://localhost/EduSphere/
