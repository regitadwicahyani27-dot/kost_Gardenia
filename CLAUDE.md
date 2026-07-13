# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

**Kos Putri Gardenia** — an online boarding house ("kos putri") information and booking system built with Laravel 12. The UI and codebase naming use Indonesian language.

## Common Commands

```bash
# Local development (all services concurrently)
composer dev

# Or run individually:
php artisan serve                   # Laravel dev server
npm run dev                         # Vite dev server (CSS/JS HMR)
php artisan queue:listen --tries=1  # Queue worker
php artisan pail --timeout=0        # Real-time log viewer

# Database
php artisan migrate                 # Run migrations
php artisan migrate:fresh --seed    # Reset and re-seed (creates admin account)
php artisan db:seed                 # Seed only

# Build for production
npm run build                       # Compile Vite assets

# Testing
php artisan test                    # Run PHPUnit tests
vendor/bin/phpunit --filter=TestName  # Run a single test

# Code quality
vendor/bin/pint                     # PHP code formatter (Laravel Pint)

# Other
php artisan storage:link            # Create public storage symlink
php artisan tinker                  # Interactive REPL
```

Default admin credentials (from seeder): `admin@gardenia.com` / `admin123`

## Architecture

Standard Laravel 12 MVC with Blade server-side rendering, Tailwind CSS + Alpine.js on the frontend, and Axios for AJAX (used in the booking flow).

### Route Organization (`routes/web.php`)

Routes are split into three access levels:

| Group | Prefix | Middleware | Purpose |
|-------|--------|-----------|---------|
| Public | `/` | none | Home, room listing/detail, "Tentang" page |
| User | `/user` | `auth` | Dashboard, booking, payment, history, profile |
| Admin | `/admin` | `auth` + `admin` | Dashboard, room CRUD, booking status, payment verification, testimonial CRUD |

The `/dashboard` route redirects based on `auth()->user()->isAdmin()`.

Auth routes (from Laravel Breeze, Blade stack) are in `routes/auth.php` — registration uses a modal popup on public pages, not a separate page.

### Role System

A simple `role` column on the `users` table (`'user'` or `'admin'`). Checked by `AdminMiddleware` (registered as alias `'admin'`) via `User::isAdmin()`.

### Key Controllers

- `HomeController` — public home/landing page
- `RoomController` — public room listing + user-specific room views
- `BookingController` — user booking: create, store (with AJAX), show, history, dashboard
- `PaymentController` — user stores payments with proof
- `ProfileController` — user profile editing
- `Admin\DashboardController` — admin dashboard with stats
- `Admin\RoomController` — full CRUD for rooms, photo upload/delete, availability toggle
- `Admin\BookingController` — booking detail view and status updates
- `Admin\PaymentController` — payment list, verify (in DB transaction: marks payment verified → booking confirmed → room unavailable), reject (marks payment rejected → booking cancelled)
- `Admin\TestimonialController` — full CRUD for testimonials (admin-managed, not user-submitted)

### Middleware

Only one custom middleware: `AdminMiddleware` — aborts with 403 if user is not logged in or not an admin. Registered as alias `'admin'` in `bootstrap/app.php`. The same file conditionally enables `trustProxies` in non-local environments (for HTTPS behind a reverse proxy).

### AppServiceProvider

Forces HTTPS scheme in non-local environments (`URL::forceScheme('https')`).

## Database Schema (project-specific tables)

| Table | Key Columns |
|-------|------------|
| `users` | `name`, `email`, `password`, `phone`, `address`, `birth_date`, `role`, `avatar` |
| `rooms` | `name`, `type` (standard/deluxe/vip), `floor` (1/2), `price` (integer), `description`, `is_available` |
| `facilities` | `name`, `icon` |
| `room_facility` | Pivot: `room_id` + `facility_id` |
| `room_photos` | `room_id`, `photo_path`, `is_primary` |
| `bookings` | `user_id`, `room_id`, `booking_code`, `check_in_date`, `duration_months`, `total_price`, `dp_amount`, `status` (pending/confirmed/active/cancelled/completed), `cancelled_reason`, `cancelled_by` |
| `payments` | `booking_id`, `user_id`, `amount`, `payment_method` (qris/dana/ovo/bca), `payment_type` (dp/monthly/full), `proof_path`, `status` (pending/verified/rejected), `verified_at`, `verified_by`, `notes` |
| `testimonials` | `user_id`, `booking_id`, `name`, `label`, `rating`, `content`, `status` (admin-managed) |

