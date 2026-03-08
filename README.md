# Blog Eterna – Laravel 12 & Vue 3 Fullstack Blog System

## 📌 Project Description

Blog Eterna is a fullstack blog management system developed using **Laravel 12**, **Sanctum API authentication**, and **Vue 3 Composition API**.

The system is designed with **policy-based authorization**, **role middleware security**, and **scalable architecture suitable for real-world production environments**.

The frontend is connected directly to the API without using Inertia.

---

## 🚀 Technologies Used

### Backend

- Laravel 12
- Laravel Sanctum (API Authentication)
- Policy-based Authorization
- Soft Delete Architecture
- Scheduler Job System
- Spatie Media Library (Image Upload)
- Activity Log Integration (Prepared architecture)
- Notification Foundation Structure

### Frontend

- Vue 3 Composition API
- TailwindCSS
- Axios Interceptors
- Vue Router

---

## 👤 User Roles

System supports three user roles:

- Admin
- Editor (Writer)
- User (Reader)

Authorization is handled using middleware and policy layers.

---

## 🔐 Authentication Features

- User registration with:
    - First name
    - Last name
    - Phone number (masked input supported)
    - Email
    - Password

- Login via email or phone.

- JWT-style token authentication using Laravel Sanctum.

---

## 📝 Blog Post Features

- Editor can create and manage own posts.
- Admin has full system access.

Post model includes:

- Title
- Content
- Slug (auto generated)
- Cover image upload
- Publication status (draft / published)
- Published date
- View counter
- Reaction counter
- Smart scoring system

### ⭐ Featured Post Algorithm

Featured posts are calculated automatically using scoring logic based on:

- Comment activity in last 24 hours
- Comment activity in last 7 days
- Total comment count
- Publication age coefficient

Only published and non-soft-deleted posts are included.

Top 5 scoring posts are exposed via dedicated API endpoint.

---

## 💬 Comment System

- All authenticated users can comment.
- Admin comments are automatically approved.
- Comment moderation available for admin.
- Comment approval triggers notification foundation logic.

---

## 🗂 Category Management

- Admin can create, update, and delete categories.
- Posts can belong to multiple categories.

---

## 📊 Scheduler Automation

System includes scheduled background job:

- Automatically deletes published posts that have not received comments for 7 days.

Scheduler is prepared using Laravel scheduler configuration.

---

## 📁 Media Upload System

Image uploads are handled using:

- Spatie Laravel Media Library

Uploaded images are stored in:

```
storage/app/public
```

---

## 🔒 Security Architecture

- Sanctum token authentication
- Role-based middleware
- Policy-based resource authorization
- Query-level data filtering

---

## 🛠 Installation

```bash
composer install
npm install
php artisan migrate --seed
php artisan storage:link
php artisan serve
npm run dev
```

---

## 📌 API Testing

Postman collection should be used for testing endpoints.

Main endpoints:

```
/api/register
/api/login
/api/posts
/api/categories
/api/comments
/api/users
/api/posts/featured
```

---

## ⚠️ Requirements Coverage

✅ Full CRUD blog management
✅ Role-based authorization
✅ Featured post scoring system
✅ Soft delete support
✅ Scheduler automation
✅ Media upload system
✅ Notification architecture preparation

---

## 👨‍💻 Author

Developed as a fullstack demonstration project using Laravel and Vue 3.

## API Documentation

You can view the API documentation here:

/docs/api-doc.html

## Postman Collection

Postman collection for testing endpoints:

/docs/Eterna.postman_collection.json
