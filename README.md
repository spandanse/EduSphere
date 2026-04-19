# 🎓 EduSphere

EduSphere is a **Smart Academic Management System** designed for colleges to efficiently manage **students, faculty, attendance, marks, and academic analytics** in one unified platform.

It provides real-time insights like attendance tracking, CA performance, pre-exam risk detection, and faculty-student communication.

---

## 🚀 Features

### 👨‍🎓 Student Module
- View attendance subject-wise with percentage analytics
- Continuous Assessment (CA) performance tracking (CA1, CA2, CA3)
- Smart attendance prediction (how many classes needed to maintain 75%)
- Pre-exam checklist (low attendance + low marks detection)
- Submit queries to faculty
- Interactive charts (Chart.js visualization)

### 👨‍🏫 Faculty Module
- Manage attendance records
- Manage internal marks (CA1, CA2, CA3)
- View subject-wise student performance
- View full student attendance records
- Respond to student queries
- Smart student performance overview dashboard

### 🔐 Admin Module
- Manage users (students & faculty)
- Manage subjects
- System overview dashboard

---

## 🧠 Smart Features
- 📊 Attendance analytics with visual charts
- 📉 Automated low attendance detection
- 🎯 Exam readiness checklist (risk-based analysis)
- 📈 Performance visualization using Chart.js
- ⚡ Real-time dashboard updates

---

## 🛠️ Tech Stack

### Frontend:
- HTML5
- CSS3
- JavaScript
- Chart.js

### Backend:
- PHP (Core PHP)
- MySQL

### Server:
- XAMPP / Apache

---

## 🗄️ Database Design

Main tables:
- users
- subjects
- attendance
- marks
- queries
- submissions (planned/optional)

---

## 📂 Project Structure

```

EduSphere/
│
├── assets/              # CSS, JS, images
├── includes/            # DB connection & functions
├── dashboard.php        # Main dashboard
├── login.php
├── logout.php
├── attendance.php
├── marks.php
├── preexam_checklist.php
├── student_queries.php
├── faculty_queries.php
├── get_student_full_attendance.php
└── edusphere.sql

```

---

## 📊 Key Functionalities

### 📌 Attendance Tracking
- Subject-wise attendance calculation
- Percentage computation using SQL aggregation

### 📌 Marks System
- Internal assessment tracking (CA1, CA2, CA3)
- Average performance calculation

### 📌 Pre-Exam Intelligence
- Flags students with:
  - Low attendance (<75%)
  - Low CA performance
- Helps students identify risk areas early

### 📌 Smart Dashboard
- Graphical visualization of academic data
- Role-based dashboards (Student / Faculty / Admin)

---