### Model Relationships

- `User` → hasMany `Booking`, `Payment`, `Testimonial`
- `Room` → hasMany `RoomPhoto`, `Booking`; belongsToMany `Facility` (via `room_facility`); hasOne `primaryPhoto`
- `Booking` → belongsTo `User`, `Room`; hasMany `Payment`; hasOne `Testimonial`
- `Payment` → belongsTo `Booking`, `User`, `verifiedBy` (User)
- `Testimonial` → belongsTo `User`, `Booking`; has accessor `displayName`

## Core Business Logic

### Booking Flow
1. User picks a room → fills check-in date and payment method (QRIS/DANA/OVO/BCA)
2. System generates booking code (`GDN-XXXXXXXX`), creates booking with status `pending`, and creates a DP payment of **Rp250,000** (constant `Booking::DP_AMOUNT`)
3. Admin verifies payment → booking status → `confirmed`, room → `is_available = false`
4. Admin rejects payment → booking status → `cancelled`, room stays available
5. When booking is `cancelled` or `completed`, room is freed (`is_available = true`)

### Payment Verification (`Admin\PaymentController`)
Uses `DB::transaction` to atomically: update payment status → update booking status → update room availability.

### Booking Status Updates (`Admin\BookingController`)
When status changes to `cancelled` or `completed`, the room is marked available. Cancellation records `cancelled_reason` and `cancelled_by`.

### AJAX Booking
The booking store endpoint supports both AJAX (JSON response with booking details) and regular POST (redirect). The frontend uses Axios with a modal display for the booking receipt.

### Photo Storage
Room photos are stored on the `public` disk (`storage/app/public/rooms/`). First photo uploaded becomes primary automatically. Deleting a primary photo promotes the next one.

## Frontend Notes

- Tailwind CSS is loaded via CDN in the layout (`<script src="https://cdn.tailwindcss.com">`), **not** via the Vite build. The Vite build is for custom CSS/JS.
- Alpine.js is also loaded via CDN.
- The brand color is `#2F4538` (dark green).
- Font families: `Playfair Display` (headings/display, `.font-display`) and `Inter` (body).
- Custom CSS animations: `popupFadeIn`, `overlayFadeIn`, `slideUp` (with stagger delays).
- Custom JS functions for auth modal (`bukaPopupAuth`, `tutupPopupAuth`) and WhatsApp popup (`bukaPopupWA`, `tutupPopupWA`) are defined in `resources/views/layouts/app.blade.php`.

## Support Classes

- `App\Support\Icons` — Centralized SVG icon library, used in Blade as `{!! Icons::get('icon-name') !!}`
- `App\Support\RoomFacilities` — Standard facilities list shared across all rooms (bathroom, desk, WiFi, bed, wardrobe, electricity)

## Environment Notes

- `.env.example` defaults to SQLite; local `.env` uses MySQL (`gardenia_kos` database)
- `.env_hosting` contains production InfinityFree configuration (hardcoded credentials — security concern)
- Production app name: "Kos Putri Gardenia", locale: `id`
- `APP_ENV !== 'local'` gates HTTPS forcing and proxy trust (see `AppServiceProvider` and `bootstrap/app.php`)

## Known Gaps

- Only `dp` payment type is implemented; `monthly` and `full` exist in the schema but have no logic.
- Email is `MAIL_MAILER=log` — password resets and verification emails are logged but not sent.
- Booking duration is hardcoded to 1 month (users cannot choose).
- Migration `2026_07_08_092847_convert_money_columns_to_integer.php` is a no-op (references non-existent table `integer`).
- No project-specific automated tests exist yet (only Laravel boilerplate test files).
- `DOCUMENTATION.md` in the project root contains detailed technical documentation in Indonesian.
