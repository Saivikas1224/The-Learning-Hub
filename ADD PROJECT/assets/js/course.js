/* =========================================================
   THE LEARNING HUB
   COURSE LEARNING SYSTEM
========================================================= */

document.addEventListener("DOMContentLoaded", function () {

    // Get course ID from URL
    const params = new URLSearchParams(window.location.search);

    const courseId = params.get("course");

    console.log("Course ID:", courseId);


    // Check whether course ID exists
    if (!courseId) {

        showError("No course selected.");

        return;
    }


    // Get course from course-content.js
    const course = getCourse(courseId);


    // Check whether course exists
    if (!course) {

        showError(
            "Course content not found for: " + courseId
        );

        console.error(
            "Available courses:",
            Object.keys(courseContent)
        );

        return;
    }


    console.log("Course loaded:", course);


    // Start course
    initializeCourse(course);

});


/* =========================================================
   COURSE VARIABLES
========================================================= */

let currentCourse = null;

let allLessons = [];

let currentLessonIndex = 0;

let completedLessons = [];


/* =========================================================
   INITIALIZE COURSE
========================================================= */

function initializeCourse(course) {

    currentCourse = course;


    // Convert modules into one lesson list
    allLessons = [];

    course.modules.forEach(function (module) {

        module.lessons.forEach(function (lesson) {

            allLessons.push({

                ...lesson,

                moduleTitle: module.title

            });

        });

    });


    // Load saved progress
    loadProgress();


    // Update course information
    displayCourseInformation();


    // Create sidebar
    createCourseSidebar();


    // Display current lesson
    displayLesson();


    // Update progress
    updateProgress();

}


/* =========================================================
   COURSE INFORMATION
========================================================= */

function displayCourseInformation() {

    const titleElement =
        document.querySelector(".course-title");

    const descriptionElement =
        document.querySelector(".course-description");


    if (titleElement) {

        titleElement.textContent =
            currentCourse.title;

    }


    if (descriptionElement) {

        descriptionElement.textContent =
            currentCourse.description;

    }


    // Navigation title
    const navTitle =
        document.querySelector(".nav-course-title");

    if (navTitle) {

        navTitle.textContent =
            currentCourse.title;

    }

}


/* =========================================================
   CREATE SIDEBAR
========================================================= */

function createCourseSidebar() {

    const sidebar =
        document.querySelector(".course-modules");

    if (!sidebar) {

        console.warn(
            "Course modules container not found."
        );

        return;
    }


    sidebar.innerHTML = "";


    currentCourse.modules.forEach(
        function (module, moduleIndex) {

            const moduleDiv =
                document.createElement("div");

            moduleDiv.className =
                "course-module";


            const header =
                document.createElement("div");

            header.className =
                "module-header";


            header.innerHTML = `

                <div class="module-title">

                    <span class="module-number">
                        ${moduleIndex + 1}
                    </span>

                    <span>
                        ${module.title}
                    </span>

                </div>

                <i class="fas fa-chevron-down"></i>

            `;


            const lessonsContainer =
                document.createElement("div");

            lessonsContainer.className =
                "lessons-container";


            module.lessons.forEach(
                function (lesson) {

                    const lessonButton =
                        document.createElement("button");

                    lessonButton.className =
                        "lesson-item";

                    lessonButton.dataset.lessonId =
                        lesson.id;


                    const lessonIndex =
                        allLessons.findIndex(
                            item =>
                                item.id === lesson.id
                        );


                    lessonButton.innerHTML = `

                        <span class="lesson-icon">

                            <i class="fas fa-play-circle"></i>

                        </span>

                        <span class="lesson-name">

                            ${lessonIndex + 1}.
                            ${lesson.title}

                        </span>

                    `;


                    lessonButton.addEventListener(
                        "click",
                        function () {

                            const index =
                                allLessons.findIndex(
                                    item =>
                                        item.id === lesson.id
                                );

                            if (index !== -1) {

                                currentLessonIndex =
                                    index;

                                displayLesson();

                                updateProgress();

                            }

                        }
                    );


                    lessonsContainer.appendChild(
                        lessonButton
                    );

                }
            );


            header.addEventListener(
                "click",
                function () {

                    moduleDiv.classList.toggle(
                        "expanded"
                    );

                }
            );


            moduleDiv.appendChild(header);

            moduleDiv.appendChild(
                lessonsContainer
            );

            sidebar.appendChild(moduleDiv);

        }
    );


    // Open first module
    const firstModule =
        sidebar.querySelector(".course-module");

    if (firstModule) {

        firstModule.classList.add("expanded");

    }

}


/* =========================================================
   DISPLAY LESSON
========================================================= */

