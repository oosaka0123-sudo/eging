# 商品写真 権利証跡表（2026-11-11 / PC04 KS04）

結論：**6モデルとも、第三者サイトでの掲載を認める許諾・提携の根拠を確認できなかった。写真は掲載しない。** 各カード・詳細は「写真は掲載許諾の確認待ち」の表示と、該当モデルの写真が載ったメーカー公式ページへのリンクを出す。カタログの正本は `public/_comparison_egis.php`（`photo_page` / `photo_status` / `photo_terms` / `photo_checked_at` / `photo_block_reason`）。

画像の生成・別モデル画像の流用・公式画像の複製やホットリンクは行っていない。

## モデル別

| slug | 公式ページで確認したモデル・号数・タイプ | 公式写真ページ | 適用される利用条件 | 判定 |
|---|---|---|---|---|
| yamashita-k-basic | エギ王 K 3.5号 ベーシック 22g 約3秒/m | https://www.yamaria.co.jp/yamashita/product/detail/522 | https://www.yamaria.co.jp/about 「著作権等」：掲載データ（文書・図形・デザイン等）の無断転載・複製・頒布等をしないこと | 不可（許諾なし） |
| yamashita-k-shallow | エギ王 K シャロー 3.5号S 20g 約6.0秒/m | https://www.yamaria.co.jp/yamashita/product/detail/523（S/SS共通ページ） | 同上 | 不可（許諾なし） |
| yamashita-k-super-shallow | エギ王 K スーパーシャロー 3.5号SS 19.5g 約8.0秒/m | https://www.yamaria.co.jp/yamashita/product/detail/523（S/SS共通ページ） | 同上 | 不可（許諾なし） |
| shimano-clinch-flashboost | セフィア クリンチ フラッシュブースト 3.5号 品番QE-X35U | https://fish.shimano.com/ja-JP/product/lure/shoreeging/egi/a155f00000c5d08qaf.html | https://www.shimano.com/jp/term_of_use.html 「著作権」：書面による明確な許可がない限り画像を含むサイトのいかなる部分も複製・公表・送信等できず、他のウェブサイトで用いることは一切禁止 | 不可（書面許可なし） |
| daiwa-amorous-joint | エメラルダス アモラスジョイント 3.5号 22.5g 3.75秒/m（16カラー） | https://www.daiwa.com/jp/product/thtjj6t | 利用許諾の規定は見つからず。表示は © GLOBERIDE, Inc. All Rights Reserved.（知的財産方針 https://www.globeride.co.jp/ip_policy/ はアイデア提案の扱いのみ） | 不可（許諾なし＝権利留保） |
| shimano-clinch-deep-flashboost | セフィア クリンチ ディープ フラッシュブースト 3.5号 品番QE-D35V 約1.9秒/m | https://fish.shimano.com/ja-JP/product/lure/shoreeging/egi/a155f00000ccfveqab.html | シマノ利用条件（上記） | 不可（書面許可なし） |

確認方法：ヤマリア・ダイワはHTTP取得、シマノはAkamaiが自動取得を403で拒否するため、PC04 Chrome（CDP 9222・未ログインの閲覧のみ）で表示して確認した。

## 正式アフィリエイト素材（Yahoo!ショッピング／ValueCommerce）

- 2026-11-11、PC04 Chrome（プロファイル `C:\ChromeAutoVC`、CDP 9222）で https://aff.valuecommerce.ne.jp/ を開いたところ**ログイン画面**。サイト「エギングギアラボ」の登録状況・Yahoo!ショッピング提携の有無は確認できなかった。
- このため、提携発行URL（`affiliate_url` + `affiliate_verified=true`）と商品画像（`photo_license_type=yahoo-affiliate`）はどちらも未登録のまま。通常のYahoo検索リンクは「検索」として表示し、PR表示はしない。
- 別の登録サイトIDの広告素材は流用していない。認証情報・Cookieは参照・記録していない。

## 掲載に進むための条件（本人操作）

1. **ValueCommerce**：本人がPC04 Chrome（`C:\ChromeAutoVC`）でログイン → 「エギングギアラボ」がYahoo!ショッピングと提携済みか確認 → 6モデルそれぞれ、号数・タイプ・カラーが一致する商品のMyLink（発行URLと商品画像URL）を発行。
2. または **メーカー**（ヤマリア／シマノ／グローブライド）に、エギングギアラボでの製品画像掲載許諾を書面（メール可）で申請し、条件を記録。
3. または自分で購入・撮影した写真。

許諾取得後の登録手順は `docs/PRODUCT_PHOTO_RELEASE.md` を参照。
