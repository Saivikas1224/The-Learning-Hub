-- =========================================================
-- THE LEARNING HUB
-- Smart Education Website
-- Database: MySQL / XAMPP
-- =========================================================


-- Create Database
CREATE DATABASE IF NOT EXISTS learning_hub;

USE learning_hub;



-- =========================================================
-- 1. USERS TABLE
-- =========================================================

CREATE TABLE IF NOT EXISTS users (

    user_id INT AUTO_INCREMENT PRIMARY KEY,

    full_name VARCHAR(100) NOT NULL,

    email VARCHAR(100) NOT NULL UNIQUE,

    password VARCHAR(255) NOT NULL,

    phone VARCHAR(20),

    course VARCHAR(100),

    created_at DATETIME DEFAULT NULL

);



-- =========================================================
-- 2. COURSES TABLE
-- =========================================================

CREATE TABLE IF NOT EXISTS courses (

    course_id INT AUTO_INCREMENT PRIMARY KEY,

    course_name VARCHAR(100) NOT NULL,

    description TEXT,

    category VARCHAR(100),

    difficulty VARCHAR(50),

    duration VARCHAR(50),

    image VARCHAR(255)

);



-- =========================================================
-- 3. ENROLLMENTS TABLE
-- =========================================================

CREATE TABLE IF NOT EXISTS enrollments (

    enrollment_id INT AUTO_INCREMENT PRIMARY KEY,

    user_id INT NOT NULL,

    course_id INT NOT NULL,

    enrolled_at DATETIME DEFAULT NULL,

    progress INT DEFAULT 0,

    status VARCHAR(30) DEFAULT 'Active',

    FOREIGN KEY (user_id)
        REFERENCES users(user_id)
        ON DELETE CASCADE,

    FOREIGN KEY (course_id)
        REFERENCES courses(course_id)
        ON DELETE CASCADE

);



-- =========================================================
-- 4. NOTES TABLE
-- =========================================================

CREATE TABLE IF NOT EXISTS notes (

    note_id INT AUTO_INCREMENT PRIMARY KEY,

    user_id INT NOT NULL,

    title VARCHAR(200) NOT NULL,

    subject VARCHAR(100),

    content TEXT NOT NULL,

    created_at DATETIME DEFAULT NULL,

    updated_at DATETIME DEFAULT NULL,

    FOREIGN KEY (user_id)
        REFERENCES users(user_id)
        ON DELETE CASCADE

);



-- =========================================================
-- 5. STUDY PLAN TABLE
-- =========================================================

CREATE TABLE IF NOT EXISTS study_plans (

    plan_id INT AUTO_INCREMENT PRIMARY KEY,

    user_id INT NOT NULL,

    subject VARCHAR(100) NOT NULL,

    topic VARCHAR(200),

    study_date DATE,

    start_time TIME,

    end_time TIME,

    status VARCHAR(30) DEFAULT 'Pending',

    FOREIGN KEY (user_id)
        REFERENCES users(user_id)
        ON DELETE CASCADE

);



-- =========================================================
-- 6. TIMETABLE TABLE
-- =========================================================

CREATE TABLE IF NOT EXISTS timetable (

    timetable_id INT AUTO_INCREMENT PRIMARY KEY,

    user_id INT NOT NULL,

    subject VARCHAR(100) NOT NULL,

    topic VARCHAR(200),

    day VARCHAR(20),

    start_time TIME,

    end_time TIME,

    location VARCHAR(100),

    FOREIGN KEY (user_id)
        REFERENCES users(user_id)
        ON DELETE CASCADE

);



-- =========================================================
-- 7. QUIZ TABLE
-- =========================================================

CREATE TABLE IF NOT EXISTS quizzes (

    quiz_id INT AUTO_INCREMENT PRIMARY KEY,

    course_id INT,

    quiz_title VARCHAR(200) NOT NULL,

    description TEXT,

    total_questions INT DEFAULT 0,

    FOREIGN KEY (course_id)
        REFERENCES courses(course_id)
        ON DELETE SET NULL

);



