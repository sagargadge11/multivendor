# Phase 3.1 – Global Categories and Product Approval

This is an overlay for the supplied Laravel 13 marketplace project. It reuses the existing `categories`, `products`, and `product_approvals` tables. It does not create a duplicate category table.

## Included
- Admin global category list, create, edit and delete screens.
- Parent category / subcategory selection.
- Category SEO title, description, keywords and robots fields.
- Product review queue.
- Admin assigns a global category while approving a product.
- Approval and rejection are recorded in `product_approvals`.
- Admin navigation links for Global Categories and Product Review.

## Copy files
Copy the folders from this ZIP into the root of your existing Laravel project, keeping the same folder structure. Allow the supplied files to replace these existing files:
- `app/Models/Category.php`
- `app/Models/Product.php`
- `routes/web.php`
- `resources/views/layouts/admin.blade.php`

Do not delete any other existing project files.

## Deploy
1. Commit and push the overlay files to the same GitHub branch connected to Render.
2. Wait for Render deployment to finish.
3. Run the new migration against the existing PostgreSQL database:
   `php artisan migrate --force`
4. Do not run `migrate:fresh` or `db:wipe`. Those commands can delete existing data.

If you still have the temporary protected `/run-marketplace-migrations/{key}` route in `routes/web.php`, it can run migrations, but it also runs the database seeder. Use it only if needed and remove that temporary route immediately afterwards. Remove `MIGRATION_SECRET` from Render after use. Prefer a one-time migration-only mechanism; never expose a public migration route permanently.

## Test
1. Log in as admin.
2. Open `/admin/categories`.
3. Create a main category, e.g. Jewellery.
4. Create a child category, e.g. Gold Jewellery, and select Jewellery as its parent.
5. Open `/admin/products`.
6. Open a pending product, select an active global category, and click Approve & Publish Product.
7. Confirm the product is approved and its global category is assigned.

## Important notes
- This package is Phase 3.1 only. It does not yet add vendor-created category management, public category listing, storefront branding, enquiry management, or AdSense placements.
- Existing `products.category_id` is used as the global marketplace category ID. No existing product category values are overwritten by the migration.
- Vendor-specific category storage and mapping will be added separately so the vendor's original category and the marketplace global category remain distinct.
- SEO fields are stored now. Public page `<title>` and meta tag rendering will be connected when the public category and product pages are implemented.
- The product review search uses PostgreSQL `ILIKE`, matching your Render PostgreSQL database.
