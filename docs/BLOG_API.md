# New World Nursery — Blog API

Base URL (local):

```text
http://new-world-nursery.test/api
```

Send header:

```http
Accept: application/json
```

Image fields return a **full absolute URL**, or `null` if empty:

```text
http://new-world-nursery.test/storage/image/{path}
```

---

## Overview

| Method | Endpoint | Description |
|--------|----------|-------------|
| `GET` | `/api/blogs` | All published blogs |
| `GET` | `/api/blogs/latest` | Latest blogs (default 5) |
| `GET` | `/api/blogs/{slug}` | Single blog by slug |

Only **active** posts with `published_at` in the past (or now) are returned.  
Sorted by `published_at` descending (newest first).

---

## List all blogs

```http
GET /api/blogs
```

### Success `200`

```json
{
  "data": [
    {
      "id": 1,
      "title": "A Happy Start to Nursery Life",
      "slug": "a-happy-start-to-nursery-life",
      "excerpt": "How we help little ones settle into Al Barsha...",
      "content": "<p>Full HTML body from the admin editor.</p>",
      "image": "http://new-world-nursery.test/storage/image/blogs/post.jpg",
      "published_at": "2026-07-26T10:00:00+00:00"
    }
  ]
}
```

---

## Latest blogs

```http
GET /api/blogs/latest
GET /api/blogs/latest?limit=10
```

| Query | Default | Notes |
|-------|---------|--------|
| `limit` | `5` | Min `1`, max `20` |

Same response shape as the full list.

---

## Single blog

```http
GET /api/blogs/{slug}
```

Use the post **slug** (not the numeric id).

### Success `200`

```json
{
  "data": {
    "id": 1,
    "title": "A Happy Start to Nursery Life",
    "slug": "a-happy-start-to-nursery-life",
    "excerpt": "How we help little ones settle into Al Barsha...",
    "content": "<p>Full HTML body from the admin editor.</p>",
    "image": "http://new-world-nursery.test/storage/image/blogs/post.jpg",
    "published_at": "2026-07-26T10:00:00+00:00"
  }
}
```

### Errors

| Status | When |
|--------|------|
| `404` | Blog not found, inactive, or not yet published |

---

## Response fields

| Field | Type | Notes |
|-------|------|--------|
| `id` | number | Database id |
| `title` | string | Post title |
| `slug` | string | Unique; used in `/api/blogs/{slug}` |
| `excerpt` | string\|null | Short teaser for list cards |
| `content` | string\|null | HTML from Filament RichEditor |
| `image` | string\|null | Full absolute image URL |
| `published_at` | string\|null | ISO 8601 datetime |

---

## Frontend examples

```js
const BASE_URL = 'http://new-world-nursery.test';

// Homepage / sidebar: latest posts
const latest = await fetch(`${BASE_URL}/api/blogs/latest?limit=3`, {
  headers: { Accept: 'application/json' },
}).then((r) => r.json());

// Blog listing page
const all = await fetch(`${BASE_URL}/api/blogs`, {
  headers: { Accept: 'application/json' },
}).then((r) => r.json());

// Blog detail page
const post = await fetch(`${BASE_URL}/api/blogs/${slug}`, {
  headers: { Accept: 'application/json' },
}).then((r) => r.json());
```

---

## Notes

1. Inactive or future-dated posts are **not** returned.
2. Always use the image URL as returned — do not prepend `APP_URL` again.
3. `content` is HTML — sanitize or use a trusted HTML renderer.
4. Manage posts from Filament admin: `/admin/blogs`.
