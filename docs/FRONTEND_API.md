# New World Nursery — Frontend API Guide

Base URL (local):

```text
http://new-world-nursery.test/api
```

All public endpoints return JSON.  
Send header:

```http
Accept: application/json
Content-Type: application/json
```

Image fields always return a **full absolute URL**, or `null` if empty:

```text
http://new-world-nursery.test/storage/image/{path}
```

---

## Quick overview

| Method | Endpoint | Description |
|--------|----------|-------------|
| `GET` | `/api/home` | Full homepage content in one call |
| `GET` | `/api/settings` | Site settings only |
| `GET` | `/api/features` | Features list |
| `GET` | `/api/locations` | Locations list |
| `GET` | `/api/locations/{id}` | Single location |
| `GET` | `/api/programs` | Programs list |
| `GET` | `/api/programs/{id}` | Single program |
| `GET` | `/api/gallery` | Gallery images |
| `POST` | `/api/contact` | Submit contact form |
| `GET` | `/api/user` | Authenticated user (Bearer token) |

**Recommended:** use `GET /api/home` for the landing page.

---

## 1. Get Home Content

```http
GET /api/home
```

### Success `200`

```json
{
  "data": {
    "settings": { "...": "see Site Settings shape below" },
    "features": [],
    "locations": [],
    "programs": [],
    "gallery": []
  }
}
```

---

## 2. Get Site Settings

```http
GET /api/settings
```

### Success `200`

```json
{
  "data": {
    "site_name": "New World Nursery",
    "top_bar_phone": "+971 50 123 4567",
    "top_bar_email": "info@newworldnursery.ae",
    "facebook_url": "https://facebook.com",
    "instagram_url": "https://instagram.com",
    "twitter_url": null,
    "youtube_url": "https://youtube.com",
    "hero": {
      "eyebrow": "NEW WORLD NURSERY - DUBAI",
      "title": "A Happy Place to Learn & Grow",
      "subtitle": "A warm Dubai nursery where play-based learning...",
      "image": "http://new-world-nursery.test/storage/image/site/hero.jpg",
      "cta_primary": "ENQUIRE NOW",
      "cta_secondary": "OUR PROGRAMS"
    },
    "about": {
      "label": "ABOUT US",
      "title": "Welcome to New World Nursery",
      "highlight": "New World Nursery",
      "content": "Based in Dubai, we welcome children...",
      "image": "http://new-world-nursery.test/storage/image/site/about.jpg",
      "cta": "BOOK A VISIT"
    },
    "locations": {
      "label": "OUR LOCATIONS",
      "title": "Find us across",
      "title_highlight": "the region",
      "subtitle": "Start with our Dubai home in Al Barsha..."
    },
    "programs": {
      "label": "OUR PROGRAMS",
      "title": "Learning by",
      "title_highlight": "age & stage",
      "subtitle": "Play-led pathways from first steps..."
    },
    "gallery": {
      "label": "INSTAGRAM",
      "title": "Follow",
      "title_highlight": "Our Journey",
      "subtitle": "Peek into classroom moments...",
      "cta": "FOLLOW US ON INSTAGRAM"
    },
    "contact": {
      "label": "PLAN A VISIT",
      "title": "Talk with",
      "title_highlight": "Our Team",
      "subtitle": "Tell us your child's age and preferred program...",
      "email": "info@newworldnursery.ae",
      "phone": "+971 50 123 4567",
      "address": "Al Barsha, Dubai, UAE",
      "website": "https://newworldnursery.ae"
    },
    "footer_about": "New World Nursery provides a warm Dubai nursery...",
    "newsletter_title": "Come see New World in action"
  }
}
```

### UI mapping tips

- `*_highlight` fields are the words that should be styled with the teal highlight/brush effect.
- Example: title = `Find us across` + highlight = `the region` → **Find us across** `the region`

---

## 3. Features

```http
GET /api/features
```

### Success `200`

```json
{
  "data": [
    {
      "id": 1,
      "title": "Safe & Secure",
      "description": "Supervised spaces and clear routines so families feel at ease every day.",
      "icon_color": "#D4EDDA",
      "icon_image": "http://new-world-nursery.test/storage/image/features/safe.png",
      "sort_order": 1
    }
  ]
}
```

| Field | Type | Notes |
|-------|------|--------|
| `icon_color` | string | Hex background for the circle |
| `icon_image` | string\|null | Full image URL |

---

## 4. Locations

### List

```http
GET /api/locations
```

### Single

```http
GET /api/locations/{id}
```

### Success `200` (list)

