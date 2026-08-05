# Frontend — Localization (EN / AR)

The API now returns **translated content** based on the request locale.  
Response **shape is the same** as before — only string values change per language.

Supported locales: `en` (default), `ar`.

---

## What the frontend must change

### 1. Send locale on every API call

Pick **one** method (query is simplest):

```http
GET /api/home?locale=ar
X-Locale: ar
Accept-Language: ar
```

Priority on the backend:

1. `?locale=`
2. `X-Locale`
3. `Accept-Language`
4. App default (`en`)

### 2. Keep locale in sync with UI language switcher

When the user switches language:

1. Store `en` | `ar` (localStorage / app state).
2. Re-fetch content with that locale (or refetch the pages that show CMS text).
3. Set `dir="rtl"` / `dir="ltr"` on `<html>` for Arabic vs English.
4. Use the **slug returned for the current locale** for blog / gallery category detail routes.

### 3. Recommended fetch helper

```js
const BASE_URL = 'http://new-world-nursery.test/api';

async function api(path, { locale = 'en', ...options } = {}) {
  const url = new URL(`${BASE_URL}${path}`);
  url.searchParams.set('locale', locale);

  const res = await fetch(url, {
    ...options,
    headers: {
      Accept: 'application/json',
      'X-Locale': locale,
      ...(options.headers || {}),
    },
  });

  // Optional check:
  // res.headers.get('Content-Language') === locale

  return res.json();
}

// Examples
const home = await api('/home', { locale: 'ar' });
const blogs = await api('/blogs', { locale: 'en' });
```

### 4. Fallback behavior

If Arabic text is missing for a field, the API falls back to English for that field.  
Do **not** hardcode English copy in the frontend for CMS fields — always use the API value.

### 5. What is NOT translated

| Item | Notes |
|------|--------|
| Contact form `POST /api/contact` | User-submitted data |
| Phones, emails, social URLs, map/visit URLs | Shared across locales |
| Images / icon colors / sort order / ids | Shared |
| Auth `/api/user` | Unchanged |

For contact `program`, send the program `title` from `/api/programs?locale=...` in the **active** language.

---

## APIs that changed (locale-aware)

All of these accept `locale` and return translated strings when available:

| Method | Endpoint | Translated fields |
|--------|----------|-------------------|
| `GET` | `/api/home` | All nested translated content + top-level `locale` |
| `GET` | `/api/settings` | Settings copy + top-level `locale` |
| `GET` | `/api/features` | `title`, `description` |
| `GET` | `/api/locations` | `name`, `city`, `country`, `address`, `working_hours` |
| `GET` | `/api/locations/{id}` | same |
| `GET` | `/api/programs` | `title`, `age_range`, `description` |
| `GET` | `/api/programs/{id}` | same |
| `GET` | `/api/gallery` | `alt`, `category.name`, `category.slug` |
| `GET` | `/api/gallery/categories` | `name`, `slug`, nested `items[].alt` |
| `GET` | `/api/gallery/categories/{slug}` | same (slug may be EN or AR) |
| `GET` | `/api/instagram` | `alt` |
| `GET` | `/api/blogs` | `title`, `slug`, `excerpt`, `content` |
| `GET` | `/api/blogs/latest` | same |
| `GET` | `/api/blogs/{slug}` | same (slug may be EN or AR) |

### Response extras

- Header: `Content-Language: en|ar`
- Body (home + settings only):

```json
{
  "locale": "ar",
  "data": { }
}
```

Other list/detail endpoints keep the previous `{ "data": ... }` shape (no top-level `locale` field), but still honor `?locale=` / `X-Locale`.

---

## Settings — translated vs shared

### Translated (change with locale)

`site_name`, hero texts/CTAs, about texts/CTAs, section headings (locations / programs / gallery / moments), contact label/title/subtitle/**address**, footer about, newsletter title.

### Shared (same in EN & AR)

`top_bar_phone`, `top_bar_email`, `contact_email`, `contact_phone`, `contact_website`, social URLs, `hero.image`, `about.image`.

---

## Slugs (important for routing)

Blogs and gallery categories have a **slug per locale**.

```text
EN: /blog/a-happy-start-to-nursery-life
AR: /blog/{arabic-or-transliterated-slug-from-api}
```

Rules:

1. On list pages, take `slug` from the current locale response.
2. Detail URLs should use that slug.
3. Backend also resolves detail by **any** locale slug, so old EN links still work, but UI should prefer the active-locale slug.

---

## Home payload reminder

```http
GET /api/home?locale=ar
```

```json
{
  "locale": "ar",
  "data": {
    "settings": {},
    "features": [],
    "locations": [],
    "programs": [],
    "gallery_categories": [],
    "gallery_items": [],
    "moments": [],
    "instagram": [],
    "gallery": []
  }
}
```

- `gallery` / `instagram` → Instagram feed  
- `moments` / `gallery_items` → site gallery  
- `gallery_categories` → category list  

---

## Checklist for frontend

- [ ] Add language switcher (`en` / `ar`)
- [ ] Persist selected locale
- [ ] Pass `locale` (or `X-Locale`) on all content API calls
- [ ] Toggle `dir` / fonts for RTL Arabic
- [ ] Use API strings for all CMS text (no hardcoded hero/about/section titles)
- [ ] Use locale-specific `slug` for blog & gallery category pages
- [ ] Re-fetch (or invalidate cache) when locale changes
- [ ] Import updated Postman collection: `postman/New-World-Nursery-API.postman_collection.json`

---

## Postman

1. Import `postman/New-World-Nursery-API.postman_collection.json`
2. Import `postman/New-World-Nursery-Local.postman_environment.json`
3. Set env/collection variable `locale` to `en` or `ar`
4. Open folder **i18n** for ready-made EN vs AR examples (`Home (AR)`, `Settings (AR)`, …)

Full endpoint reference: [FRONTEND_API.md](./FRONTEND_API.md)
