# 🚗 Caripsum

A full-stack **car marketplace web application** built with Laravel, where users can browse, list, edit, and save their favourite car listings — all through a clean and responsive interface.

---

## Overview

CarSellingApp is a feature-complete marketplace platform for buying and selling used cars. It covers the full lifecycle of a car listing: from user registration and authenticated CRUD operations, to a powerful multi-filter search engine and a real-time AJAX watchlist — all built on top of a well-structured relational database.

---

## Note

This project is a practice build and uses placeholder data for demonstration. While the UI is inspired by a Codeholic (YouTube) course, I have expanded and improved the functionality—specifically the advanced search logic and back-end structure—well beyond the original material

---

## ✨ Features

### 🔐 Authentication System

- **User Registration** with full validation (email uniqueness, password confirmation, phone number).
- **Login / Logout** using Laravel's built-in `Auth` facade with session management and CSRF protection.
- **Session flash messages** for login, logout, registration success, and form submission feedback.
- **Old input retention** on validation failure — users don't lose their typed data on form errors.
- Auth pages (Login, Signup) use a dedicated layout that hides the global header and footer for a focused UX.

### 🚘 Car Listings — Full CRUD

- **Create a listing** with a comprehensive form: Maker, Model, Year, Price, VIN, Mileage, Fuel Type, Car Type, State/Region, City, Address, Phone, Description, and a Published toggle.
- **Edit a listing** with all fields pre-populated, including feature checkboxes — data is always remembered.
- **Soft Delete** — cars are not permanently removed from the database, allowing for data recovery.
- **My Cars Dashboard** — a paginated table of a user's own listings with Edit and Delete actions.
- **Publish/Unpublish** — sellers control whether their listing is publicly visible by toggling a `published_at` timestamp.
- **DRY Form Architecture** — Create and Edit views share a single `_form.blade.php` partial, eliminating code duplication.

### 🔎 Advanced Search & Filtering

The search engine supports **9 simultaneous filter parameters**, all chainable:
| Filter | Type |
|---|---|
| Maker | Exact match |
| Model | Exact match |
| State / Region | Exact match |
| City | Exact match |
| Car Type | Exact match |
| Fuel Type | Exact match |
| Year (from / to) | Range |
| Price (from / to) | Range |
| Max Mileage | Upper bound |

- Results are **sorted** by Newest, Price ↑, or Price ↓.
- Results use **cursor-aware pagination** — filter parameters persist across pages in the URL query string.
- Only **published** listings are shown in search results.

### ❤️ AJAX Watchlist (Favourites)

- Authenticated users can **save or remove** any car from their watchlist by clicking a heart icon.
- The toggle fires a `POST` request via the **Fetch API** — the heart icon updates instantly **without a full page reload**.
- A dedicated **My Favourite Cars** page displays the user's saved listings with full pagination.
- Guest users clicking the heart are redirected to the Login page.

### 🖼️ Multi-Image Upload

- Sellers can upload **multiple images** per listing in a single form submission.
- Images are stored using **Laravel's `Storage` facade** (`storage/app/public`), following framework best practices.
- Images are positioned sequentially (`position` column), and the **primary image** (position 1) is used as the listing thumbnail throughout the app.
- On **edit**, new images are **appended** to the existing gallery without overwriting.

### 📋 Car Specifications

Each listing tracks **12 feature checkboxes**: ABS, Air Conditioning, Power Windows, Power Door Locks, Cruise Control, Bluetooth Connectivity, Remote Start, GPS Navigation, Heated Seats, Climate Control, Rear Parking Sensors, and Leather Seats.

### 🔏 Security

- **IDOR (Insecure Direct Object Reference) protection** — the `edit`, `update`, and `destroy` methods enforce ownership checks (`$car->user_id !== auth()->id()`) and return a `403 Forbidden` response for unauthorized attempts.
- **CSRF protection** on all `POST`, `PUT`, and `DELETE` requests via Laravel's `@csrf` directive.
- **Logout via POST** — prevents CSRF-based logout attacks.
- **Server-side validation** on all form inputs with `exists:table,id` rules to prevent foreign key injection.
- **Auth middleware** protects all write routes (`car.create`, `car.store`, `car.edit`, `car.update`, `car.destroy`, watchlist).

### 🧩 Architecture & Code Quality

- **Blade Component System** — modular layouts (`BaseLayout`, `AppLayout`, `AuthLayout`) handle conditional header/footer rendering via component properties.
- **Self-contained `SearchForm` component** — fetches its own data (Makers, Models, Cities, etc.) in the component class, making it independently reusable on any page.
- **Eager loading** of all relationships (`with([...])`) on every listing query to prevent N+1 database queries.
- **Reusable `<x-car-specification>` component** for rendering each feature row in the car detail view.
- **Phone number masking** using `Str::mask()` on the car detail page — the last digits are hidden until the user taps "view full number."
- **Sticky footer** implemented via CSS Flexbox on the root layout, ensuring the footer always sits at the bottom of the viewport.

---

## 🛠️ Tech Stack

| Layer                | Technology                                            |
| -------------------- | ----------------------------------------------------- |
| **Framework**        | Laravel 11                                            |
| **Templating**       | Blade (components, partials, layouts)                 |
| **Database**         | MySQL with Eloquent ORM                               |
| **Authentication**   | Laravel Auth (`auth()` helpers + session guards)      |
| **File Storage**     | Laravel Storage Facade (local, symlinked to `public`) |
| **Frontend**         | Vanilla HTML, CSS, JavaScript (Fetch API)             |
| **Fonts**            | Google Fonts — Ubuntu                                 |
| **Icons**            | Heroicons (inline SVG) + Font Awesome 5               |
| **Animations**       | ScrollReveal.js                                       |
| **Database Seeding** | Eloquent Factories + custom `DatabaseSeeder`          |

---

## 📁 Project Structure Highlights

```
app/
├── Http/Controllers/
│   ├── CarController.php       # Full CRUD + Search + Watchlist
│   ├── LoginController.php     # Auth: login, logout
│   └── SignupController.php    # Auth: registration
├── Models/
│   ├── Car.php                 # SoftDeletes, all relationships
│   ├── CarFeatures.php         # One-to-one feature flags
│   └── CarImages.php           # One-to-many image gallery
├── View/Components/
│   ├── SearchForm.php          # Self-contained search bar component
│   ├── BaseLayout.php          # Root HTML shell
│   └── AppLayout.php           # Main authenticated layout

resources/views/
├── car/
│   ├── _form.blade.php         # Shared DRY create/edit form
│   ├── create.blade.php
│   ├── edit.blade.php
│   ├── show.blade.php
│   ├── search.blade.php
│   └── watchlist.blade.php
└── layouts/
    ├── base.blade.php          # Global HTML shell (sticky footer)
    ├── auth.blade.php          # Auth page layout (no header/footer)
    └── header.blade.php        # Conditional auth/guest navbar
```

---

## 🗄️ Database Relationships

```
User ──< Car ──< CarImages
              ├── CarFeatures (1:1)
              ├── Maker
              ├── Model
              ├── CarType
              ├── FuelType
              ├── State
              └── City

User >──< Car (many-to-many via car_favorite — Watchlist)
```
