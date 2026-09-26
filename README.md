# エギングギアラボ / EGING GEAR LAB

条件から選ぶ、エギングギア。

## Purpose
エギ・ロッド・リール・ライン・ランディング用品・小物を、季節・潮・風・水深・地形などの条件から選べるエギングギア専門メディア。

## Architecture
- Production: Lolipop rental server
- Backend/CMS: PHP + MySQL
- Frontend: Server-rendered PHP + HTML/CSS + minimal JavaScript
- Simulator: Vanilla JavaScript + structured gear data
- Source of Truth: GitHub
- Deploy: GitHub Actions -> FTPS
- Mobile First

## Why not Astro for this project
ai-master/WEB_DEVELOPMENT.mdではコンテンツ中心の新規サイトはAstroがDEFAULTだが、本ProjectはCMSからの商品データ更新、条件検索、比較データ、シミュレーターとの即時連携を主要要件とする。Lolipop Light上で編集後の再ビルド依存を避けるため、PHP + MySQLの動的構成を採用する。

## Core content
- /gear/ エギ・ロッド・リール・ライン・小物
- /condition/ 春・秋・潮・風・月・水深・地形
- /review/ 個別レビュー
- /compare/ 比較
- /simulator/ 条件逆引きタックル診断
- /admin/ CMS

## Revenue flow
条件記事 -> 必要スペック -> 比較 -> 個別商品 -> EC/アフィリエイトリンク

## Security
Secrets and credentials must never be committed. Production DB/FTP credentials use server-side config or GitHub Secrets.
