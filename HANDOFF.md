# EGING GEAR LAB — 最新引き継ぎ (2026-10-14 JST)

## 正本と現在のサイト構成

- Source: `oosaka0123-sudo/eging` main
- 本番: https://eging.rss7.net/
- 実デプロイ: `oosaka0123-sudo/rss7-ai-works` の `.github/workflows/deploy-eging.yml`。GitHub Actionsで`eging` mainをチェックアウトしてFTPSへ非破壊アップロード。
- 方向性: `docs/SITE_DIRECTION_COUNCIL_2026-10-14.md` と `README.md` を読む。
- PC03が原則primary。PC03 Remote Desktop Commanderの使用量上限に達している場合、live-confirmed PC04 (ks04)を補助利用。Operaは使わずChromeを使用。
- 2026-10-14 core site PR #34 merged: https://github.com/oosaka0123-sudo/eging/pull/34
- 2026-10-14 production deploy succeeded: https://github.com/oosaka0123-sudo/rss7-ai-works/actions/runs/38518537721
- Mobile first-fold density patch PR #35: https://github.com/oosaka0123-sudo/eging/pull/35 （公開成功・実URL検証まで完了扱いにしない）

## 実装と確認した証拠

- 公開入口 `/` → `/conditions.php` で「浅場」「堤防・中層」「深め・潮流」。比較 `/compare.php` は3.5号エギ5機種、`?scene=shallow` / `standard` / `current` は3機種。各モデルから `/gear-compare-item.php?model=...`。
- 比較と各詳細は `public/_comparison_egis.php` が単一の暫定公開ソースで、MySQL認証が失敗しても動作。プライベートのCMS・DBはそのまま保全。
- 個別商品は公称重量・沈下速度・出典・向く条件・注意点・メーカー公式へのリンクを表示。Yahooリンクは**通常の型番検索**であり未提携のアフィリエイトリンクと偽らない。
- 商品別の製品写真は未掲載。モデル固有写真の再利用許諾が検証できるまでは型番プレースホルダー。汎用参照写真による誤認を避けた。
- PR #34 CI PHP lint＋Playwright browser smoke＋3/5比較～5商品の詳細 E2E＋Lighthouseすべて成功。PC04 Playwright Chrome実本番確認 390px/1440pxで /、/compare.php、/gear-compare-item.php は HTTP 200／JS page errors 0／horizontal overflow 0。※PR #35後は再確認が必要。
- Live `/sitemap.xml` HTTP 200。DB 1045は解決していないが、CMS項目のみスキップしてXMLを提供。

## 未完了 / ブロッカー

1. **商品固有の実物写真**: 権利／契約条件未確認。Yahoo!アフィリエイト写真は利用規約上、アフィリエイトリンクと一緒に表示する条件等があるため、提携後に正確な商品画像を取得する。
2. **Yahooアフィリエイト収益化**: 発行済み正規の提携商品URLはまだ登録されていない。現在の通常検索リンクはアフィリエイトではない。
3. **MySQL接続**: ロリポップ MySQLユーザー認証エラー SQLSTATE 1045。対象DB名・ホスト・ユーザー名は以前ロリポップ画面とGitHub varsで照合済み。Secretの現行値一致は未確認。既存共有DBパスワードやIAMは無承認変更しない。
4. **CMSからの新規商品登録・7条件動的診断**: DB接続の復旧＋E2E検証が必須。比較画面を二重化しない。
5. **PR #35**: CSS・UI密度の改善とキャッシュbusterの変更。CI / Merge / Deploy / Live screenshotが通ったら完了判定。

## Next

- PR #35 の CI PHP・browser・Lighthouse成功確認 → merge → central broker FTPS deploy → PC04でHTTPS通常URLの確認・390/1440スクリーンショットを再検証。
- 商品のライセンス・正式な提携リンクをユーザー側の登録済みパートナー経路から、安全に確認して商品ごとに追加する（Secretやログイン情報を公開しない）。
- DB authは共有サーバーの影響を調べたうえで別タスクとして回復させる。
