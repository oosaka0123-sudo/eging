# Lolipop Deploy

## Production URL
- https://eging.rss7.net

## Server layout
The verified subdomain DocumentRoot must point to the repository's `public/` directory.

Expected layout:

```
<project-root>/
  app/
  config/
    config.php        # generated during deploy, never committed
  database/
    schema.sql        # deployed outside web root for admin installer
  public/             # eging.rss7.net DocumentRoot
```

## GitHub Environment
Environment: `production`

### Secrets
- `LOLIPOP_FTP_HOST`
- `LOLIPOP_FTP_USER`
- `LOLIPOP_FTP_PASSWORD`
- `LOLIPOP_DB_HOST`
- `LOLIPOP_DB_NAME`
- `LOLIPOP_DB_USER`
- `LOLIPOP_DB_PASSWORD`
- `EGING_ADMIN_PASSWORD_HASH`

### Variables
- `LOLIPOP_SITE_URL=https://eging.rss7.net`
- `LOLIPOP_FTP_PORT=21`
- `LOLIPOP_DEPLOY_DIR` = verified path ending in `/public`

Production `config/config.php` is generated only inside the GitHub Actions runner from Environment secrets, uploaded to the server, and never committed.

## First deploy
1. Confirm the Lolipop subdomain DocumentRoot exactly.
2. Confirm that DocumentRoot is the intended `.../public` directory.
3. Create the MySQL database in Lolipop.
4. Configure the production GitHub Environment.
5. Generate the admin password hash with PHP `password_hash(..., PASSWORD_DEFAULT)` and save only the hash as `EGING_ADMIN_PASSWORD_HASH`.
6. Run the deploy workflow manually.
7. Log in to `/admin/` and open `/admin/install.php` to create the tables.
8. Verify:
   - `/`
   - `/health.php`
   - `/robots.txt`
   - `/sitemap.php`
   - `/admin/login.php`

The workflow intentionally uses non-destructive upload and refuses deployment unless the directory ends in `/public`.
