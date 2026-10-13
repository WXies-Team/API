# AGENTS.md

WXies Team public API endpoints (api.horatio.cn): **pure PHP 8, no framework, no Composer, no build**. Three endpoints — QQ avatar / QQ group avatar / Bing wallpaper — all 302-redirect to the upstream image URL or return JSON metadata; `index.php` is a static landing page that redirects to the doc site. Push to `main` deploys straight to production via Actions rsync — **push = live, with no test gate**.

- **The interface documentation lives in the Doc repo** (`/www/project/Doc/tool/*.md`, deployed as doc.horatio.cn): changing endpoint behavior/params/response means updating the matching page there in the same batch.

## Commands

- Syntax check: `php -l <changed file>` — required after every change; there is no lint/test beyond it.
- Local preview: repo root `php -S 127.0.0.1:8080` (endpoints work standalone; `.cache/` is created on demand).
- Deploy: push `main` → `.github/workflows/deploy.yml` rsyncs to the server; changes to `*.md` or `.github/**` **do not trigger** a deploy — use workflow_dispatch.

## Conventions

- Every endpoint starts with `require_once __DIR__ . '/../common.php';` — it sets CORS headers, answers `OPTIONS` with 204, and provides all helpers. New endpoints do the same; no business logic in `common.php`.
- **Read params only through `get_param()` / `get_bool_param()`** (trims, empty string falls back to the default). Never touch `$_GET` directly.
- **Responses go through the shared helpers**: `api_json_response(code, message, data, httpStatus)` emits the `{code, message, data}` envelope; `api_error(...)` for failures; `api_redirect(url, cacheSeconds)` for 302s with cache headers.
- **Upstream calls go through `http_get_json()` (3s timeout, SSL verified) and the file cache `cache_get()`/`cache_set()`** (`.cache/`, md5-named json, 2h default TTL). Any new upstream call must cache — the Bing endpoint exists because uncached calls hit upstream rate limits.
- Interpolate params into upstream URLs **only after whitelisting** (see the size maps and regex validation in `avatar/*.php`, `tool/bing.php`); a raw param in a URL is an SSRF/path-injection hole.
- `declare(strict_types=1);` in every file; `display_errors` is off — a white screen means reading the server error log, never enabling display.

## Boundaries

**Always**
- Run `php -l` on every changed file before pushing.
- New endpoints follow the existing pattern: `common.php` → validate params → cache upstream → envelope response or 302.
- Changed endpoint behavior/params/response → update the Doc repo page (`/www/project/Doc/tool/`) in the same batch.

**Ask first**
- Changing the CORS policy (`Access-Control-Allow-Origin: *` is a deliberate decision).
- Removing a legacy parameter alias (the size-code aliases `1/2/3` serve existing callers).
- Adding Composer/dependencies or a build step (the repo is deliberately dependency-free).

**Never**
- Commit `.cache/`, `.htaccess`, `.user.ini`, `tool/.env`, `vendor/` (all gitignored).
- Echo raw upstream errors, stack traces, or `phpinfo()` to clients.
- Put anything but endpoints in this repo — no HTML beyond the `index.php` landing page.

## Known pitfalls

- `common.php` answers `OPTIONS` with 204 **before any output** — headers must be set first; an endpoint that outputs before requiring it breaks CORS preflight.
- `get_param()` treats an empty string as "missing" — a caller sending `?day=` gets the default, not an error; don't rely on "present but empty" distinctions.
- Deploy is `rsync -a --delete`: files on the server that are not in the repo get deleted, except the excluded `.user.ini` / `.htaccess` / `.well-known` (panel-managed) and `.cache/` (runtime, self-heals). `AGENTS.md` / `.github` are excluded on purpose — repo-only files must stay excluded when editing the workflow.
