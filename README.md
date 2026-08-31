# CSD-440: Server-Side Scripting

PHP coursework for CSD-440 done by Alexander. Each module is a self-contained HTML document with embedded PHP, designed to run through XAMPP Apache.

## Run Locally

1. Start Apache from the XAMPP Control Panel.
2. Open a module page at `http://localhost/csd-440/module-N/filename.php`.

Examples:

- `http://localhost/csd-440/module-5/AlexanderCustomers.php`
- `http://localhost/csd-440/module-6/AlexanderMyInteger.php`

To check PHP syntax from PowerShell, run:

```powershell
& C:\xampp\php\php.exe -l .\module-5\AlexanderCustomers.php
& C:\xampp\php\php.exe -l .\module-6\AlexanderMyInteger.php
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
