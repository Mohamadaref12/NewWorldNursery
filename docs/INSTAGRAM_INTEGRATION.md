# Instagram Integration (Admin Dashboard)

Connect a Professional Instagram account from Filament and sync posts into the **`instagram_posts`** table (separate from Gallery).

## Requirements

1. Instagram account is **Business** or **Creator**
2. Account is linked to a **Facebook Page**
3. Meta app at [developers.facebook.com](https://developers.facebook.com) with:
   - **Instagram Graph API**
   - **Facebook Login**
4. OAuth permissions (App Review needed for live mode / other users):
   - `instagram_basic`
   - `pages_show_list`
   - `pages_read_engagement`

## Setup from dashboard

1. Open **Admin → Instagram**
2. Enter **App ID** and **App Secret** from your Meta app
3. Copy the shown **OAuth Redirect URI** into Meta → Facebook Login → Settings → Valid OAuth Redirect URIs
4. Click **Save credentials**
5. Click **Connect Instagram** and approve access
6. Posts sync into `instagram_posts` and appear on this page

Local redirect URI example:

```text
http://new-world-nursery.test/admin/instagram/callback
```

## How sync works

- Fetches latest posts via Graph API (`/{ig-user-id}/media`)
- Downloads images to `storage/app/public/image/instagram/`
- Upserts `instagram_posts` using `instagram_media_id` (no duplicates)
- Skips pure VIDEO posts (uses first IMAGE in carousels)
- Public API:
  - `GET /api/instagram`
  - `GET /api/home` → `instagram` (and BC alias `gallery`)

## Gallery vs Instagram

| Feature | Table | Admin | API |
|---------|-------|-------|-----|
| Gallery (categorized) | `gallery_items` + `gallery_categories` | Gallery group | `/api/gallery`, `/api/gallery/categories` |
| Instagram feed | `instagram_posts` | Instagram page | `/api/instagram` |

## Automatic sync

```bash
php artisan instagram:sync
```

Scheduled hourly via `routes/console.php`.

## Notes

- App Secret and access tokens are stored **encrypted** in the database
- Images are stored locally so CDN URLs from Instagram do not expire on the frontend
- Development mode Meta apps only work for app roles / testers until App Review is approved
