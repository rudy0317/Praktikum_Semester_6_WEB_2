# AGENTS.md — Praktikum Web Semester 6

## What this repo is

Hybrid: **AdminLTE 3.1.0** (Bootstrap 4 admin template) + **custom PHP CRUD app** ("Sistem Informasi Kepegawaian FTI UNISKA"). The AdminLTE build toolchain and the PHP app coexist at root — the PHP app is the actual coursework.

## App structure

| Path | Purpose |
|---|---|
| `index.php` | Login entrypoint (session-based auth, roles: ADMIN/USER) |
| `koneksi.php` | DB connection via `parse_ini_file('.env')` → `mysqli_connect` |
| `.env` | DB_HOST, DB_PORT, DB_USER, DB_PASS, DB_NAME |
| `admin/` | ADMIN dashboard pages + theme partials (`theme-header/sidebar/footer.php`) |
| `user/` | USER dashboard pages + theme partials |
| `forgot-password.php` | Password reset form (UI only, no backend logic yet) |
| `pages/` | AdminLTE demo HTML pages (not integrated with PHP) |

- **No PHP framework, no ORM, no router** — vanilla `mysqli_*` functions, raw includes.
- **Many sidebar links reference pages that don't exist yet** (`admin/mahasiswa.php`, `admin/gaji.php`, etc.) — the app is a starter/partial implementation.
- DB port is **1111** (non-standard), DB name: `db_uniska_praktikum_semester_6`.

## Auth flow

- `index.php`: verifies password with `password_verify()`, sets `$_SESSION["login"]`, `$_SESSION["peran"]`, `$_SESSION["username"]`, `$_SESSION["id"]`.
- Each admin/user page guards with `session_start()`, checks `$_SESSION["login"]` and `$_SESSION["peran"]`.
- ADMIN users get `$_SESSION["nama"] = "Admin"` hardcoded; USER users get their DB `nama` column.

## Build (AdminLTE frontend assets)

| Command | What it does |
|---|---|
| `npm install` | Installs deps + runs `npm run plugins` postinstall |
| `npm run dev` | Watch SCSS/JS + browsersync live server |
| `npm run production` | Compile + minify CSS/JS + publish plugins |
| `npm test` | Runs `lint` then `production` |
| `npm run lint` | `css-lint` + `js-lint` + `lockfile-lint` in parallel |
| `npm run css-lint` | stylelint on `build/scss/**/*.scss` |
| `npm run js-lint` | eslint on `.` |
| `npm run bundlewatch` | Bundle size tracking (CI only, needs token) |

- SCSS source: `build/scss/` → output: `dist/css/`
- JS source: `build/js/` → Rollup → `dist/js/`
- Use CSS/JS from `dist/` in PHP pages (already linked in admin/user templates).

## CI (`.github/workflows/ci.yml`)

Runs on push/PR to master. Steps: `npm ci` → `npm run compile` → `npm run bundlewatch` (ubuntu/node 14 only). Matrix: Node 10/12/14 x ubuntu/macos/windows.

## PHP conventions (observed, not enforced)

- Opening `<?php` without closing `?>` in files that are only PHP (e.g., `koneksi.php`).
- Session + role guard at top of every admin/user page.
- DB queries use `mysqli_query()` with direct variable interpolation (**SQL injection present** — be aware when editing).
- `date_default_timezone_set("Asia/Makassar")` in login pages.
- No prepared statements or parameterized queries — all existing queries are raw string interpolation.

## Security notes

- `.env` is gitignored (`*.env` pattern) but an actual `.env` file is tracked in the repo — do not commit secrets.
- `forgot-password.php` has the form markup but no backend logic; any new implementation here should be built with security in mind.

## What NOT to touch without explicit ask

- `vendor/` — Composer deps (empty, no PHP packages)
- `node_modules/` — npm deps
- `plugins/` — vendored frontend libs
- `pages/`, `docs/`, `build/` — upstream AdminLTE files
- `.github/` — CI/CD workflows
