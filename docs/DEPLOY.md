# Lolipop Deploy

## Production URL
- https://eging.rss7.net

## Required server files
The repository intentionally does not store production secrets.

Create on the server:
- `config/config.php` based on `config/config.example.php`
- MySQL database using `database/schema.sql`

The production layout assumes the verified subdomain DocumentRoot points to the repository's `public/` directory. Do not guess this path.

Expected server layout:

```
<project-root>/
  app/
  config/
    config.php        # production secret file, never commit
  public/             # eging.rss7.net DocumentRoot
```

## GitHub Environment
Environment: `production`

### Secrets
- `LOLIPOP_FTP_HOST`
- `LOLIPOP_FTP_USER`
- `LOLIPOP_FTP_PASSWORD`

### Variables
- `LOLIPOP_SITE_URL=https://eging.rss7.net`
- `LOLIPOP_FTP_PORT=21`
- `LOLIPOP_DEPLOY_DIR` = verified path ending in `/public`

Do not commit any secret values.

## Before first production deploy
1. Confirm the Lolipop subdomain DocumentRoot exactly.
2. Confirm that DocumentRoot is the intended `.../public` directory.
3. Create/import the MySQL database with `database/schema.sql`.
4. Create `config/config.php` with DB credentials and a password hash.
5. Configure the production GitHub Environment secrets/variables.
6. Run the deploy workflow manually.
7. Verify:
   - `/`
   - `/health.php`
   - `/robots.txt`
   - `/sitemap.php`
   - `/admin/login.php`

The workflow intentionally does not use destructive delete sync.
