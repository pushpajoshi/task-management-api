# Task Management API

A Laravel 10 API with authentication, role-based access, tasks, comments, and reporting.

---

## Setup Instructions

1. Clone the repository:
   git clone <repo-url>
   cd <project-folder>

2. Install dependencies:
  composer install
  npm install
  npm run dev

3. Configure .env:
  APP_NAME=TaskManagementAPI
  APP_URL=http://localhost
  DB_CONNECTION=mysql
  DB_HOST=127.0.0.1
  DB_PORT=3306
  DB_DATABASE=task_management_api
  DB_USERNAME=root
  DB_PASSWORD=

4. Run migrations & seeders:
  php artisan migrate --seed

5. Start the server:
  php artisan serve

## Test Credentials

Use these accounts to test the API via Postman:

- **Admin**
  - Email: admin@yopmail.com
  - Password: password123

- **User**
  - Email: user@yopmail.com
  - Password: password123


  ## Postman Collection

A Postman collection is provided to test all API endpoints.

### How to Import:

1. Open Postman.
2. Click **File → Import** (or use the Import button in Postman).
3. Select the file `TaskManagementAPI.postman_collection.json` from the project root.
4. Click **Import**.
5. Set the environment variable `base_url` (e.g., `http://localhost:8000`) if used in requests.

### Notes:

- The collection contains all endpoints:
  - Authentication (register, login, logout)
  - Tasks (CRUD)
  - Comments
  - Reports and other API endpoints
- Make sure the server is running:  
  ```bash
  php artisan serve

## for Email Notification setting (using gmail)
1. Go to your Google Account Security Settings
2. Under Signing in to Google click App passwords
3. Choose Mail → Other (Custom name) → enter project name
4. Copy the 16-character password generated — use it in your .env

## Update details in your .env file 
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_generated_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your_email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"

## for Email Notification setting (using mailtrap)

1. Create an account / sign in on mailtrap
2. Open your Sandbox / Inbox
3. Open the Integration=> smtp tab
4. on code sample select php: laravel9+ and coppy that credentials and use it in .env file













