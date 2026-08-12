<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alexander's First PHP Program</title>
</head>
<body>
    <header>
        <h1>My First PHP Program</h1>
    </header>

    <main>
        <section>
            <h2>Student Information</h2>

            <?php
            /*
             * Student: Alexander Hunt
             * Course: CSD-440 Server-Side Scripting
             * File: AlexanderFirstProgram.php
             * Date: August 12, 2026
             * Purpose: Demonstrate PHP embedded within a standard HTML document.
             */

            // Store the student and course information for display.
            $studentName = "Alexander Hunt";
            $courseName = "CSD-440 Server-Side Scripting";

            // Escape dynamic values before including them in HTML output.
            echo "<p>Hello! My name is "
                . htmlspecialchars($studentName, ENT_QUOTES, "UTF-8")
                . ".</p>";
            echo "<p>This program was created for "
                . htmlspecialchars($courseName, ENT_QUOTES, "UTF-8")
                . ".</p>";
            ?>
        </section>

        <section>
            <h2>Program Status</h2>

            <?php
            // Use a second PHP snippet to produce additional visible output.
            $isWorking = true;

            if ($isWorking) {
                echo "<p>The PHP program is functioning correctly.</p>";
            } else {
                echo "<p>The PHP program is not functioning correctly.</p>";
            }
            ?>
        </section>
    </main>

    <footer>
        <p>Created for CSD-440.</p>
    </footer>
</body>
</html>