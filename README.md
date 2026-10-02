# More Academy — course website + admin panel

Laravel 12 · PHP 8.2+ · MySQL · Blade · Tailwind CSS v4 · Alpine.js

## Quick start (local)

```bash
composer install
cp .env.example .env        # skip if .env already exists
php artisan key:generate

# create an empty MySQL database (default name: more_academy) and set DB_* in .env
# set ADMIN_EMAIL / ADMIN_PASSWORD in .env (leave the password empty to get a random one printed once)

php artisan migrate --seed
php artisan storage:link    # needed for uploaded course images

npm install && npm run build   # compiled assets are already in public/build; rebuild after changing views/CSS
php artisan serve
```

- Website: http://localhost:8000
- Admin: http://localhost:8000/admin (log in with the ADMIN_EMAIL account)

`db:seed` creates the admin user, the 8 starter courses, and in `APP_ENV=local` 36 fake demo applications (`@example.com`). To start with empty applications run `php artisan migrate:fresh --seed` with `APP_ENV=production`, or delete them in the admin.

## Design system (from the brand references)

| Token | Value | Use |
|---|---|---|
| cream | `#FFF8E5` | page background ("paper") |
| ink | `#0C0C0C` | text, borders, black panels |
| brand-yellow | `#F3E14A` | single accent: CTAs, line drawings, active states |
| Space Mono 700, uppercase | display type | "LEARN MORE. CREATE MORE." headlines |
| Noto Sans Armenian | body text | all Armenian copy |

Hard 2px black rules, square cards with a hard offset shadow on hover, pill buttons, thin yellow single-line drawings (head, robot, phones…), dim code texture on black panels, photos shown in black & white. The admin uses the same palette with softer, rounded surfaces and a dark sidebar so it is clearly a separate tool.

## Structure

```
app/
  Enums/RegistrationStatus.php        new | contacted | accepted | rejected (labels + badge colours)
  Models/Course.php, Registration.php, User.php (is_admin)
  Http/Controllers/                    HomeController, CourseController, RegistrationController
  Http/Controllers/Admin/              AuthController, DashboardController, RegistrationController, CourseController
  Http/Requests/                       StoreRegistrationRequest, Admin/UpdateRegistrationRequest, Admin/CourseRequest
  Http/Middleware/EnsureUserIsAdmin.php  ('admin' alias, Gate 'access-admin')
database/migrations                    users (+is_admin), sessions, cache, courses, registrations (FK course_id, restrict)
database/seeders                       AdminSeeder, CourseSeeder, DemoRegistrationSeeder
resources/views/
  components/layouts/                  app (public), admin, error
  components/site/                     header, footer
  components/ui/                       button, field, alert, flash, status-badge, course-card, course-image,
                                       line-art, wave, code-texture, section-heading, pagination
  components/admin/                    sidebar, nav-link, page-header, stat-card, status-select, delete-button, icon, empty-state
  pages/home · courses/index, show · registrations/create, success · admin/** · errors/**
routes/web.php                         public routes + /admin group (auth + admin middleware)
```

## Routes

| Method | URL | Purpose |
|---|---|---|
| GET | `/` | Home |
| GET | `/courses?q=&category=` | Course list with search + category filter |
| GET | `/courses/{slug}` | Course details (inactive courses return 404) |
| GET | `/register?course={slug}` | Application form, course pre-selected |
| POST | `/register` | Save application (throttled 5/min/IP) |
| GET | `/register/thank-you` | Confirmation (only right after submitting) |
| GET/POST | `/admin/login` | Admin login (throttled) |
| GET | `/admin` | Dashboard: totals, new, accepted, active courses, 14-day chart, by status, by course, recent |
| GET | `/admin/registrations?q=&status=&course=` | Table with search, filters, pagination |
| GET/PUT/DELETE | `/admin/registrations/{id}` (+ `/edit`) | View, edit, delete |
| PATCH | `/admin/registrations/{id}/status` | Change status inline |
| GET/POST/PUT/DELETE | `/admin/courses…` | Course CRUD with image upload |
| PATCH | `/admin/courses/{slug}/toggle` | Activate / deactivate |

## Security & data handling

- All `/admin` routes require login **and** `is_admin` (middleware + gate); `is_admin` is not mass-assignable.
- CSRF on every form, server-side validation via Form Requests, bcrypt passwords, session regeneration on login, login and form rate limiting.
- Registration form: honeypot field, consent checkbox, submit button locks after the first click, and the same e-mail + course within 24 h does not create a duplicate. The confirmation page reveals nothing about earlier applications.
- A course with applications cannot be deleted (foreign key `restrict`); deactivate it instead.
- Applications are visible only inside the admin; `/admin` is `noindex` and disallowed in `robots.txt`.
- The form hint asks people not to enter health or other sensitive data. Collect only what you need.

## Notes

- Course prices are empty in the seed data ("Ճշտել զանգով" is shown). Set them in **Admin → Courses**.
- Starter course texts are drafts; edit them in the admin.
- Uploaded images go to `storage/app/public/courses` and are served through `public/storage`.
# more-academy
