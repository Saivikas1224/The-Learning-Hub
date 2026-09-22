/* =========================================================
   THE LEARNING HUB
   COURSE CONTENT DATABASE
   Frontend Version - No XAMPP / MySQL Required
========================================================= */

const courseContent = {

    /* =====================================================
       JAVA PROGRAMMING
    ===================================================== */

    java: {

        id: "java",

        title: "Java Programming",

        category: "Programming",

        icon: "fab fa-java",

        description:
            "Learn Java programming from the fundamentals to object-oriented programming.",

        modules: [

            {
                id: "java-module-1",
                title: "Introduction to Java",

                lessons: [

                    {
                        id: "java-1-1",
                        title: "What is Java?",

                        content: `
                            <h2>What is Java?</h2>

                            <p>
                                Java is a high-level, object-oriented programming language
                                developed by Sun Microsystems. Java is widely used for
                                building desktop applications, web applications,
                                enterprise applications and many other software systems.
                            </p>

                            <h3>Why Learn Java?</h3>

                            <ul>
                                <li>Java is easy to learn for beginners.</li>
                                <li>It follows object-oriented programming principles.</li>
                                <li>Java is platform independent.</li>
                                <li>It provides strong security features.</li>
                                <li>Java has a large developer community.</li>
                            </ul>

                            <h3>Where is Java Used?</h3>

                            <ul>
                                <li>Web applications</li>
                                <li>Desktop applications</li>
                                <li>Enterprise applications</li>
                                <li>Android applications</li>
                                <li>Cloud applications</li>
                            </ul>
                        `,

                        code: `class Main {

    public static void main(String[] args) {

        System.out.println("Hello, World!");

    }

}`,

                        output: `Hello, World!`,

                        practice:
                            "Write a Java program that prints your name."
                    },


                    {
                        id: "java-1-2",
                        title: "Features of Java",

                        content: `
                            <h2>Features of Java</h2>

                            <p>
                                Java provides several features that make it a popular
                                programming language.
                            </p>

                            <h3>1. Simple</h3>

                            <p>
                                Java has a relatively simple syntax and removes many
                                complex features found in older programming languages.
                            </p>

                            <h3>2. Object-Oriented</h3>

                            <p>
                                Java uses classes and objects to organize programs.
                            </p>

                            <h3>3. Platform Independent</h3>

                            <p>
                                Java programs are compiled into bytecode which can run
                                on any system that has a Java Virtual Machine.
                            </p>

                            <h3>4. Secure</h3>

                            <p>
                                Java provides several security mechanisms including
                                bytecode verification and controlled memory access.
                            </p>

                            <h3>5. Robust</h3>

                            <p>
                                Java provides exception handling and automatic memory
                                management through garbage collection.
                            </p>
                        `,

                        practice:
                            "List any five important features of Java."
                    },


                    {
                        id: "java-1-3",
                        title: "JDK, JRE and JVM",

                        content: `
                            <h2>JDK, JRE and JVM</h2>

                            <p>
                                JDK, JRE and JVM are important components of the Java
                                platform.
                            </p>

                            <h3>JVM</h3>

                            <p>
                                JVM stands for Java Virtual Machine. It executes Java
                                bytecode and allows Java programs to run on different
                                operating systems.
                            </p>

                            <h3>JRE</h3>

                            <p>
                                JRE stands for Java Runtime Environment. It contains
                                the JVM and the libraries required to run Java programs.
                            </p>

                            <h3>JDK</h3>

                            <p>
                                JDK stands for Java Development Kit. It contains the
                                tools required to develop and run Java applications.
                            </p>

                            <p>
                                The basic relationship is:
                            </p>

                            <p>
                                <strong>JDK → JRE → JVM</strong>
                            </p>
                        `,

                        practice:
                            "Explain the difference between JDK, JRE and JVM."
                    }

                ]
            },


            /* =================================================
               MODULE 2
            ================================================= */

            {
                id: "java-module-2",

                title: "Java Basics",

                lessons: [

                    {
                        id: "java-2-1",

                        title: "Java Program Structure",

                        content: `
                            <h2>Java Program Structure</h2>

                            <p>
                                A Java program normally contains a class and a main
                                method. Program execution begins from the main method.
                            </p>

                            <h3>Example</h3>

                            <p>
                                The following program prints a message on the screen.
                            </p>
                        `,

                        code: `class Main {

    public static void main(String[] args) {

        System.out.println("Welcome to The Learning Hub");

    }

}`,

                        output:
                            `Welcome to The Learning Hub`,

                        practice:
                            "Create a Java program that prints three different messages."
                    },


                    {
                        id: "java-2-2",

                        title: "Variables and Data Types",

                        content: `
                            <h2>Variables and Data Types</h2>

                            <p>
                                A variable is a named memory location used to store
                                data.
                            </p>

                            <h3>Common Data Types</h3>

                            <ul>
                                <li>int - stores integer values</li>
                                <li>float - stores decimal values</li>
                                <li>double - stores larger decimal values</li>
                                <li>char - stores a single character</li>
                                <li>boolean - stores true or false</li>
                                <li>String - stores text</li>
                            </ul>
                        `,

                        code: `class Main {

    public static void main(String[] args) {

        int age = 18;

        double marks = 85.5;

        char grade = 'A';

        boolean passed = true;

        String name = "Arnav";

        System.out.println(name);
        System.out.println(age);
        System.out.println(marks);
        System.out.println(grade);
        System.out.println(passed);

    }

}`,

                        output: `Arnav
18
85.5
A
true`,

                        practice:
                            "Create variables for your name, age, CGPA and branch."
                    },


                    {
                        id: "java-2-3",

                        title: "Operators in Java",

                        content: `
                            <h2>Operators in Java</h2>

                            <p>
                                Operators are symbols used to perform operations on
                                values and variables.
                            </p>

                            <h3>Arithmetic Operators</h3>

                            <p>
                                Java provides operators such as +, -, *, / and %.
                            </p>

                            <h3>Relational Operators</h3>

                            <p>
                                Relational operators compare two values.
                            </p>

                            <p>
                                Examples include ==, !=, >, <, >= and <=.
                            </p>

                            <h3>Logical Operators</h3>

                            <p>
                                Logical operators include &&, || and !.
                            </p>
                        `,

                        code: `class Main {

    public static void main(String[] args) {

        int a = 20;
        int b = 10;

        System.out.println(a + b);
        System.out.println(a - b);
        System.out.println(a * b);
        System.out.println(a / b);
        System.out.println(a % b);

    }

}`,

                        output: `30
10
200
2
0`,

                        practice:
                            "Write a program to perform all arithmetic operations on two numbers."
                    }

                ]
            },


            /* =================================================
               MODULE 3
            ================================================= */

            {
                id: "java-module-3",

                title: "Control Statements",

                lessons: [

                    {
                        id: "java-3-1",

                        title: "if-else Statement",

                        content: `
                            <h2>if-else Statement</h2>

                            <p>
                                The if-else statement is used to make decisions
                                in a Java program.
                            </p>

                            <p>
                                If the condition is true, the if block is executed.
                                Otherwise, the else block is executed.
                            </p>
                        `,

                        code: `class Main {

    public static void main(String[] args) {

        int marks = 75;

        if (marks >= 40) {

            System.out.println("Pass");

        } else {

            System.out.println("Fail");

        }

    }

}`,

                        output:
                            `Pass`,

                        practice:
                            "Write a program to check whether a number is positive or negative."
                    },


                    {
                        id: "java-3-2",

                        title: "for Loop",

                        content: `
                            <h2>for Loop</h2>

                            <p>
                                A for loop is used when we want to execute a block
                                of code repeatedly.
                            </p>

                            <p>
                                A for loop normally contains initialization,
                                condition and increment/decrement.
                            </p>
                        `,

                        code: `class Main {

    public static void main(String[] args) {

        for (int i = 1; i <= 5; i++) {

            System.out.println(i);

        }

    }

}`,

                        output: `1
2
3
4
5`,

                        practice:
                            "Write a Java program to print numbers from 1 to 10."
                    },


                    {
                        id: "java-3-3",

                        title: "while Loop",

                        content: `
                            <h2>while Loop</h2>

                            <p>
                                The while loop executes a block of code repeatedly
                                as long as its condition remains true.
                            </p>
                        `,

                        code: `class Main {

    public static void main(String[] args) {

        int i = 1;

        while (i <= 5) {

            System.out.println(i);

            i++;

        }

    }

}`,

                        output: `1
2
3
4
5`,

                        practice:
                            "Use a while loop to print even numbers from 2 to 20."
                    }

                ]
            },


            /* =================================================
               MODULE 4
            ================================================= */

            {
                id: "java-module-4",

                title: "Object-Oriented Programming",

                lessons: [

                    {
                        id: "java-4-1",

                        title: "Classes and Objects",

                        content: `
                            <h2>Classes and Objects</h2>

                            <p>
                                A class is a blueprint used to create objects.
                                An object is an instance of a class.
                            </p>

                            <h3>Example</h3>

                            <p>
                                A Student class can contain properties such as
                                name and age and methods such as display().
                            </p>
                        `,

                        code: `class Student {

    String name;
    int age;

    void display() {

        System.out.println(name);
        System.out.println(age);

    }

}

class Main {

    public static void main(String[] args) {

        Student s = new Student();

        s.name = "Arnav";
        s.age = 18;

        s.display();

    }

}`,

                        output: `Arnav
18`,

                        practice:
                            "Create a Student class with name, roll number and marks."
                    },


                    {
                        id: "java-4-2",

                        title: "Constructors",

                        content: `
                            <h2>Constructors</h2>

                            <p>
                                A constructor is a special method that is automatically
                                called when an object is created.
                            </p>

                            <p>
                                A constructor has the same name as its class and does
                                not have a return type.
                            </p>
                        `,

                        code: `class Student {

    String name;

    Student(String name) {

        this.name = name;

    }

    void display() {

        System.out.println(name);

    }

}

class Main {

    public static void main(String[] args) {

        Student s = new Student("Arnav");

        s.display();

    }

}`,

                        output:
                            `Arnav`,

                        practice:
                            "Create a constructor that initializes a student's name and age."
                    },


                    {
                        id: "java-4-3",

                        title: "Inheritance",

                        content: `
                            <h2>Inheritance</h2>

                            <p>
                                Inheritance allows one class to acquire properties
                                and methods of another class.
                            </p>

                            <p>
                                The class that inherits is called the child class
                                and the class being inherited from is called the
                                parent class.
                            </p>
                        `,

                        code: `class Animal {

    void eat() {

        System.out.println("Animal eats");

    }

}

class Dog extends Animal {

    void bark() {

        System.out.println("Dog barks");

    }

}

class Main {

    public static void main(String[] args) {

        Dog d = new Dog();

        d.eat();
        d.bark();

    }

}`,

                        output: `Animal eats
Dog barks`,

                        practice:
                            "Create an Animal class and derive a Dog class from it."
                    }

                ]
            },


            /* =================================================
               MODULE 5
            ================================================= */

            {
                id: "java-module-5",

                title: "Arrays and Exception Handling",

                lessons: [

                    {
                        id: "java-5-1",

                        title: "Arrays",

                        content: `
                            <h2>Arrays</h2>

                            <p>
                                An array is a collection of elements of the same
                                data type stored under one variable name.
                            </p>

                            <p>
                                Array indexing starts from zero.
                            </p>
                        `,

                        code: `class Main {

    public static void main(String[] args) {

        int[] numbers = {10, 20, 30, 40, 50};

        for (int i = 0; i < numbers.length; i++) {

            System.out.println(numbers[i]);

        }

    }

}`,

                        output: `10
20
30
40
50`,

                        practice:
                            "Create an array containing five student marks and print them."
                    },


                    {
                        id: "java-5-2",

                        title: "Exception Handling",

                        content: `
                            <h2>Exception Handling</h2>

                            <p>
                                Exception handling is used to handle runtime errors
                                without abruptly terminating the program.
                            </p>

                            <p>
                                Java provides try, catch, finally, throw and throws
                                for exception handling.
                            </p>
                        `,

                        code: `class Main {

    public static void main(String[] args) {

        try {

            int result = 10 / 0;

            System.out.println(result);

        } catch (ArithmeticException e) {

            System.out.println("Cannot divide by zero");

        }

    }

}`,

                        output:
                            `Cannot divide by zero`,

                        practice:
                            "Write a Java program that handles division by zero."
                    }

                ]
            }

        ]
    },


    /* =====================================================
       PYTHON PROGRAMMING
    ===================================================== */

    python: {

        id: "python",

        title: "Python Programming",

        category: "Programming",

        icon: "fab fa-python",

        description:
            "Learn Python programming from the basics to functions, collections and object-oriented programming.",

        modules: [

            {
                id: "python-module-1",

                title: "Introduction to Python",

                lessons: [

                    {
                        id: "python-1-1",

                        title: "What is Python?",

                        content: `
                            <h2>What is Python?</h2>

                            <p>
                                Python is a high-level, interpreted programming
                                language known for its simple and readable syntax.
                            </p>

                            <h3>Uses of Python</h3>

                            <ul>
                                <li>Web development</li>
                                <li>Data science</li>
                                <li>Artificial intelligence</li>
                                <li>Machine learning</li>
                                <li>Automation</li>
                                <li>Desktop applications</li>
                            </ul>
                        `,

                        code: `print("Hello, World!")`,

                        output:
                            `Hello, World!`,

                        practice:
                            "Write a Python program to print your name."
                    },


                    {
                        id: "python-1-2",

                        title: "Variables and Data Types",

                        content: `
                            <h2>Variables and Data Types</h2>

                            <p>
                                A variable is a name used to store a value.
                                Python automatically determines the data type.
                            </p>

                            <h3>Common Python Data Types</h3>

                            <ul>
                                <li>int</li>
                                <li>float</li>
                                <li>str</li>
                                <li>bool</li>
                                <li>list</li>
                                <li>tuple</li>
                                <li>set</li>
                                <li>dict</li>
                            </ul>
                        `,

                        code: `name = "Arnav"
age = 18
marks = 85.5
passed = True

print(name)
print(age)
print(marks)
print(passed)`,

                        output: `Arnav
18
85.5
True`,

                        practice:
                            "Create variables for your name, age, branch and CGPA."
                    }

                ]
            },


            {
                id: "python-module-2",

                title: "Conditional Statements and Loops",

                lessons: [

                    {
                        id: "python-2-1",

                        title: "if-else",

                        content: `
                            <h2>if-else Statement</h2>

                            <p>
                                The if statement is used to execute code based on
                                a condition.
                            </p>
                        `,

                        code: `marks = 75

if marks >= 40:
    print("Pass")
else:
    print("Fail")`,

                        output:
                            `Pass`,

                        practice:
                            "Write a program to check whether a number is positive or negative."
                    },


                    {
                        id: "python-2-2",

                        title: "for Loop",

                        content: `
                            <h2>for Loop</h2>

                            <p>
                                A for loop is used to iterate over a sequence
                                or range of values.
                            </p>
                        `,

                        code: `for i in range(1, 6):
    print(i)`,

                        output: `1
2
3
4
5`,

                        practice:
                            "Print numbers from 1 to 10 using a for loop."
                    }

                ]
            },


            {
                id: "python-module-3",

                title: "Functions",

                lessons: [

                    {
                        id: "python-3-1",

                        title: "Creating Functions",

                        content: `
                            <h2>Functions in Python</h2>

                            <p>
                                A function is a reusable block of code that performs
                                a specific task.
                            </p>
                        `,

                        code: `def greet(name):
    print("Hello", name)

greet("Arnav")`,

                        output:
                            `Hello Arnav`,

                        practice:
                            "Create a function that calculates the square of a number."
                    }

                ]
            },


            {
                id: "python-module-4",

                title: "Collections",

                lessons: [

                    {
                        id: "python-4-1",

                        title: "Lists",

                        content: `
                            <h2>Lists</h2>

                            <p>
                                A list is an ordered and mutable collection of
                                elements.
                            </p>
                        `,

                        code: `numbers = [10, 20, 30, 40]

for number in numbers:
    print(number)`,

                        output: `10
20
30
40`,

                        practice:
                            "Create a list of five subjects and print each subject."
                    }

                ]
            },


            {
                id: "python-module-5",

                title: "Object-Oriented Programming",

                lessons: [

                    {
                        id: "python-5-1",

                        title: "Classes and Objects",

                        content: `
                            <h2>Classes and Objects</h2>

                            <p>
                                A class defines the structure and behavior of
                                objects. An object is an instance of a class.
                            </p>
                        `,

                        code: `class Student:

    def __init__(self, name):
        self.name = name

    def display(self):
        print(self.name)


student = Student("Arnav")

student.display()`,

                        output:
                            `Arnav`,

                        practice:
                            "Create a Student class with name and age."
                    }

                ]
            }

        ]
    },


    /* =====================================================
       HTML
    ===================================================== */

    html: {

        id: "html",

        title: "HTML",

        category: "Web Development",

        icon: "fab fa-html5",

        description:
            "Learn HTML and build the structure of modern web pages.",

        modules: [

            {
                id: "html-module-1",

                title: "HTML Fundamentals",

                lessons: [

                    {
                        id: "html-1-1",

                        title: "Introduction to HTML",

                        content: `
                            <h2>Introduction to HTML</h2>

                            <p>
                                HTML stands for HyperText Markup Language.
                                It is used to create the structure of web pages.
                            </p>

                            <h3>HTML is used for</h3>

                            <ul>
                                <li>Headings</li>
                                <li>Paragraphs</li>
                                <li>Images</li>
                                <li>Links</li>
                                <li>Forms</li>
                                <li>Tables</li>
                            </ul>
                        `,

                        code: `<!DOCTYPE html>
<html>

<head>
    <title>My Website</title>
</head>

<body>

    <h1>Hello World</h1>

    <p>Welcome to The Learning Hub.</p>

</body>

</html>`,

                        practice:
                            "Create an HTML page containing your name and course."
                    },


                    {
                        id: "html-1-2",

                        title: "HTML Headings and Paragraphs",

                        content: `
                            <h2>Headings and Paragraphs</h2>

                            <p>
                                HTML provides six heading levels from h1 to h6.
                                Paragraphs are created using the p element.
                            </p>
                        `,

                        code: `<h1>Main Heading</h1>

<h2>Sub Heading</h2>

<p>
    This is a paragraph.
</p>`,

                        practice:
                            "Create a page containing three headings and two paragraphs."
                    }

                ]
            },


            {
                id: "html-module-2",

                title: "Links and Images",

                lessons: [

                    {
                        id: "html-2-1",

                        title: "HTML Links",

                        content: `
                            <h2>HTML Links</h2>

                            <p>
                                Links allow users to navigate from one webpage
                                to another.
                            </p>
                        `,

                        code: `<a href="https://example.com">
    Visit Website
</a>`,

                        practice:
                            "Create links to three different websites."
                    },


                    {
                        id: "html-2-2",

                        title: "HTML Images",

                        content: `
                            <h2>HTML Images</h2>

                            <p>
                                The img element is used to display images on
                                a webpage.
                            </p>
                        `,

                        code: `<img src="image.jpg"
     alt="Learning Hub"
     width="300">`,

                        practice:
                            "Add an image to an HTML webpage."
                    }

                ]
            }

        ]
    },


    /* =====================================================
       CSS
    ===================================================== */

    css: {

        id: "css",

        title: "CSS",

        category: "Web Development",

        icon: "fab fa-css3-alt",

        description:
            "Learn CSS and create attractive responsive websites.",

        modules: [

            {
                id: "css-module-1",

                title: "CSS Fundamentals",

                lessons: [

                    {
                        id: "css-1-1",

                        title: "What is CSS?",

                        content: `
                            <h2>What is CSS?</h2>

                            <p>
                                CSS stands for Cascading Style Sheets.
                                It is used to style HTML elements.
                            </p>

                            <p>
                                CSS controls colors, fonts, spacing,
                                layouts and responsive design.
                            </p>
                        `,

                        code: `body {
    background: black;
    color: white;
}

h1 {
    color: #00ff66;
}`,

                        practice:
                            "Create a webpage with a black background and green heading."
                    },


                    {
                        id: "css-1-2",

                        title: "CSS Selectors",

                        content: `
                            <h2>CSS Selectors</h2>

                            <p>
                                Selectors are used to select HTML elements
                                that need to be styled.
                            </p>

                            <ul>
                                <li>Element selector</li>
                                <li>Class selector</li>
                                <li>ID selector</li>
                                <li>Universal selector</li>
                            </ul>
                        `,

                        code: `p {
    color: white;
}

.title {
    color: #00ff66;
}

#main {
    background: black;
}`,

                        practice:
                            "Create examples using element, class and ID selectors."
                    }

                ]
            }

        ]
    },


    /* =====================================================
       JAVASCRIPT
    ===================================================== */

    javascript: {

        id: "javascript",

        title: "JavaScript",

        category: "Web Development",

        icon: "fab fa-js-square",

        description:
            "Learn JavaScript and make websites interactive.",

        modules: [

            {
                id: "javascript-module-1",

                title: "JavaScript Fundamentals",

                lessons: [

                    {
                        id: "javascript-1-1",

                        title: "Introduction to JavaScript",

                        content: `
                            <h2>Introduction to JavaScript</h2>

                            <p>
                                JavaScript is a programming language commonly
                                used to make web pages interactive.
                            </p>

                            <h3>JavaScript can</h3>

                            <ul>
                                <li>Change HTML content</li>
                                <li>Change CSS styles</li>
                                <li>Handle user events</li>
                                <li>Validate forms</li>
                                <li>Communicate with servers</li>
                            </ul>
                        `,

                        code: `console.log("Hello JavaScript");`,

                        output:
                            `Hello JavaScript`,

                        practice:
                            "Write a JavaScript program that prints your name."
                    },


                    {
                        id: "javascript-1-2",

                        title: "Variables",

                        content: `
                            <h2>JavaScript Variables</h2>

                            <p>
                                Variables are used to store values.
                                JavaScript provides let, const and var.
                            </p>
                        `,

                        code: `let name = "Arnav";

const age = 18;

console.log(name);
console.log(age);`,

                        output: `Arnav
18`,

                        practice:
                            "Create variables for your name, age and college."
                    }

                ]
            }

        ]
    }

};


/* =========================================================
   GET COURSE
========================================================= */

function getCourse(courseId) {

    if (!courseId) {
        return null;
    }

    courseId = courseId.toLowerCase();

    return courseContent[courseId] || null;
}


/* =========================================================
   GET ALL COURSES
========================================================= */

function getAllCourses() {

    return Object.values(courseContent);

}


/* =========================================================
   GET TOTAL LESSONS
========================================================= */

function getTotalLessons(course) {

    if (!course || !course.modules) {
        return 0;
    }

    let total = 0;

    course.modules.forEach(module => {

        if (module.lessons) {
            total += module.lessons.length;
        }

    });

    return total;
}


/* =========================================================
   FIND LESSON
========================================================= */

function findLesson(course, lessonId) {

    if (!course || !course.modules) {
        return null;
    }

    for (const module of course.modules) {

        for (const lesson of module.lessons) {

            if (lesson.id === lessonId) {

                return {
                    lesson: lesson,
                    module: module
                };

            }

        }

    }

    return null;
}