-- =========================================================
-- 8. QUESTIONS TABLE
-- =========================================================

CREATE TABLE IF NOT EXISTS questions (

    question_id INT AUTO_INCREMENT PRIMARY KEY,

    quiz_id INT NOT NULL,

    question_text TEXT NOT NULL,

    option_a VARCHAR(500),

    option_b VARCHAR(500),

    option_c VARCHAR(500),

    option_d VARCHAR(500),

    correct_answer CHAR(1),

    FOREIGN KEY (quiz_id)
        REFERENCES quizzes(quiz_id)
        ON DELETE CASCADE

);



-- =========================================================
-- 9. QUIZ RESULTS TABLE
-- =========================================================

CREATE TABLE IF NOT EXISTS quiz_results (

    result_id INT AUTO_INCREMENT PRIMARY KEY,

    user_id INT NOT NULL,

    quiz_id INT NOT NULL,

    score INT DEFAULT 0,

    total_questions INT DEFAULT 0,

    percentage DECIMAL(5,2) DEFAULT 0,

    completed_at DATETIME DEFAULT NULL,

    FOREIGN KEY (user_id)
        REFERENCES users(user_id)
        ON DELETE CASCADE,

    FOREIGN KEY (quiz_id)
        REFERENCES quizzes(quiz_id)
        ON DELETE CASCADE

);



-- =========================================================
-- 10. PROGRESS TABLE
-- =========================================================

CREATE TABLE IF NOT EXISTS progress (

    progress_id INT AUTO_INCREMENT PRIMARY KEY,

    user_id INT NOT NULL,

    course_id INT,

    completed_topics INT DEFAULT 0,

    total_topics INT DEFAULT 0,

    percentage INT DEFAULT 0,

    last_updated DATETIME DEFAULT NULL,

    FOREIGN KEY (user_id)
        REFERENCES users(user_id)
        ON DELETE CASCADE,

    FOREIGN KEY (course_id)
        REFERENCES courses(course_id)
        ON DELETE SET NULL

);



-- =========================================================
-- 11. AI CHAT HISTORY
-- =========================================================

CREATE TABLE IF NOT EXISTS ai_chat (

    chat_id INT AUTO_INCREMENT PRIMARY KEY,

    user_id INT NOT NULL,

    question TEXT NOT NULL,

    answer TEXT,

    created_at DATETIME DEFAULT NULL,

    FOREIGN KEY (user_id)
        REFERENCES users(user_id)
        ON DELETE CASCADE

);



-- =========================================================
-- 12. CONTACT MESSAGES
-- =========================================================

CREATE TABLE IF NOT EXISTS contact_messages (

    message_id INT AUTO_INCREMENT PRIMARY KEY,

    name VARCHAR(100) NOT NULL,

    email VARCHAR(100) NOT NULL,

    subject VARCHAR(200),

    message TEXT NOT NULL,

    created_at DATETIME DEFAULT NULL

);



-- =========================================================
-- INSERT COURSES
-- =========================================================

INSERT INTO courses
(
    course_name,
    description,
    category,
    difficulty,
    duration
)
VALUES

(
    'Java Programming',
    'Learn Java programming from fundamentals to object-oriented programming.',
    'Programming',
    'Beginner',
    '8 Weeks'
),

(
    'Python Programming',
    'Learn Python programming, data structures and problem solving.',
    'Programming',
    'Beginner',
    '8 Weeks'
),

(
    'Web Development',
    'Learn HTML, CSS and JavaScript to build modern websites.',
    'Web Development',
    'Beginner',
    '10 Weeks'
),

(
    'Database Management',
    'Learn SQL, MySQL and database management concepts.',
    'Database',
    'Intermediate',
    '6 Weeks'
),

