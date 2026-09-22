# CSD-440: Server-Side Scripting

PHP coursework for CSD-440 done by Alexander. Each module is a self-contained HTML document with embedded PHP, designed to run through XAMPP Apache.

## Run Locally

1. Start Apache from the XAMPP Control Panel.
2. Open a module page at `http://localhost/csd-440/module-N/filename.php`.

Examples:

- `http://localhost/csd-440/module-5/AlexanderCustomers.php`
- `http://localhost/csd-440/module-6/AlexanderMyInteger.php`
- `http://localhost/csd-440/module-7/AlexanderForm.php`
- `http://localhost/csd-440/module-8/AlexanderCreateTable.php`
- `http://localhost/csd-440/module-8/AlexanderPopulateTable.php`
- `http://localhost/csd-440/module-8/AlexanderQueryTable.php`
- `http://localhost/csd-440/module-9/AlexanderIndex.php`
- `http://localhost/csd-440/module-9/AlexanderQuery.php`
- `http://localhost/csd-440/module-9/AlexanderForms.php`
- `http://localhost/csd-440/module-10/AlexanderJSON.php`

Before using Module 8 for the first time, create the local database and student account. The script reads the MySQL root password from `MYSQL_ROOT_PASSWORD` and does not store it in the repository:

```powershell
& C:\Users\Alexa\AppData\Local\Python\pythoncore-3.14-64\python.exe .\module-8\scripts\setup-database.py
& C:\Users\Alexa\AppData\Local\Python\pythoncore-3.14-64\python.exe .\module-8\scripts\print-tables.py
```

To check PHP syntax from PowerShell, run:

```powershell
& C:\xampp\php\php.exe -l .\module-5\AlexanderCustomers.php
& C:\xampp\php\php.exe -l .\module-6\AlexanderMyInteger.php
& C:\xampp\php\php.exe -l .\module-7\AlexanderForm.php
& C:\xampp\php\php.exe -l .\module-8\AlexanderCreateTable.php
& C:\xampp\php\php.exe -l .\module-8\AlexanderDropTable.php
& C:\xampp\php\php.exe -l .\module-8\AlexanderPopulateTable.php
& C:\xampp\php\php.exe -l .\module-8\AlexanderQueryTable.php
& C:\xampp\php\php.exe -l .\module-9\AlexanderIndex.php
& C:\xampp\php\php.exe -l .\module-9\AlexanderQuery.php
& C:\xampp\php\php.exe -l .\module-9\AlexanderForms.php
& C:\xampp\php\php.exe -l .\module-10\AlexanderJSON.php
```

## Project Conventions

- Include a complete HTML document with semantic `header`, `main`, `section`, and `footer` elements.
- Use scalar type declarations for PHP function and class method parameters and return values.
- Escape dynamic values rendered into HTML with `htmlspecialchars($value, ENT_QUOTES, "UTF-8")`.
- Include a concise assignment metadata comment with the student, course, filename, date, and purpose.

## Modules

| Module | File | Topic |
| --- | --- | --- |
| 1 | `module-1/AlexanderFirstProgram.php` | Introductory embedded PHP and student information |
| 2 | `module-2/AlexanderTable2.php` | 10 by 10 random number table |
| 3 | `module-3/AlexanderFunctions.php` | Reusable random-number addition function |
| 3 | `module-3/AlexanderTable3.php` | 10 by 10 table of sums using the Module 3 function |
| 4 | `module-4/AlexanderPalindrome.php` | Palindrome checking with visible test results |
| 5 | `module-5/AlexanderCustomers.php` | Customer array and field-based record searches |
| 6 | `module-6/AlexanderMyInteger.php` | `MyInteger` class, integer checks, getter, and setter |
| 7 | `module-7/AlexanderForm.php` | Seven-field form with server-side validation and formatted results |
| 8 | `module-8/scripts/setup-database.py` | Re-runnable local MySQL database and student-account setup |
| 8 | `module-8/scripts/print-tables.py` | Print all tables in the `baseball_01` database |
| 8 | `module-8/AlexanderCreateTable.php` | Create the `book_collection` table |
| 8 | `module-8/AlexanderDropTable.php` | Drop the `book_collection` table |
| 8 | `module-8/AlexanderPopulateTable.php` | Populate five book collection records |
| 8 | `module-8/AlexanderQueryTable.php` | Query and display book collection records |
| 9 | `module-9/AlexanderIndex.php` | Navigation page for Module 9 and copied Module 8 utilities |
| 9 | `module-9/AlexanderQuery.php` | Search book records by title, author, or genre |
| 9 | `module-9/AlexanderForms.php` | Add a validated book record with MySQLi |
| 10 | `module-10/AlexanderJSON.php` | Eight-field book form with JSON output |