```json
{
  "data": [
    {
      "id": 1,
      "name": "New World Nursery - Dubai",
      "city": "Dubai",
      "country": "United Arab Emirates",
      "badge_color": "#2E9E94",
      "address": "Al Barsha, Dubai, UAE",
      "phone": "+971 50 123 4567",
      "email": "dubai@newworldnursery.ae",
      "working_hours": "Sun – Thu: 7:00 AM – 6:00 PM",
      "map_url": "https://maps.google.com",
      "visit_url": "#contact",
      "image": "http://new-world-nursery.test/storage/image/locations/dubai.jpg",
      "sort_order": 1
    }
  ]
}
```

### Errors

| Status | When |
|--------|------|
| `404` | Location not found or inactive |

---

## 5. Programs

### List

```http
GET /api/programs
```

### Single

```http
GET /api/programs/{id}
```

### Success `200` (list)

```json
{
  "data": [
    {
      "id": 1,
      "title": "Toddlers",
      "age_range": "18 Months - 2.5 Years",
      "description": "Soft routines, sensory play, and first friendships that ease the start of nursery life.",
      "color": "#E8F5E9",
      "icon": "👶",
      "icon_color": "#81C784",
      "image": "http://new-world-nursery.test/storage/image/programs/toddlers.jpg",
      "sort_order": 1
    }
  ]
}
```

| Field | Type | Notes |
|-------|------|--------|
| `color` | string | Card background hex |
| `icon` | string\|null | Emoji fallback |
| `icon_color` | string | Icon circle accent |
| `image` | string\|null | Full image URL |

### Errors

| Status | When |
|--------|------|
| `404` | Program not found or inactive |

---

## 6. Gallery

```http
GET /api/gallery
```

### Success `200`

```json
{
  "data": [
    {
      "id": 1,
      "image": "http://new-world-nursery.test/storage/image/gallery/1.jpg",
      "alt": "Classroom moment",
      "sort_order": 1
    }
  ]
}
```

Use `settings.gallery.cta` + `settings.instagram_url` for the Instagram button.

---

## 7. Contact Form (Submit Message)

```http
POST /api/contact
```

### Request body

```json
{
  "name": "Jane Smith",
  "email": "jane@example.com",
  "phone": "+971501234567",
  "program": "Toddlers",
  "child_age": "3 years",
  "message": "I would like to book a visit for my child."
}
```

| Field | Required | Type | Rules |
|-------|----------|------|--------|
| `name` | yes | string | max 255 |
| `email` | yes | email | max 255 |
| `message` | yes | string | max 5000 |
| `phone` | no | string | max 50 |
| `program` | no | string | max 255 (use program `title` from `/api/programs`) |
| `child_age` | no | string | max 50 |

### Success `201`

```json
{
  "message": "Thank you! Your message has been sent successfully.",
  "data": {
    "id": 1,
    "name": "Jane Smith",
    "email": "jane@example.com",
    "phone": "+971501234567",
    "program": "Toddlers",
    "child_age": "3 years",
    "message": "I would like to book a visit for my child.",
    "created_at": "2026-07-27T10:06:49+00:00"
  }
}
```

### Validation error `422`

```json
{
  "message": "Please enter your name. (and 2 more errors)",
  "errors": {
    "name": ["Please enter your name."],
    "email": ["Please enter your email address."],
    "message": ["Please enter your message."]
  }
}
```

---

## 8. Authenticated User (optional)

```http
GET /api/user
Authorization: Bearer {token}
```

Requires Sanctum token. Not needed for the public website.

---

## Suggested frontend usage

### Landing page

```js
const res = await fetch(`${BASE_URL}/api/home`, {
  headers: { Accept: 'application/json' },
});
const { data } = await res.json();

// data.settings  -> header/hero/about/section titles/contact/footer
// data.features  -> why choose us
// data.locations -> branches cards
// data.programs  -> programs cards + contact program select options
// data.gallery   -> instagram grid
```

### Contact submit

```js
const res = await fetch(`${BASE_URL}/api/contact`, {
  method: 'POST',
  headers: {
    Accept: 'application/json',
    'Content-Type': 'application/json',
  },
  body: JSON.stringify({
    name,
    email,
    phone,
    program,      // program.title
    child_age,
    message,
  }),
});

if (res.status === 422) {
  const err = await res.json();
  // err.errors -> field errors
}

if (res.status === 201) {
  const ok = await res.json();
  // ok.message -> success toast
}
```

---

## Notes for frontend

1. Lists are already sorted (`sort_order` ascending).
2. Inactive items are **not** returned by the API.
3. Always use the image URL as returned — do not prepend `APP_URL` again.
4. Nullable strings/images can be `null` — handle empty states.
5. Postman collection is available in:
   - `postman/New-World-Nursery-API.postman_collection.json`
   - `postman/New-World-Nursery-Local.postman_environment.json`
