# エギングギアラボ / EGING GEAR LAB

**条件から選ぶ、エギングギア。**

https://eging.rss7.net/

## このサイトの目的

釣り場の条件から、自分に合うエギを **3〜5モデルに絞って比較し、購入を判断する** 専門サイト。現状は3.5号エギ5モデルを対象とします。幅広い釣具の網羅や実釣ランキングを装わないこと。

### ユーザー導線

TOP `/` → 「浅場・藻場」「堤防・中層」「深め・潮流」 → `/compare.php?scene=...`（3件）または `/compare.php`（5件） → `/gear-compare-item.php?model=...` → メーカー公式情報・Yahoo!ショッピングの通常検索。

- 製品固有写真は権利の確認後のみ追加する。未確認は製品名付きプレースホルダー。汎用写真を製品固有写真と偽らない。
- Yahoo!の現行リンクは**通常の検索**。バリューコマース等のアフィリエイトリンクと混同しない。正規提携を実確認できたURLを登録した時だけPR表記で出す。
- 季節や深場で最適商品を断定しない。専用ディープ製品など未掲載の適合製品があることを明示。
- スペックはメーカー公称値。出典・確認日を表示し、実釣測定と混同しない。

## 技術とデータの正本

- 公開側：軽量 PHP + HTML / CSS + 最小限JS、Mobile First。
- 比較/詳細の**暫定公開データの唯一の正本**：`public/_comparison_egis.php`（3.5号5モデル）。画面は共通 `public/partials/egi-functions.php` と `egi-card.php` を参照。
- **長期計画はCMS (PHP + MySQL)**。現行のDBユーザー認証1045のため、公開比較からMySQL依存を一時的に外しています。CMS本体・既存DBを削除しない。復旧後はデータの整合検証と移行を行い、静的版とCMS版を二重表示しない。
- 旧7条件DBシミュレーター `/simulator.php` は、検証済みの `/conditions.php` に一時的に302転送。復旧後にE2Eテストを通した時だけ再公開可能。
- GitHubがソースコードの正本。

## 主要ページ

- `/` — 何を比較できるかと条件選択
- `/conditions.php` — 条件の入口
- `/compare.php` — 3/5製品比較
- `/gear-compare-item.php?model=...` — 各製品の詳細
- `/guide.php` — 号数・沈下速度の基礎知識
- `/concept.php` — 編集方針・出典・PRポリシー
- `/gear.php` — ギアの役割整理（未整備カテゴリを明記）

## テスト・公開

- CI `.github/workflows/ci.yml`: PHP lint、Secretガード、Playwright browser smoke (390/1440)、条件選択→3/5比較→詳細→404・旧URL転送のE2Eテスト。
- Lighthouse Quality `.github/workflows/lighthouse.yml`。
- 本番デプロイ正本は **`oosaka0123-sudo/rss7-ai-works` の `deploy-eging.yml`**。エギングRepoの古いPreflight/Deployを実働と混同しない。詳細 `docs/DEPLOY.md`。
- 本番はロリポップ！アクセラレータによる旧HTMLキャッシュが最大10分程度残る可能性がある。公開はCI成功＋broker Deploy成功＋実URLでの確認をもって判定する。

## 改修判断の記録

- `docs/SITE_DIRECTION_COUNCIL_2026-10-14.md`（PC04でChatGPT/Claude/Gemini 3者の方向性レビュー）
- `HANDOFF.md`（最新の安全上の未解決課題）

## 禁止事項

- 未確認の画像転載、加工、製品写真の偽装
- 偽のレビュー、釣果・最安値・在庫・提携リンクの創作
- Shared DB Password/Secretの安易な変更
- 公開側で静的データとCMSを二重の正本として併走
