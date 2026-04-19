# 🎓 EduSphere

EduSphere is a **Smart Academic Management System** built for colleges to manage students, faculty, attendance, marks, and academic analytics in a centralized platform.

It focuses on **real-time performance tracking, smart attendance analysis, and exam readiness evaluation**.

---

## 🚀 Features

### 👨‍🎓 Student Module
- Subject-wise attendance tracking with percentage analytics
- Continuous Assessment (CA1, CA2, CA3) performance visualization
- Smart attendance prediction (classes needed to maintain 75%)
- Pre-exam checklist (risk detection based on attendance & marks)
- Interactive dashboards with charts (Chart.js)
- Query submission system to faculty

### 👨‍🏫 Faculty Module
- Manage student attendance records
- Manage internal marks (CA1, CA2, CA3)
- View subject-wise student performance analytics
- Access full student attendance history
- Respond to student queries
- Smart student performance overview dashboard

### 🔐 Admin Module
- Manage users (students & faculty)
- Manage academic subjects
- System-level overview dashboard

---

## 🧠 Smart Features

- 📊 Real-time attendance analytics
- 📉 Automatic low attendance detection (<75%)
- 🎯 Pre-exam risk analysis system
- 📈 CA performance visualization
- ⚡ Predictive attendance insights
- 📌 Role-based dashboards (Student / Faculty / Admin)

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

Core tables used:
- users
- subjects
- attendance
- marks
- queries

---

## 📂 Project Structure

```

EduSphere/
│
├── assets/              # CSS, JS, images
├── includes/           # DB connection & helper functions
├── dashboard.php       # Main dashboard
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
- Subject-wise attendance calculation using SQL aggregation
- Percentage computation and visualization

### 📌 Marks System
- Internal assessment tracking (CA1, CA2, CA3)
- Average performance calculation per subject

### 📌 Pre-Exam Intelligence System
- Flags students with:
  - Low attendance (<75%)
  - Low CA performance
- Helps students identify academic risks early

### 📌 Smart Dashboard
- Graphical representation of academic performance
- Role-based dashboards with dynamic data visualization

---
