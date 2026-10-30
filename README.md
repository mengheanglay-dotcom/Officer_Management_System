# Officer Management System — Laravel 13 + MySQL + Tailwind + Lucide

This folder is an **overlay**: only the project's own files. Drop it on a fresh Laravel 13 app.

## Setup
```bash
composer create-project laravel/laravel officer-system     # or: laravel new officer-system
cp -r officer-system-overlay/* officer-system/             # overwrite when asked
cd officer-system
```
Create database `officer_db` in dbForge Studio, then edit `.env`:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=officer_db
DB_USERNAME=root
DB_PASSWORD=
```
Then:
```bash
php artisan migrate:fresh --seed
npm install
npm run dev          # keep running; in another terminal:
php artisan serve
```
Open http://127.0.0.1:8000 — login as administrator with `admin@gmail.com` / `12345678`, or click Register.

Administrators have full control and can add, edit, and delete officers and departments.
Newly registered accounts are assigned the Officer role automatically and create an
unassigned officer record. Officers can view, search, and filter officer and department
information and can update their own profile. Administrators assign their gender,
position, hire date, and department.

## Structure
| Part | File |
|---|---|
| Migrations | `database/migrations/*_create_departments_table.php`, `*_create_officers_table.php` |
| Models + relations | `app/Models/Department.php`, `Officer.php` |
| Controllers | `AuthController`, `OfficerController`, `DepartmentController` |
| Seeder | `database/seeders/DatabaseSeeder.php` |
| Routes | `routes/web.php` |
| Views | `resources/views/{layouts,auth,officers,departments}` |

Tables: `departments (dept_id PK, dept_name, location, manager_id FK→officers)` and
`officers (officer_id PK, ..., dept_id FK→departments)`.
Search by name + filter by department / gender / sort on both lists.
