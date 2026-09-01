# 🚗 Caripsum

Caripsum is a Laravel-based used-car marketplace demo built as a premium automotive portfolio project. It focuses on the browsing and listing experience: users can view listings, search/filter inventory, create and manage their own car ads, and save favourites through a streamlined frontend.

This project is intentionally scoped as a polished demo app rather than a full production marketplace. The goal is to feel like a realistic premium automotive listing platform while staying maintainable and easy to understand.

---

## Project goal

The app is designed to feel like a modern car marketplace with:

- premium listing cards and landing-page polish
- realistic fake data and a strong visual catalog feel
- advanced inventory search and sorting
- authenticated CRUD for car listings
- AJAX-based favourites/watchlist behaviour
- maintainable Laravel data contracts and normalized naming

---

## Current state of the app

The project now includes:

- authenticated registration, login, logout, and session-based access
- full create/edit/list/delete logic for car listings
- published/unpublished listing flow based on `published_at`
- multi-filter product search with persistent query params
- watchlist/favourite cars with instant AJAX updates
- multi-image upload and gallery handling
- feature specifications for each vehicle
- realistic fake data generation, including remote imagery for visual realism
- normalized model/schema naming after contract cleanup

---

## What changed from the older README

The original README was written earlier in the project and no longer reflects the current scope or architecture. The main updates include:

- the app is a portfolio/demo marketplace, not a full production buyer/seller platform
- the current tech stack is Laravel 12, not Laravel 11
- the project intentionally uses generated realistic data rather than placeholder-only content
- the app includes contract cleanup around feature names and field usage
- `state_id` is not part of the car record schema and was removed from the relevant logic
- the canonical feature names are standardized to snake_case values such as `power_door_locks` and `bluetooth_connectivity`
- the project now documents the real maintainability focus: schema drift prevention and clean model contracts

---

## Core features

### Authentication

- user registration with validation for name, email, password, and phone data
- login and logout using Laravel session auth
- CSRF protection on authenticated actions
- redirect flow for unauthenticated users in protected areas
- flash messages for browsing and form feedback

### Car listings and CRUD

- create a listing with seller details, vehicle attributes, description, price, and publish status
- edit listings with prior values preserved
- my-cars dashboard for the current user
- soft-delete style lifecycle handling for listings
- publish/unpublish behaviour via `published_at`
- ownership checks for editing, updating, and deleting car records

### Advanced search and filtering

The search engine supports multiple filters at once:

- maker
- model
- city
- state
- car type
- fuel type
- year min/max
- price min/max
- mileage min/max
- keyword text search

Sorting supports:

- newest
- oldest
- price ascending
- price descending
- year ascending
- year descending
- mileage ascending
- mileage descending

Only published listings are returned in search results.

### Watchlist / favourites

- authenticated users can add or remove listings from a watchlist
- heart toggle uses the Fetch API for instant UI feedback without full page reloads
- dedicated favourites page displays saved cars with pagination
- guests are redirected to login when attempting to use watchlist actions

### Multi-image support

- sellers can upload multiple images in one submission
- uploaded images are stored via Laravel storage
- images are ordered by position
- the first image is treated as the primary listing image
- editing appends new images without overwriting the existing gallery

### Vehicle specifications

Each car records a set of feature flags, including:

- ABS
- Air conditioning
- Power windows
- Power door locks
- Cruise control
- Bluetooth connectivity
- Remote start
- GPS navigation
- Heated seats
- Climate control
- Rear parking sensors
- Leather seats

---

## Security and data integrity

The app includes a number of important safeguards:

- ownership checks before edit/update/delete actions
- 403 Forbidden responses for unauthorized access attempts
- server-side validation for vehicle records and file uploads
- CSRF protection on sensitive actions
- database-level foreign key relationships and validation rules for IDs
- normalized schema naming to reduce drift between migration, model, validation, and form logic

---

## Architecture decisions

### Portfolio-first scope

This project is intentionally not a production-scale seller management system or enterprise marketplace. Instead, it focuses on the visual and functional experience of a premium automotive marketplace demo.

