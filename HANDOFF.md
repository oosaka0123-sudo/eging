# EGING GEAR LAB — 最新引き継ぎ (2026-10-21 JST)

## 正本と現在のサイト構成

- Source: `oosaka0123-sudo/eging` main
- 本番: https://eging.rss7.net/
- 実デプロイ: `oosaka0123-sudo/rss7-ai-works` の `.github/workflows/deploy-eging.yml`。GitHub Actionsで`eging` mainをチェックアウトしてFTPSへ非破壊アップロード。
- 方向性: `docs/SITE_DIRECTION_COUNCIL_2026-10-14.md` と `README.md` を読む。
- PC03が原則primary。PC03 Remote Desktop Commanderの使用量上限に達している場合、live-confirmed PC04 (ks04)を補助利用。Operaは使わずChromeを使用。
- 2026-10-14 core site PR #34 merged: https://github.com/oosaka0123-sudo/eging/pull/34
- 2026-10-14 production deploy succeeded: https://github.com/oosaka0123-sudo/rss7-ai-works/actions/runs/38518537721
- Mobile first-fold density patch PR #35 merged/deployed/live-checked: https://github.com/oosaka0123-sudo/eging/pull/35
- Official product photo CTA/source refresh PR #36 merged: https://github.com/oosaka0123-sudo/eging/pull/36
- 2026-10-21 broker release: https://github.com/oosaka0123-sudo/rss7-ai-works/actions/runs/39540092425 （GitHub Actions success 結果を最終確認するまで完了扱いにしない）

## 実装と確認した証拠

- 公開入口 `/` → `/conditions.php` で「浅場」「堤防・中層」「深め・潮流」。比較 `/compare.php` は3.5号エギ5機種、`?scene=shallow` / `standard` / `current` は3機種。各モデルから `/gear-compare-item.php?model=...`。
- 比較と各詳細は `public/_comparison_egis.php` が単一の暫定公開ソースで、MySQL認証が失敗しても動作。プライベートのCMS・DBはそのまま保全。
- 個別商品は公称重量・沈下速度・出典・向く条件・注意点・メーカー公式へのリンクを表示。Yahooリンクは**通常の型番検索**であり未提携のアフィリエイトリンクと偽らない。
- 商品別の製品写真は未掲載。モデル固有写真の再利用許諾が検証できるまでは型番プレースホルダー。汎用参照写真による誤認を避けた。
- PR #34 CI PHP lint＋Playwright browser smoke＋3/5比較～5商品の詳細 E2E＋Lighthouseすべて成功。PC04 Playwright Chrome実本番確認 390px/1440pxで /、/compare.php、/gear-compare-item.php は HTTP 200／JS page errors 0／horizontal overflow 0。PR #35後の公開URLも2026-10-21にHTTP 200・CSS r2・3/5商品表示で再確認済み。
- Live `/sitemap.xml` HTTP 200。DB 1045は解決していないが、CMS項目のみスキップしてXMLを提供。

- 2026-10-21 live /health.php = `db: auth_failed`（DB接続問題継続）。5モデルすべての個別詳細はHTTP 200、公式ページ・Yahoo通常検索リンクを確認。比較 `/compare.php?audit=20261021-v3` はメーカー写真ページ案内5件と公称値の確認日を確認。

## 未完了 / ブロッカー

1. **商品固有の実物写真**: 権利／契約条件未確認。Yahoo!アフィリエイト写真は利用規約上、アフィリエイトリンクと一緒に表示する条件等があるため、提携後に正確な商品画像を取得する。
2. **Yahooアフィリエイト収益化**: 発行済み正規の提携商品URLはまだ登録されていない。現在の通常検索リンクはアフィリエイトではない。
3. **MySQL接続**: ロリポップ MySQLユーザー認証エラー SQLSTATE 1045。対象DB名・ホスト・ユーザー名は以前ロリポップ画面とGitHub varsで照合済み。Secretの現行値一致は未確認。既存共有DBパスワードやIAMは無承認変更しない。
4. **CMSからの新規商品登録・7条件動的診断**: DB接続の復旧＋E2E検証が必須。比較画面を二重化しない。
5. **ロリポップ／ValueCommerceログイン**: PC04のChrome CDPでは2026-10-21時点で両方ログイン画面。本人のログイン操作が必要。認証情報はチャット・ログ・GitHubに表示しない。

## Next

- PR #36 のbroker FTPS Deployを完了確認。比較ページの公式写真CTA 5件、製品公称値の再確認日 2026-10-21、通常URLと5商品の詳細を最終検証。
- 商品のライセンス・正式な提携リンクをユーザー側の登録済みパートナー経路から、安全に確認して商品ごとに追加する（Secretやログイン情報を公開しない）。
- DB authは共有サーバーの影響を調べたうえで別タスクとして回復させる。
