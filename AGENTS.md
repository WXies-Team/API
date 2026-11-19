# AGENTS.md — WXies API

Public API endpoints (api.horatio.cn): pure PHP 8, no framework, Composer or build. Three endpoints — QQ avatar, QQ group avatar, Bing wallpaper — 302-redirect or return JSON; `index.php` is the landing page. Push to `main` rsyncs to production with no test gate; `*.md` / `.github/**` changes do not trigger a deploy (use `workflow_dispatch`).

The interface documentation lives in the Doc repo (`/www/project/Doc/tool/*.md`, deployed as doc.horatio.cn): changing endpoint behavior/params/response means updating the matching page there in the same batch.

## Commands

| Purpose | Command |
| --- | --- |
| Syntax check (required after every change) | `php -l <changed file>` |
| Local preview | `php -S 127.0.0.1:8080` from repo root (endpoints work standalone; `.cache/` is created on demand) |
| Deploy | push `main` → `.github/workflows/deploy.yml` |

There is no lint/test beyond `php -l`. `deploy.yml` uses `rsync -a --delete`: server files not in the repo are deleted except the excluded panel/runtime paths (`.user.ini`, `.htaccess`, `.well-known`, `.cache`, `.git*`, `node_modules`); `AGENTS.md` and `.github` are excluded too.

## Context loading

| Task | Read |
| --- | --- |
| Shared helpers, CORS, cache | `common.php` |
| Param validation / size whitelists | `avatar/qq.php`, `avatar/qqg.php`, `tool/bing.php` |
| Interface docs | `/www/project/Doc/tool/*.md` |
| Deploy behavior | `.github/workflows/deploy.yml` |

## Boundaries

### Always
- Run `php -l` on every changed file before pushing.
- New endpoints start with `require_once __DIR__ . '/../common.php';` → validate params → cache upstream → envelope/redirect; no business logic in `common.php`.
- Read params only via `get_param()` / `get_bool_param()`; respond via `api_json_response()` / `api_error()` / `api_redirect()` (302 with cache headers).
- Upstream calls go through `http_get_json()` (3s timeout, SSL verified) and `cache_get()` / `cache_set()` (`.cache/`, md5-named json, 2h TTL). Changed endpoint behavior/params/response → update the Doc repo page in the same batch.

### Ask first
- CORS policy change (`Access-Control-Allow-Origin: *` is deliberate).
- Removing a legacy size-code alias (`1/2/3` serve existing callers).
- Adding Composer/dependencies or a build step (the repo is deliberately dependency-free).

### Never
- Commit `.cache/`, `.htaccess`, `.user.ini`, `tool/.env`, `vendor/` (all gitignored).
- Echo raw upstream errors, stack traces or `phpinfo()` to clients.
- Touch `$_GET` directly, or interpolate a raw param into an upstream URL — whitelist/size-map first (`avatar/*.php`, `tool/bing.php`), otherwise SSRF/path injection.
- Put anything but endpoints in this repo (no HTML beyond the `index.php` landing page); output before `require_once common.php` breaks the `OPTIONS` 204 preflight.

General rules: `~/.config/opencode/AGENTS.md`.