### Realistic but lightweight data strategy

The app uses generated realistic records rather than static placeholder content. It includes:

- realistic maker/model combinations
- varied price, year, and mileage ranges
- city/state distributions
- remote Unsplash-style image URLs to keep the catalog visually rich without bloating the repository

### Data contract stability is a priority

A major issue in this project was contract drift between:

- migrations
- model fillable values
- validation rules
- controller assumptions
- form field names
- feature naming conventions

This was cleaned up so the app remains easier to maintain and safer for future AI-assisted changes.

---

## Tech stack

| Layer | Technology |
|---|---|
| Framework | Laravel 12 |
| Templating | Blade |
| Database | MySQL with Eloquent ORM |
| Authentication | Laravel Auth sessions |
| Storage | Laravel Storage facade |
| Frontend | HTML, CSS, JavaScript, Fetch API |
| Fonts | Google Fonts |
| Icons | Heroicons / Font Awesome-style usage |
| Build tooling | Vite / npm |
| Seed data | Laravel factories and seeders |

---

## Project structure highlights

```bash
app/
├── Http/
│   ├── Controllers/
│   │   ├── CarController.php
│   │   ├── LoginController.php
│   │   └── SignupController.php
│   └── ...
├── Models/
│   ├── Car.php
│   ├── CarFeatures.php
│   ├── CarImages.php
│   ├── Maker.php
│   ├── Model.php
│   └── User.php
├── View/
│   └── Components/
├── ...

database/
├── factories/
├── migrations/
├── seeders/

resources/views/
├── car/
│   ├── _form.blade.php
│   ├── create.blade.php
│   ├── edit.blade.php
│   ├── search.blade.php
│   ├── show.blade.php
│   └── watchlist.blade.php
├── layouts/
│   ├── app.blade.php
│   ├── auth.blade.php
│   └── base.blade.php
└── ...
```

---

## Database relationships

```text
User ──< Car ──< CarImages
              ├── CarFeatures (1:1)
              ├── Maker
              ├── Model
              ├── CarType
              ├── FuelType
              ├── State
              └── City

User >──< Car (many-to-many via car_favorite — watchlist)
```

---

## Validation and local run notes

### Basic local run

```bash
cd C:\Users\somet\Downloads\Coding\Caripsum
php artisan serve --host 127.0.0.1 --port 8000
```

Then open:

```text
http://127.0.0.1:8000
```

### Fallback if the built-in server is not usable on Windows

```bash
cd C:\Users\somet\Downloads\Coding\Caripsum
php -S 127.0.0.1:8001 -t public
```

Then open:

```text
http://127.0.0.1:8001
```

### Rebuild frontend assets

```bash
cd C:\Users\somet\Downloads\Coding\Caripsum
npm install
npm run build
```

### Re-seed the database

```bash
cd C:\Users\somet\Downloads\Coding\Caripsum
php artisan migrate:fresh --seed
```

---

## Current project direction

This app remains a demo-focused automotive marketplace rather than a large production platform. The strongest next improvements are still UX-focused ones that improve perception and conversion:

- richer homepage merchandising
- stronger detail-page trust signals
- better mobile layout polish
- improved watchlist/compare interactions
- refined search result UX
- stronger onboarding and listing presentation

The project should stay visually premium and believable without broadening into unrelated production features unless the scope changes.

---

## Important project notes

1. This is a demo portfolio marketplace, not a full-scale production vehicle marketplace.
2. Fake but realistic data is intentional.
3. Remote images are used to keep the catalog visually rich without large local asset folders.
4. Data contract consistency matters more than adding more low-risk features.
5. `state_id` is not part of the canonical car record schema and should not be reintroduced without a deliberate schema change.
6. Feature names should remain normalized and snake_case.

---

## Suggested next upgrades

If the project continues in the same direction, the best follow-up work is:

- feature-rich home page sections
- better empty-state and filter-summary UX
- enhanced car detail page trust-building sections
- stronger favourites interaction experience
- sticky mobile filters and result polish
- more premium merchandising and landing-page sections

These improve the current demo app without drifting away from its portfolio-first goal.
