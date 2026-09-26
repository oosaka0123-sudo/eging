# Lolipop Deploy

## Required server files
The repository intentionally does not store production secrets.

Create on the server:
- `config/config.php` based on `config/config.example.php`
- MySQL database using `database/schema.sql`

Because only `public/` is deployed by the default workflow, place `app/` and `config/` outside the web root manually or adjust the server layout before enabling production deploy.

## GitHub Secrets
- LOLIPOP_FTP_HOST
- LOLIPOP_FTP_USER
- LOLIPOP_FTP_PASSWORD
- LOLIPOP_FTP_DIR

Do not commit any of these values.

## Before enabling automatic deploy
1. Confirm server directory layout.
2. Confirm PHP can require app/bootstrap.php outside web root.
3. Import database/schema.sql.
4. Create config.php with DB credentials and password hash.
5. Replace example.com in site metadata / robots / sitemap.
6. Run manual workflow first.
7. Verify production pages and admin login.
