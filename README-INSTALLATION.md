# Marketplace – Milestone 2: Vendor Management & Business Profile

This is a Laravel 13 **Milestone 2 code package**. It is designed to be added to the Laravel project created in Milestone 1. It is not a complete Laravel application and does not include Laravel's vendor/, .env, or framework files.

## Before uploading

1. Back up your Laravel project and database.
2. Confirm Milestone 1 is installed and its `vendors` table exists.
3. Confirm the `Vendor` model, `User` model, `CheckRole` middleware, `layouts.admin`, and `layouts.vendor` exist.
4. Upload/merge the files in this package into the matching locations. Do not replace your whole Laravel project with this ZIP.
5. Review `routes/web.php` and `LAYOUT-NAVIGATION-SNIPPETS.md` and merge the supplied snippets into your existing files.
6. Check that the PHP version is compatible with Laravel 13 (PHP 8.3 or newer).

## Install

From the Laravel project root:

```bash
php artisan optimize:clear
php artisan migrate
php artisan storage:link
php artisan route:list
```

If deploying to shared hosting, run these commands through SSH/terminal if available, or your hosting provider's Laravel deployment facility. Do not run `migrate:fresh` on a database containing data.

## Routes to add

Copy the route definitions from `routes-milestone-2-snippet.php` into the existing `routes/web.php`. Keep the routes already present for authentication and dashboards. Do not replace the complete `web.php` file with the snippet.

## Sidebar links to add

Copy the links from `LAYOUT-NAVIGATION-SNIPPETS.md` into the existing admin and vendor sidebar navigation.

## Pages

- Vendor profile: `/vendor/profile`
- Admin vendor list: `/admin/vendors`
- Admin vendor details: `/admin/vendors/{vendor}`

## Important integration notes

- The migration assumes the Milestone 1 `vendors` table already contains: `id`, `user_id`, `business_name`, `slug`, `business_email`, `phone`, `website_url`, `listing_type`, `gst_number`, `address`, `city`, `state`, `country`, `description`, `logo`, `verification_status`, `status`, and `verified_at`.
- The migration adds the new profile and status-reason fields.
- The model and middleware files replace the corresponding Milestone 1 files. Preserve any additional relationships or methods you have added locally.
- The profile controller uses the `public` filesystem disk. Uploaded files go into `storage/app/public/vendors/`.
- Ensure `storage` and `bootstrap/cache` are writable by the PHP/web-server user.
- The admin routes use route model binding and expect the default `Vendor` primary key.
- The current implementation does not send email notifications or write a complete admin activity log.
- Website URL validation checks URL syntax only. Before fetching/syncing vendor websites, implement SSRF protections and domain/host validation.
- GSTIN validation checks its format only; it does not verify registration with the GST portal.
- Test this on staging before production.

## Test sequence

1. Log in as a vendor and visit `/vendor/profile`.
2. Save business information without uploading images.
3. Upload a logo and banner.
4. Select `Connect Existing Website` and confirm the Website URL is required.
5. Log in as admin and visit `/admin/vendors`.
6. Open a vendor and test approve, reject, suspend, and reactivate.
7. Confirm a suspended vendor cannot access `/vendor/dashboard`.

Do not proceed to Milestone 3 until this milestone works on your server.