(
    'Artificial Intelligence',
    'Learn the fundamentals of Artificial Intelligence and intelligent systems.',
    'Artificial Intelligence',
    'Intermediate',
    '10 Weeks'
),

(
    'Data Structures',
    'Learn arrays, linked lists, stacks, queues, trees and graphs.',
    'Computer Science',
    'Intermediate',
    '10 Weeks'
),

(
    'Computer Networks',
    'Learn networking fundamentals, protocols and network security.',
    'Computer Science',
    'Intermediate',
    '8 Weeks'
);



-- =========================================================
-- SAMPLE QUIZZES
-- =========================================================

INSERT INTO quizzes
(
    course_id,
    quiz_title,
    description,
    total_questions
)
VALUES

(
    1,
    'Java Basics Quiz',
    'Test your basic Java programming knowledge.',
    5
),

(
    2,
    'Python Basics Quiz',
    'Test your Python programming knowledge.',
    5
),

(
    3,
    'HTML & CSS Quiz',
    'Test your web development fundamentals.',
    5
);



-- =========================================================
-- SAMPLE QUESTIONS
-- =========================================================

INSERT INTO questions
(
    quiz_id,
    question_text,
    option_a,
    option_b,
    option_c,
    option_d,
    correct_answer
)
VALUES

(
    1,
    'Which keyword is used to create a class in Java?',
    'class',
    'Class',
    'new',
    'object',
    'A'
),

(
    1,
    'Which method is the entry point of a Java program?',
    'start()',
    'main()',
    'run()',
    'execute()',
    'B'
),

(
    1,
    'Which symbol is used to end a statement in Java?',
    '.',
    ':',
    ';',
    ',',
    'C'
),

(
    1,
    'Which keyword is used for inheritance in Java?',
    'inherits',
    'extends',
    'inherit',
    'superclass',
    'B'
),

(
    1,
    'Which data type stores whole numbers in Java?',
    'float',
    'double',
    'String',
    'int',
    'D'
);



-- =========================================================
-- SAMPLE PYTHON QUESTIONS
-- =========================================================

INSERT INTO questions
(
    quiz_id,
    question_text,
    option_a,
    option_b,
    option_c,
    option_d,
    correct_answer
)
VALUES

(
    2,
    'Which keyword is used to define a function in Python?',
    'function',
    'def',
    'fun',
    'define',
    'B'
),

(
    2,
    'Which symbol is used for comments in Python?',
    '//',
    '/*',
    '#',
    '--',
    'C'
),

(
    2,
    'Which data type stores multiple values in an ordered collection?',
    'list',
    'integer',
    'boolean',
    'float',
    'A'
),

(
    2,
    'Which function is used to display output in Python?',
    'display()',
    'write()',
    'print()',
    'show()',
    'C'
),

(
    2,
    'Which extension is commonly used for Python files?',
    '.java',
    '.html',
    '.py',
    '.css',
    'C'
);



-- =========================================================
-- SAMPLE HTML/CSS QUESTIONS
-- =========================================================

INSERT INTO questions
(
    quiz_id,
    question_text,
    option_a,
    option_b,
    option_c,
    option_d,
    correct_answer
)
VALUES

(
    3,
    'Which HTML tag is used for the largest heading?',
    '<h6>',
    '<head>',
    '<h1>',
    '<heading>',
    'C'
),

(
    3,
    'Which language is used to style HTML pages?',
    'Java',
    'CSS',
    'Python',
    'SQL',
    'B'
),

(
    3,
    'Which HTML tag is used to create a link?',
    '<link>',
    '<a>',
    '<href>',
    '<url>',
    'B'
),

(
    3,
    'Which CSS property changes text color?',
    'font',
    'text-color',
    'color',
    'background',
    'C'
),

(
    3,
    'Which HTML tag is used to display an image?',
    '<picture>',
    '<image>',
    '<img>',
    '<src>',
    'C'
);



-- =========================================================
-- CHECK DATABASE
-- =========================================================

SHOW TABLES;