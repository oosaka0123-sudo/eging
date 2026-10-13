# EGING GEAR LAB — 作業引き継ぎ（2026-10-13）

## 本番サイトとデプロイ

- 正本: https://github.com/oosaka0123-sudo/eging （main）
- 本番: https://eging.rss7.net/
- **実稼働の本番デプロイ**: 別Repo `oosaka0123-sudo/rss7-ai-works` の GitHub Actions `.github/workflows/deploy-eging.yml` を `workflow_dispatch` で実行。ソースは `eging` の最新 main をcheckoutし、FTPSで `/eging` と `/eging-private` に非破壊ミラー。
- PC03（ks-03）から `gh workflow run deploy-eging.yml -R oosaka0123-sudo/rss7-ai-works --ref main` を実行できる。Operaは使わない。
- 2026-10-13 公開成功: https://github.com/oosaka0123-sudo/rss7-ai-works/actions/runs/38345952663
- 2026-10-13 キャッシュ対策の再デプロイ: https://github.com/oosaka0123-sudo/rss7-ai-works/actions/runs/38346459388 （最新の実行結果を必ず確認）

## 写真ページの現在地

- `/gear.php` : Wikimedia Commonsから、許諾の明確な参考写真（エギ／ロッド＋リール）2点を表示。写真には出典・ライセンスを明示。
- `/compare.php` : 本来は CMSの `gear_items` の公開レコードに紐づく `specs.image_url` を3列のギャラリーに表示。写真と商品名のリンク先は自サイト `/gear-item.php?slug=...`。
- DB接続不調または公開商品なしの時は `/compare.php` に、正規に再利用できる参考写真2点と注記を表示するフォールバックを2026-10-13に追加。**この2点はメーカー製品固有の写真ではなく参考写真**。全商品の写真登録が完了したという意味ではない。
- `public/compare.php` と `public/gear-item.php` にはロリポップ！アクセラレータの古いPHPキャッシュを防ぐ `Cache-Control: no-cache, no-store, must-revalidate, max-age=0` を追加済み。
- 通常URLが古い場合、`/compare.php?check=20261013` のような別クエリでオリジン最新版を確認。ロリポップ公式のキャッシュ削除機能はユーザー専用ページにログインして対象ドメインの設定から実行可能。**他ドメインのキャッシュや設定には触らない**。

## 最重要の未解決課題

1. **MySQL認証エラー**: `Configure Eging Runtime` の過去の診断結果は MySQL `1045` (`HY000`)。対象はロリポップ MySQL 8.4の `LA03517425-net`。DB名、ホスト名、ユーザー名は管理画面とEnvironment変数で照合済み。
2. GitHub ActionsのDBパスワード Secret `EGING_DB_PASSWORD` は `rss7-ai-works` Repoの `production` 環境に存在するが、2026-10-13昼時点で更新タイムスタンプが2026-10-12 00:16:49Zのまま。実際にロリポップ側と一致しているか確認できていない。ユーザーが設定成功と述べた際も更新が反映された証拠は得られなかった。
3. **機密値をチャット・Issue・Actionsログ・公開ファイルへ書かない**。共有DBのパスワード自体を安易に変更しない。認証画面経由の自動Secret転送は安全性チェックによりブロックされたことがあり、許可されない経路での回避はしない。
4. Secretを正しく登録後、`rss7-ai-works` の `Configure Eging Runtime` を再実行 → DB正常接続確認 → 本番 `/admin/install.php`（管理者認証付き、既存データを壊さない事前確認）でDBスキーマ整備 → 商品登録の順。
5. メーカー固有の実物写真は、メーカー公式情報の**存在確認**とサイトへの**転載・埋込み許諾**は別。適切なメーカー素材利用許諾か提携アフィリエイト規約に基づく画像だけ `specs.image_url` に登録する。未許可の画像を転載しない。写真の出典を `specs.image_source` に記録し、最新メーカー公称スペックは一次ソースで照合する。

## 完了条件

- GitHubのCI成功、Deploy成功、実ページHTTP 200・見た目・写真URL読込、DB正常接続とCMS公開レコード、商品固有写真の権利確認、比較写真・商品名→自サイト詳細ページの導線を本番実測。
- コードをGitHubへ保存しただけで「完成」と言わない。
