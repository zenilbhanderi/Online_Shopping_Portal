# ShopEasy — PHP + MySQL (Tailwind CDN)
- Role-based login: admin credentials redirect to Admin Dashboard; users to storefront.
- Admin CRUD for products (with image replace), CRUD categories, orders viewer.
- User cart with +/- and auto-remove at 0; checkout creates orders.
- Styling: light brown/white/black palette; hovers & ripple.

## Quick Start (XAMPP)
1. Copy `shopping_portal/` into `htdocs/`.
2. phpMyAdmin → Import `init.sql` (DB `shopping_portal` with seed data + sample orders).
3. Visit: `/shopping_portal/` (store) and `/shopping_portal/admin/login.php` (optional admin login).
4. Logins:
   - Admin: `admin@example.com` / `admin123`
   - User: `user@example.com` / `user123`

Place your images in `assets/images/` and set filenames on products in Admin.
