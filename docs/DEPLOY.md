# Lolipop Deploy

## Production URL
- https://eging.rss7.net

## Verified server layout
2026-09-30の実FTP probeで、`eging.rss7.net` のDocumentRootは **`/eging`** と確認済み。
`/eging/public` は存在せず、旧`/public`前提は使用しない。

```
/
└─ eging/                 # eging.rss7.net DocumentRoot
   ├─ index.php
   ├─ admin/
   ├─ api/
   ├─ assets/
   ├─ _private/           # PHP include用。Webアクセスは二重に拒否
   │  ├─ .htaccess
   │  ├─ app/
   │  │  └─ bootstrap.php
   │  ├─ config/
   │  │  └─ config.php   # DB設定済み時だけ生成。commitしない
   │  ├─ database/
   │  │  └─ schema.sql
   │  └─ var/
   │     ├─ sessions/
   │     └─ login-rate/
   └─ ...
```

ローカル開発では `public/_runtime.php` が従来のrepository root（`app/`, `config/`, `database/`）を自動利用する。

## GitHub Environment
Environment: `production`

### Required FTP Secrets
- `LOLIPOP_FTP_HOST`
- `LOLIPOP_FTP_USER`
- `LOLIPOP_FTP_PASSWORD`

### Optional until DB runtime is enabled
以下5つは **全て未設定** または **全て設定済み** のどちらかにする。途中まで設定されている場合はDeployを拒否する。
- `LOLIPOP_DB_HOST`
- `LOLIPOP_DB_NAME`
- `LOLIPOP_DB_USER`
- `LOLIPOP_DB_PASSWORD`
- `EGING_ADMIN_PASSWORD_HASH`

### Variables
- `LOLIPOP_SITE_URL=https://eging.rss7.net`
- `LOLIPOP_FTP_PORT=21`
- `LOLIPOP_DEPLOY_DIR=/eging`
- `LOLIPOP_PRIVATE_DIR=/eging-private`

## Deployment behavior
- `public/` の内容を `/eging/` へ非破壊upload。
- `app/` と `database/` を `/eging-private/` へ配置。
- `/eging-private/.htaccess` は外部Webアクセスを拒否。
- DB/Admin secretsが未設定なら静的公開のみ行い、記事・問い合わせPOST・診断API等のDB依存機能は503で安全に停止。
- 5つのDB/Admin secretsが全て設定済みなら `config/config.php` をActions runner内で生成し、`/eging-private/config/config.php` へuploadする。
- remote delete syncは行わない。

## First deploy
1. `Production Preflight` を実行。
2. FTP/DocumentRoot確認が成功していることを確認。
3. `Deploy to Lolipop` を手動実行。
4. `/`, `/health.php`, `/robots.txt`, `/sitemap.xml` を確認。
5. DB runtimeを有効化した後、`/admin/` にログインし `/admin/install.php` でテーブルを初期化・更新。
6. 診断API、問い合わせフォーム、CMS記事を実確認する。

## Safety
- production secretsはcommitしない。
- `/eging-private` をDocumentRootにしない。
- FTP uploadは非破壊。
- public/private pathが検証済み値と一致しない場合はDeployを拒否する。
