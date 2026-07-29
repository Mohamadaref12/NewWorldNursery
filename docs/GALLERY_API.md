# New World Nursery — Gallery, Categories & Instagram API

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

Gallery and Instagram are **separate tables**:

| Feature | Table | Admin |
|---------|-------|--------|
| Categories | `gallery_categories` | Gallery → Categories |
| Gallery images | `gallery_items` | Gallery → Gallery |
| Instagram feed | `instagram_posts` | Instagram page (OAuth sync) |

Only **active** records are returned. Lists are sorted by `sort_order` ascending.

---

## Overview

| Method | Endpoint | Description |
|--------|----------|-------------|
| `GET` | `/api/gallery` | All gallery images (optional category filter) |
| `GET` | `/api/gallery/categories` | All categories with their images |
| `GET` | `/api/gallery/categories/{slug}` | One category + its images |
| `GET` | `/api/instagram` | Synced Instagram posts |
| `GET` | `/api/home` | Includes gallery + Instagram keys (see below) |

---

## 1. Gallery images

```http
GET /api/gallery
GET /api/gallery?category=moments-of-joy
GET /api/gallery?category=1
```

| Query | Required | Notes |
|-------|----------|--------|
| `category` | no | Category `slug` or numeric `id` |

### Success `200`

```json
{
  "data": [
    {
      "id": 1,
      "image": "http://new-world-nursery.test/storage/image/moments/abc.jpg",
      "alt": "Colorful building blocks",
      "sort_order": 1,
      "category": {
        "id": 1,
        "name": "Moments of Joy",
        "slug": "moments-of-joy"
      }
    }
  ]
}
```

| Field | Type | Notes |
|-------|------|--------|
| `image` | string\|null | Full absolute URL |
| `alt` | string\|null | Alt / caption text |
| `category` | object\|null | Present when category is loaded |

---

## 2. Gallery categories (list)

```http
GET /api/gallery/categories
```

Returns every active category, each with its active images inside `items`.

### Success `200`

```json
{
  "data": [
    {
      "id": 1,
      "name": "Moments of Joy",
      "slug": "moments-of-joy",
      "sort_order": 1,
      "items": [
        {
          "id": 1,
          "image": "http://new-world-nursery.test/storage/image/moments/abc.jpg",
          "alt": "Colorful building blocks",
          "sort_order": 1
        }
      ]
    },
    {
      "id": 2,
      "name": "Classroom",
      "slug": "classroom",
      "sort_order": 2,
      "items": []
    }
  ]
}
```

---

## 3. Single category + its images

```http
GET /api/gallery/categories/{slug}
```

Use the category **slug** (e.g. `moments-of-joy`).

### Success `200`

```json
{
  "data": {
    "id": 1,
    "name": "Moments of Joy",
    "slug": "moments-of-joy",
    "sort_order": 1,
    "items": [
      {
        "id": 1,
        "image": "http://new-world-nursery.test/storage/image/moments/abc.jpg",
        "alt": "Colorful building blocks",
        "sort_order": 1
      },
      {
        "id": 2,
        "image": "http://new-world-nursery.test/storage/image/moments/def.jpg",
        "alt": "Child with face paint smiling",
        "sort_order": 2
      }
    ]
  }
}
```

### Errors

| Status | When |
|--------|------|
| `404` | Category not found or inactive |

---

## 4. Instagram feed

```http
GET /api/instagram
```

Synced from Meta via Admin → Instagram (Connect + Sync).  
Stored in `instagram_posts`, files under `storage/app/public/image/instagram/`.

### Success `200`

```json
{
  "data": [
    {
      "id": 1,
      "image": "http://new-world-nursery.test/storage/image/instagram/17841405822304914.jpg",
      "alt": "Caption excerpt from the post",
      "permalink": "https://www.instagram.com/p/ABC123/",
      "sort_order": 0
    }
  ]
}
```

| Field | Type | Notes |
|-------|------|--------|
| `image` | string\|null | Local full URL (downloaded on sync) |
| `alt` | string\|null | From Instagram caption (truncated) |
| `permalink` | string\|null | Link to the post on Instagram |

If nothing is connected/synced yet, `data` is an empty array `[]`.

---

## 5. Home payload keys

```http
GET /api/home
```

Relevant keys inside `data`:

| Key | Content |
|-----|---------|
| `gallery_categories` | Categories only (no nested items in this list) |
| `gallery_items` | All gallery images (with category) |
| `moments` | Same as `gallery_items` (alias) |
| `instagram` | Instagram posts |
| `gallery` | Same as `instagram` (backward-compatible alias) |

Section copy/titles still come from `settings`:

- `settings.moments` → gallery section headings
- `settings.gallery` → Instagram section headings
- `settings.instagram_url` → follow button URL

---

## Frontend examples

```js
const BASE_URL = 'http://new-world-nursery.test';
const headers = { Accept: 'application/json' };

// All gallery images
const gallery = await fetch(`${BASE_URL}/api/gallery`, { headers }).then((r) => r.json());

// Images in one category
const byCategory = await fetch(`${BASE_URL}/api/gallery?category=moments-of-joy`, { headers })
  .then((r) => r.json());

// Categories with nested images (good for tabs/filters UI)
const categories = await fetch(`${BASE_URL}/api/gallery/categories`, { headers })
  .then((r) => r.json());

// One category page
const one = await fetch(`${BASE_URL}/api/gallery/categories/classroom`, { headers })
  .then((r) => r.json());

// Instagram grid
const ig = await fetch(`${BASE_URL}/api/instagram`, { headers }).then((r) => r.json());
```

### Suggested UI mapping

```js
// Category tabs
categories.data.forEach((cat) => {
  // cat.name  → tab label
  // cat.items → images for that tab
});

// Instagram section
ig.data.forEach((post) => {
  // post.image     → thumbnail
  // post.permalink → open on Instagram
});
```

---

## Notes

1. Gallery ≠ Instagram — different tables and endpoints.
2. Always use image URLs as returned — do not prepend `APP_URL` again.
3. Manage gallery images/categories in Filament under **Gallery**.
4. Connect/sync Instagram from Filament **Instagram** page (see `docs/INSTAGRAM_INTEGRATION.md`).
5. Inactive categories/images/posts are never returned.