function displayLesson() {

    if (
        !allLessons.length ||
        !allLessons[currentLessonIndex]
    ) {

        return;

    }


    const lesson =
        allLessons[currentLessonIndex];


    console.log(
        "Displaying lesson:",
        lesson.title
    );


    /* -----------------------------------------------------
       LESSON NUMBER
    ----------------------------------------------------- */

    const numberElement =
        document.querySelector(".lesson-number");

    if (numberElement) {

        numberElement.textContent =
            `LESSON ${currentLessonIndex + 1}`;

    }


    /* -----------------------------------------------------
       LESSON TITLE
    ----------------------------------------------------- */

    const titleElement =
        document.querySelector(".lesson-title");

    if (titleElement) {

        titleElement.textContent =
            lesson.title;

    }


    /* -----------------------------------------------------
       CURRENT LESSON TEXT
    ----------------------------------------------------- */

    const currentLessonElement =
        document.querySelector(".current-lesson");

    if (currentLessonElement) {

        currentLessonElement.textContent =
            `You are currently studying Lesson ${
                currentLessonIndex + 1
            } of ${allLessons.length}.`;

    }


    /* -----------------------------------------------------
       CONTENT
    ----------------------------------------------------- */

    const contentElement =
        document.querySelector(".lesson-content");


    if (!contentElement) {

        console.error(
            "ERROR: .lesson-content was not found in course.html"
        );

        return;

    }


    let html = "";


    // Explanation
    if (lesson.content) {

        html += `

            <div class="explanation-section">

                ${lesson.content}

            </div>

        `;

    }


    // Code
    if (lesson.code) {

        html += `

            <div class="code-section">

                <h3>
                    <i class="fas fa-code"></i>
                    Example Code
                </h3>

                <div class="code-container">

                    <button
                        class="copy-code-btn"
                        onclick="copyCode()"
                    >
                        <i class="fas fa-copy"></i>
                        Copy
                    </button>

                    <pre id="lesson-code"><code>${escapeHTML(
                        lesson.code
                    )}</code></pre>

                </div>

            </div>

        `;

    }


    // Output
    if (lesson.output) {

        html += `

            <div class="output-section">

                <h3>
                    <i class="fas fa-terminal"></i>
                    Output
                </h3>

                <div class="output-box">

                    <pre>${escapeHTML(
                        lesson.output
                    )}</pre>

                </div>

            </div>

        `;

    }


    // Practice
    if (lesson.practice) {

        html += `

            <div class="practice-section">

                <div class="practice-icon">

                    <i class="fas fa-lightbulb"></i>

                </div>

                <div>

                    <h3>Practice Task</h3>

                    <p>
                        ${lesson.practice}
                    </p>

                </div>

            </div>

        `;

    }


    contentElement.innerHTML = html;


    // Update lesson navigation
    updateNavigation();


    // Highlight sidebar
    updateSidebar();


    // Scroll to top of lesson
    window.scrollTo({

        top: 0,

        behavior: "smooth"

    });

}


/* =========================================================
   COPY CODE
========================================================= */

function copyCode() {

    const codeElement =
        document.querySelector("#lesson-code");

    if (!codeElement) {

        return;

    }


    navigator.clipboard.writeText(
        codeElement.innerText
    );


    const button =
        document.querySelector(".copy-code-btn");


    if (button) {

        const original =
            button.innerHTML;


        button.innerHTML =
            '<i class="fas fa-check"></i> Copied';


        setTimeout(function () {

            button.innerHTML =
                original;

        }, 1500);

    }

}


/* =========================================================
   NAVIGATION
========================================================= */

function updateNavigation() {

    const previousButton =
        document.querySelector(
            "#previousLesson"
        );


    const nextButton =
        document.querySelector(
            "#nextLesson"
        );


    const completeButton =
        document.querySelector(
            "#completeLesson"
        );


    /* Previous */

    if (previousButton) {

        previousButton.disabled =
            currentLessonIndex === 0;

    }


    /* Next */

    if (nextButton) {

        nextButton.disabled =
            currentLessonIndex ===
            allLessons.length - 1;

    }


    /* Complete */

    if (completeButton) {

        const lesson =
            allLessons[currentLessonIndex];


        if (
            completedLessons.includes(
                lesson.id
            )
        ) {

            completeButton.textContent =
                "Completed ✓";

            completeButton.classList.add(
                "completed"
            );

            completeButton.disabled = true;

        } else {

            completeButton.innerHTML =
                'Mark as Complete <i class="fas fa-check"></i>';

            completeButton.classList.remove(
                "completed"
            );

            completeButton.disabled = false;

        }

    }

}


/* =========================================================
   PREVIOUS LESSON
========================================================= */

function previousLesson() {

    if (currentLessonIndex > 0) {

        currentLessonIndex--;

        displayLesson();

        updateProgress();

    }

}


/* =========================================================
   NEXT LESSON
========================================================= */

function nextLesson() {

    if (
        currentLessonIndex <
        allLessons.length - 1
    ) {

        currentLessonIndex++;

        displayLesson();

        updateProgress();

    }

}


/* =========================================================
   COMPLETE LESSON
========================================================= */

function completeLesson() {

    const lesson =
        allLessons[currentLessonIndex];


    if (
        !completedLessons.includes(
            lesson.id
        )
    ) {

        completedLessons.push(
            lesson.id
        );

        saveProgress();

    }


    updateNavigation();

    updateProgress();


    // Automatically go to next lesson
    if (
        currentLessonIndex <
        allLessons.length - 1
    ) {

        setTimeout(function () {

            currentLessonIndex++;

            displayLesson();

            updateProgress();

        }, 500);

    } else {

        showCourseCompleted();

    }

}


