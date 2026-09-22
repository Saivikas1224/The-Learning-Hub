/* =========================================================
   THE LEARNING HUB
   COURSE LEARNING SYSTEM
========================================================= */

document.addEventListener("DOMContentLoaded", function () {

    /* =====================================================
       GET COURSE ID
    ===================================================== */

    const urlParams = new URLSearchParams(window.location.search);

    let courseId =
        urlParams.get("course") ||
        urlParams.get("course_id") ||
        urlParams.get("id");


    /* =====================================================
       COURSE ID FROM LOCAL STORAGE
    ===================================================== */

    if (!courseId) {

        courseId =
            localStorage.getItem("selectedCourse") ||
            localStorage.getItem("selectedCourseId");

    }


    /* =====================================================
       NORMALIZE COURSE ID
    ===================================================== */

    if (courseId) {

        courseId = courseId
            .toLowerCase()
            .trim()
            .replace(/\s+/g, "-");

    }


    /* =====================================================
       GET COURSE
    ===================================================== */

    const course = getCourse(courseId);


    if (!course) {

        showCourseError();

        return;

    }


    /* =====================================================
       PAGE ELEMENTS
    ===================================================== */

    const courseTitle =
        document.querySelector(".nav-course-title");

    const courseIcon =
        document.querySelector(".course-icon");

    const courseInfoTitle =
        document.querySelector(".course-info h2");

    const courseDescription =
        document.querySelector(".course-info p");

    const modulesContainer =
        document.querySelector(".modules-container");


    /* =====================================================
       COURSE INFORMATION
    ===================================================== */

    if (courseTitle) {

        courseTitle.textContent =
            course.title;

    }


    if (courseInfoTitle) {

        courseInfoTitle.textContent =
            course.title;

    }


    if (courseDescription) {

        courseDescription.textContent =
            course.description;

    }


    if (courseIcon) {

        courseIcon.innerHTML =
            `<i class="${course.icon}"></i>`;

    }


    /* =====================================================
       COURSE STATE
    ===================================================== */

    const storageKey =
        "learningHubProgress_" + course.id;


    let completedLessons =
        JSON.parse(
            localStorage.getItem(storageKey) || "[]"
        );


    let currentLessonIndex = 0;


    const allLessons =
        getAllLessons(course);


    /* =====================================================
       CREATE MODULES
    ===================================================== */

    renderModules();


    /* =====================================================
       SHOW FIRST LESSON
    ===================================================== */

    if (allLessons.length > 0) {

        showLesson(0);

    }


    /* =====================================================
       MODULE RENDERING
    ===================================================== */

    function renderModules() {

        if (!modulesContainer) {

            console.error(
                "modules-container not found."
            );

            return;

        }


        modulesContainer.innerHTML = "";


        course.modules.forEach(
            (module, moduleIndex) => {

                const moduleElement =
                    document.createElement("div");

                moduleElement.className =
                    "course-module";


                /* -----------------------------------------
                   MODULE HEADER
                ----------------------------------------- */

                const moduleHeader =
                    document.createElement("div");

                moduleHeader.className =
                    "module-header";


                moduleHeader.innerHTML = `

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


                /* -----------------------------------------
                   LESSON CONTAINER
                ----------------------------------------- */

                const lessonsContainer =
                    document.createElement("div");

                lessonsContainer.className =
                    "lessons-container";


                module.lessons.forEach(
                    (lesson, lessonIndex) => {

                        const lessonButton =
                            document.createElement("button");

                        lessonButton.className =
                            "lesson-item";


                        const globalIndex =
                            getGlobalLessonIndex(
                                moduleIndex,
                                lessonIndex
                            );


                        if (
                            completedLessons.includes(
                                lesson.id
                            )
                        ) {

                            lessonButton.classList.add(
                                "completed"
                            );

                        }


                        lessonButton.innerHTML = `

                            <span class="lesson-icon">

                                ${
                                    completedLessons.includes(
                                        lesson.id
                                    )
                                    ?
                                    '<i class="fas fa-check-circle"></i>'
                                    :
                                    '<i class="far fa-circle"></i>'
                                }

                            </span>

                            <span class="lesson-name">
                                ${lesson.title}
                            </span>

                        `;


                        lessonButton.addEventListener(
                            "click",
                            function () {

                                showLesson(globalIndex);

                            }
                        );


                        lessonsContainer.appendChild(
                            lessonButton
                        );

                    }
                );


                /* -----------------------------------------
                   MODULE TOGGLE
                ----------------------------------------- */

                moduleHeader.addEventListener(
                    "click",
                    function () {

                        moduleElement.classList.toggle(
                            "expanded"
                        );

                    }
                );


                moduleElement.appendChild(
                    moduleHeader
                );

                moduleElement.appendChild(
                    lessonsContainer
                );

                modulesContainer.appendChild(
                    moduleElement
                );

            }
        );


        /* Open first module */

        const firstModule =
            modulesContainer.querySelector(
                ".course-module"
            );

        if (firstModule) {

            firstModule.classList.add(
                "expanded"
            );

        }

    }


    /* =====================================================
       GET ALL LESSONS
    ===================================================== */

    function getAllLessons(course) {

        const lessons = [];


        course.modules.forEach(
            (module, moduleIndex) => {

                module.lessons.forEach(
                    (lesson, lessonIndex) => {

                        lessons.push({

                            lesson: lesson,

                            module: module,

                            moduleIndex:
                                moduleIndex,

                            lessonIndex:
                                lessonIndex

                        });

                    }
                );

            }
        );


        return lessons;

    }


    /* =====================================================
       GET GLOBAL LESSON INDEX
    ===================================================== */

    function getGlobalLessonIndex(
        moduleIndex,
        lessonIndex
    ) {

        let index = 0;


        for (
            let i = 0;
            i < moduleIndex;
            i++
        ) {

            index +=
                course.modules[i].lessons.length;

        }


        index += lessonIndex;


        return index;

    }


    /* =====================================================
       SHOW LESSON
    ===================================================== */

    function showLesson(index) {

        if (
            index < 0 ||
            index >= allLessons.length
        ) {

            return;

        }


        currentLessonIndex =
            index;


        const lessonData =
            allLessons[index];


        const lesson =
            lessonData.lesson;

        const module =
            lessonData.module;


        /* -----------------------------------------
           PAGE ELEMENTS
        ----------------------------------------- */

        const breadcrumb =
            document.querySelector(
                ".lesson-breadcrumb"
            );

        const lessonNumber =
            document.querySelector(
                ".lesson-number"
            );

        const lessonTitle =
            document.querySelector(
                ".lesson-title"
            );

        const lessonContent =
            document.querySelector(
                ".lesson-content"
            );


        /* -----------------------------------------
           BREADCRUMB
        ----------------------------------------- */

        if (breadcrumb) {

            breadcrumb.textContent =
                `${course.title} / ${module.title}`;

        }


        /* -----------------------------------------
           LESSON NUMBER
        ----------------------------------------- */

        if (lessonNumber) {

            lessonNumber.textContent =
                `Lesson ${index + 1} of ${allLessons.length}`;

        }


        /* -----------------------------------------
           TITLE
        ----------------------------------------- */

        if (lessonTitle) {

            lessonTitle.textContent =
                lesson.title;

        }


        /* -----------------------------------------
           CONTENT
        ----------------------------------------- */

        if (lessonContent) {

            lessonContent.innerHTML =
                createLessonContent(lesson);

        }


        /* -----------------------------------------
           ACTIVE LESSON
        ----------------------------------------- */

        document
            .querySelectorAll(".lesson-item")
            .forEach(
                item => {

                    item.classList.remove(
                        "active"
                    );

                }
            );


        const lessonButtons =
            document.querySelectorAll(
                ".lesson-item"
            );


        if (lessonButtons[index]) {

            lessonButtons[index]
                .classList.add("active");

        }


        /* -----------------------------------------
           NAVIGATION
        ----------------------------------------- */

        updateNavigation();


        /* -----------------------------------------
           PROGRESS
        ----------------------------------------- */

        updateProgress();


        /* -----------------------------------------
           SCROLL TOP
        ----------------------------------------- */

        window.scrollTo({
            top: 0,
            behavior: "smooth"
        });

    }


    /* =====================================================
       CREATE LESSON CONTENT
    ===================================================== */

    function createLessonContent(lesson) {

        let html = "";


        /* -----------------------------------------
           EXPLANATION
        ----------------------------------------- */

        if (lesson.content) {

            html += `

                <div class="explanation-section">

                    ${lesson.content}

                </div>

            `;

        }


        /* -----------------------------------------
           CODE
        ----------------------------------------- */

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
                            onclick="copyLessonCode(this)"
                        >

                            <i class="fas fa-copy"></i>

                            Copy

                        </button>

                        <pre><code>${escapeHTML(
                            lesson.code
                        )}</code></pre>

                    </div>

                </div>

            `;

        }


        /* -----------------------------------------
           OUTPUT
        ----------------------------------------- */

        if (lesson.output) {

            html += `

                <div class="output-section">

                    <h3>

                        <i class="fas fa-terminal"></i>

                        Output

                    </h3>

                    <div class="output-box">

                        ${escapeHTML(
                            lesson.output
                        )}

                    </div>

                </div>

            `;

        }


        /* -----------------------------------------
           PRACTICE
        ----------------------------------------- */

        if (lesson.practice) {

            html += `

                <div class="practice-section">

                    <div class="practice-icon">

                        <i class="fas fa-lightbulb"></i>

                    </div>

                    <div>

                        <h3>
                            Practice Question
                        </h3>

                        <p>
                            ${lesson.practice}
                        </p>

                    </div>

                </div>

            `;

        }


        /* -----------------------------------------
           NAVIGATION
        ----------------------------------------- */

        html += `

            <div class="lesson-navigation">

                <button
                    class="nav-lesson-btn"
                    id="previousLessonBtn"
                >

                    <i class="fas fa-arrow-left"></i>

                    Previous

                </button>


                <button
                    class="complete-btn"
                    id="completeLessonBtn"
                >

                    <i class="fas fa-check"></i>

                    Mark as Complete

                </button>


                <button
                    class="nav-lesson-btn"
                    id="nextLessonBtn"
                >

                    Next

                    <i class="fas fa-arrow-right"></i>

                </button>

            </div>

        `;


        return html;

    }


    /* =====================================================
       NAVIGATION
    ===================================================== */

    function updateNavigation() {

        const previousButton =
            document.querySelector(
                "#previousLessonBtn"
            );

        const nextButton =
            document.querySelector(
                "#nextLessonBtn"
            );

        const completeButton =
            document.querySelector(
                "#completeLessonBtn"
            );


        /* Previous */

        if (previousButton) {

            previousButton.disabled =
                currentLessonIndex === 0;


            previousButton.onclick =
                function () {

                    if (
                        currentLessonIndex > 0
                    ) {

                        showLesson(
                            currentLessonIndex - 1
                        );

                    }

                };

        }


        /* Next */

        if (nextButton) {

            nextButton.disabled =
                currentLessonIndex ===
                allLessons.length - 1;


            nextButton.onclick =
                function () {

                    if (
                        currentLessonIndex <
                        allLessons.length - 1
                    ) {

                        showLesson(
                            currentLessonIndex + 1
                        );

                    }

                };

        }


        /* Complete */

        if (completeButton) {

            const currentLesson =
                allLessons[
                    currentLessonIndex
                ].lesson;


            const isCompleted =
                completedLessons.includes(
                    currentLesson.id
                );


            if (isCompleted) {

                completeButton.classList.add(
                    "completed"
                );

                completeButton.innerHTML = `

                    <i class="fas fa-check-circle"></i>

                    Completed

                `;

            } else {

                completeButton.classList.remove(
                    "completed"
                );

                completeButton.innerHTML = `

                    <i class="fas fa-check"></i>

                    Mark as Complete

                `;

            }


            completeButton.onclick =
                function () {

                    completeCurrentLesson();

                };

        }

    }


    /* =====================================================
       COMPLETE LESSON
    ===================================================== */

    function completeCurrentLesson() {

        const lesson =
            allLessons[
                currentLessonIndex
            ].lesson;


        if (
            !completedLessons.includes(
                lesson.id
            )
        ) {

            completedLessons.push(
                lesson.id
            );


            localStorage.setItem(
                storageKey,
                JSON.stringify(
                    completedLessons
                )
            );

        }


        renderModules();

        showLesson(
            currentLessonIndex
        );

        updateProgress();


        /* -----------------------------------------
           COURSE COMPLETION
        ----------------------------------------- */

        if (
            completedLessons.length >=
            allLessons.length
        ) {

            showCourseCompleted();

        }

    }


    /* =====================================================
       PROGRESS
    ===================================================== */

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


        const progressText =
            document.querySelector(
                ".progress-info span:last-child"
            );


        const progressFill =
            document.querySelector(
                ".progress-bar-fill"
            );


        const navProgress =
            document.querySelector(
                ".nav-progress"
            );


        if (progressText) {

            progressText.textContent =
                `${percentage}%`;

        }


        if (progressFill) {

            progressFill.style.width =
                `${percentage}%`;

        }


        if (navProgress) {

            navProgress.textContent =
                `${percentage}% Complete`;

        }

    }


    /* =====================================================
       COURSE COMPLETED
    ===================================================== */

    function showCourseCompleted() {

        const lessonArea =
            document.querySelector(
                ".lesson-area"
            );


        if (!lessonArea) {

            return;

        }


        lessonArea.innerHTML = `

            <div class="course-completed">

                <div class="completed-icon">

                    <i class="fas fa-trophy"></i>

                </div>


                <h2>
                    Course Completed!
                </h2>


                <p>
                    Congratulations! You have completed
                    the ${course.title} course.
                </p>


                <div class="completion-stats">

                    <div>

                        <strong>
                            ${allLessons.length}
                        </strong>

                        <span>
                            Lessons
                        </span>

                    </div>


                    <div>

                        <strong>
                            100%
                        </strong>

                        <span>
                            Completed
                        </span>

                    </div>

                </div>


                <button
                    class="complete-btn"
                    onclick="history.back()"
                >

                    <i class="fas fa-arrow-left"></i>

                    Back to My Courses

                </button>

            </div>

        `;

    }


    /* =====================================================
       ERROR
    ===================================================== */

    function showCourseError() {

        const lessonArea =
            document.querySelector(
                ".lesson-area"
            );


        if (!lessonArea) {

            return;

        }


        lessonArea.innerHTML = `

            <div class="error-message">

                <i class="fas fa-exclamation-triangle"></i>

                <h2>
                    Course Not Found
                </h2>

                <p>
                    We couldn't find the requested course.
                </p>

                <button
                    class="complete-btn"
                    onclick="history.back()"
                >

                    <i class="fas fa-arrow-left"></i>

                    Go Back

                </button>

            </div>

        `;

    }

});


/* =========================================================
   COPY CODE
========================================================= */

function copyLessonCode(button) {

    const container =
        button.closest(".code-container");


    if (!container) {

        return;

    }


    const code =
        container.querySelector("code");


    if (!code) {

        return;

    }


    const text =
        code.textContent;


    navigator.clipboard.writeText(text)
        .then(function () {

            const original =
                button.innerHTML;


            button.innerHTML =
                `<i class="fas fa-check"></i> Copied!`;


            setTimeout(function () {

                button.innerHTML =
                    original;

            }, 1500);

        })
        .catch(function () {

            alert(
                "Unable to copy code."
            );

        });

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