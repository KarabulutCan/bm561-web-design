# Course Management System

A simple **PHP & MySQL** project for managing courses, students, and their payments. This web application allows you to:

- Create, read, update, and delete (CRUD) **Courses**  
- Manage **Students** (CRUD)  
- Assign **Students** to **Courses** (many-to-many relationship)  
- Track **Payments** made by students (basic financial records)  
- Perform quick searches on course listings with a custom JavaScript filter  
- Utilize **Tailwind CSS** (for design) plus custom CSS and JS for additional styling and functionality

---

## Table of Contents
1. [Features](#features)  
2. [Prerequisites](#prerequisites)  
3. [Installation](#installation)  
4. [Database Setup](#database-setup)  
5. [Usage](#usage)  
6. [Folder Structure](#folder-structure)  
7. [Technologies Used](#technologies-used)  
8. [License](#license)

---

## Features
- **Admin-only system**: No user sign-up; an admin manages all records  
- **CRUD for Courses**: Add, edit, delete courses  
- **CRUD for Students**: Add, edit, delete student records  
- **Student-Course Assignments**: Many-to-many relationship via an enrollment table  
- **Payments**: Record student payments, amount, date, and optional notes  
- **Custom CSS & JS**: Tailwind for layout, plus local style.css and script.js for additional features (e.g., table search)

---

## Prerequisites
- [XAMPP](https://www.apachefriends.org/) (or any PHP 7.4+ & MySQL-compatible environment)  
- Basic knowledge of how to run a local server with PHP  
- A web browser (Chrome, Firefox, Edge, etc.)

---

## Installation
1. **Clone or download** this repository into your local server directory (e.g., `htdocs` if using XAMPP)
2. Ensure **Apache** and **MySQL** are running in your XAMPP Control Panel  
3. Open your web browser and navigate to:  
   `http://localhost/`

---

## Database Setup
1. Open [phpMyAdmin](http://localhost/phpmyadmin/)  
2. Create a new database (e.g. `ogrenci_otomasyon`) and run the following SQL commands to create the required tables:

```sql
CREATE DATABASE ogrenci_otomasyon;
USE ogrenci_otomasyon;

CREATE TABLE courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description VARCHAR(255)
);

CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    phone VARCHAR(20)
);

CREATE TABLE enrollments (
    student_id INT NOT NULL,
    course_id INT NOT NULL,
    PRIMARY KEY (student_id, course_id),
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
);

CREATE TABLE payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    course_id INT,
    amount DECIMAL(10,2) NOT NULL,
    payment_date DATE NOT NULL,
    note VARCHAR(255),
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE SET NULL
);
```

3. Edit your `db.php` file to match your local MySQL credentials:

```php
// db.php
$host = 'localhost';
$dbName = 'ogrenci_otomasyon';
$dbUser = 'root';
$dbPass = ''; // Default empty for XAMPP
```

---

## Usage
Visit [http://localhost/](http://localhost/) in your browser.

- Click **Courses** to manage all available courses (add, update, delete)  
- Click **Students** to manage student records  
- Click **Atama (Enrollments)** to assign students to courses  
- Click **Ödemeler (Payments)** to record or view student payments  
- A search field is available on certain pages (e.g., `courses.php`) to filter table results using JavaScript

---

## Folder Structure

```
project-root/
├── assets/
│   ├── css/
│   │   └── style.css         # Custom CSS
│   └── js/
│       └── script.js         # Custom JS
├── db.php                    # Database connection with PDO
├── layout.php                # Master page / common layout
├── index.php                 # Homepage
├── courses.php               # Courses CRUD
├── students.php              # Students CRUD
├── enrollments.php           # Student-course assignments
├── payments.php              # Manage payments
└── README.md                 # Project info
```

---

## Technologies Used
- PHP (procedural code with PDO for database interactions)  
- MySQL (for data storage)  
- Tailwind CSS (front-end styling via CDN)  
- Custom CSS & JS (in `assets/css/style.css` and `assets/js/script.js`)  
- HTML5 for page structure

---

## License
This project is for educational purposes. You may adapt and reuse it freely.

Feel free to customize the code base, add authentication, or enhance functionality as needed. Enjoy!