/* =========================================================
   UPDATE PROGRESS
========================================================= */

function updateProgress() {

    const total =
        allLessons.length;


    const completed =
        completedLessons.length;


    const percentage =
        total === 0
            ? 0
            : Math.round(
                (completed / total) * 100
            );


    /* Progress text */

    const progressText =
        document.querySelector(
            ".nav-progress"
        );

    if (progressText) {

        progressText.textContent =
            `${percentage}% Complete`;

    }


    /* Progress bar */

    const progressBar =
        document.querySelector(
            ".progress-bar-fill"
        );

    if (progressBar) {

        progressBar.style.width =
            `${percentage}%`;

    }


    /* Progress percentage */

    const percentageElement =
        document.querySelector(
            ".progress-percentage"
        );

    if (percentageElement) {

        percentageElement.textContent =
            `${percentage}%`;

    }


    /* Course information */

    const lessonCount =
        document.querySelector(
            ".course-lesson-count"
        );

    if (lessonCount) {

        lessonCount.textContent =
            `${total} Lessons`;

    }

}


/* =========================================================
   UPDATE SIDEBAR
========================================================= */

function updateSidebar() {

    const buttons =
        document.querySelectorAll(
            ".lesson-item"
        );


    buttons.forEach(function (button) {

        const id =
            button.dataset.lessonId;


        button.classList.remove(
            "active"
        );


        button.classList.remove(
            "completed"
        );


        if (
            allLessons[currentLessonIndex] &&
            id ===
            allLessons[currentLessonIndex].id
        ) {

            button.classList.add(
                "active"
            );

        }


        if (
            completedLessons.includes(id)
        ) {

            button.classList.add(
                "completed"
            );


            const icon =
                button.querySelector(
                    ".lesson-icon i"
                );


            if (icon) {

                icon.className =
                    "fas fa-check-circle";

            }

        }

    });

}


/* =========================================================
   SAVE PROGRESS
========================================================= */

function saveProgress() {

    const key =
        "learninghub_progress_" +
        currentCourse.id;


    localStorage.setItem(

        key,

        JSON.stringify(
            completedLessons
        )

    );

}


/* =========================================================
   LOAD PROGRESS
========================================================= */

function loadProgress() {

    const key =
        "learninghub_progress_" +
        currentCourse.id;


    const saved =
        localStorage.getItem(key);


    if (saved) {

        try {

            completedLessons =
                JSON.parse(saved);

        } catch (error) {

            completedLessons = [];

        }

    } else {

        completedLessons = [];

    }


    // Continue from first incomplete lesson
    const firstIncomplete =
        allLessons.findIndex(
            lesson =>
                !completedLessons.includes(
                    lesson.id
                )
        );


    if (firstIncomplete !== -1) {

        currentLessonIndex =
            firstIncomplete;

    } else {

        currentLessonIndex =
            allLessons.length - 1;

    }

}


/* =========================================================
   COURSE COMPLETED
========================================================= */

function showCourseCompleted() {

    const contentElement =
        document.querySelector(
            ".lesson-content"
        );


    if (!contentElement) {

        return;

    }


    contentElement.innerHTML = `

        <div class="course-completed">

            <div class="completed-icon">

                <i class="fas fa-trophy"></i>

            </div>

            <h2>Course Completed!</h2>

            <p>
                Congratulations! You have completed
                ${currentCourse.title}.
            </p>

            <div class="completion-stats">

                <div>

                    <strong>
                        ${allLessons.length}
                    </strong>

                    <span>Lessons</span>

                </div>

                <div>

                    <strong>100%</strong>

                    <span>Completed</span>

                </div>

            </div>

            <button
                class="complete-btn"
                onclick="window.location.href='courses.html'"
            >

                Back to Courses

            </button>

        </div>

    `;

}


/* =========================================================
   ERROR
========================================================= */

function showError(message) {

    const content =
        document.querySelector(
            ".lesson-content"
        );


    if (content) {

        content.innerHTML = `

            <div class="error-message">

                <i class="fas fa-exclamation-triangle"></i>

                <h2>Content Not Found</h2>

                <p>
                    ${message}
                </p>

                <button
                    class="complete-btn"
                    onclick="window.location.href='courses.html'"
                >
                    Back to Courses
                </button>

            </div>

        `;

    }


    console.error(message);

}


/* =========================================================
   ESCAPE HTML
========================================================= */

function escapeHTML(text) {

    if (!text) {

        return "";

    }


    return text

        .replace(/&/g, "&amp;")

        .replace(/</g, "&lt;")

        .replace(/>/g, "&gt;")

        .replace(/"/g, "&quot;")

        .replace(/'/g, "&#039;");

}


/* =========================================================
   MAKE FUNCTIONS AVAILABLE
========================================================= */

window.previousLesson =
    previousLesson;

window.nextLesson =
    nextLesson;

window.completeLesson =
    completeLesson;

window.copyCode =
    copyCode;