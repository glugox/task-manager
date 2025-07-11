# Laravel Task Manager with Projects and Drag-and-Drop Reordering

This is a simple Laravel web application for managing tasks organized by projects. Features include:

- Create and delete tasks
- Drag-and-drop reordering of tasks with automatic priority updates
- Filter tasks by project
- Add new projects inline while creating a task
- Built with Laravel backend, Vue 3 + Inertia.js frontend, and MySQL database

---

## Requirements

- PHP >= 8.1
- Composer
- Node.js >= 18.x
- npm or yarn
- MySQL
- Laravel 12

---

## Setup Instructions

### 1. Clone the repository

```bash
git clone git@github.com:glugox/task-manager.git
cd task-manager
composer install

npm install
cp .env.example .env


```

### 2. Install dependencies
```bash

cd task-manager
composer install
npm install
```


### 3. Configure the environment
Create a copy of the `.env.example` file and rename it to `.env`.
```bash
cp .env.example .env
```

Edit your `.env` file to configure your database connection and other settings.
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_manager_db_2025071101
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password
```

### 4. Generate application key
```bash
php artisan key:generate
```

### 5. Run migrations
```bash
php artisan migrate
```

### 6. Seed the database (optional)
```bash
php artisan db:seed
```

### 7. Start the development server
```bash
php artisan serve
```

### 8. Compile assets
```bash
npm run dev
```

### 9. Access the application
Open your web browser and navigate to `http://localhost:8000`.

    
