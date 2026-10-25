# 商品写真の公開手順（2026-10-25）

## ゴール
`/compare.php` の5商品・深場の追加1商品の計6モデルの**正しい実物写真**を、比較カードと個別商品ページに掲載する。製品名、号数、カラーの取り違えを防ぐ。

## 必須の権利確認
メーカー公式サイトや販売店の画像が閲覧可能でも、第三者サイトへの複製・ホットリンク・商用転載が許可されたことにはなりません。シマノのサイト利用条件では商用転載に書面での許可が必要。画像URLを調べただけで勝手にアップロードしない。

各製品の写真に対して次のどれかを確認：
1. 自分が撮影し、公開できる写真。
2. メーカー・販売店から、エギングギアラボでの掲載許諾を得た画像（条件を記録）。
3. 正式アフィリエイト契約で許可された画像（商品リンク・表示方法等の利用条件を守る）。

**商品名だけの色不特定カードには、別製品や別タイプの写真を代用しない。** カラー・号数・タイプの一致を確認する。

## 登録（GitHub経由、Desktop Commander不要）
- 公開許諾を得た商品画像を GitHub `public/assets/img/products/` にアップロードする（例: `yamashita-k-basic.webp`）。
- `public/_comparison_egis.php` の該当モデルに以下を追加：
  ```php
  'photo_url'=>'/assets/img/products/yamashita-k-basic.webp',
  'photo_permission'=>true,
  'photo_rights_source'=>'撮影者／利用許諾の記録または正式提携契約の識別情報',
  ```
- `photo_rights_source` にはパスワード・トークン・個人の秘密情報を入れない。非公開の詳細証跡は安全な内部文書に保持。
- 画像は外部の任意URLより、GitHubにあるファイルを推奨。写真とカタログの変更を同じPRに含める。
- `egl_photo()` は権利確認フラグ・根拠の有無・実在する画像ファイルを確認してから、比較・商品詳細で自動表示する。未登録の商品は「写真未登録」と表示し、メーカー公式写真へのリンクを残す。

## 現在のモデル識別子
- `yamashita-k-basic`
- `yamashita-k-shallow`
- `yamashita-k-super-shallow`
- `shimano-clinch-flashboost`
- `daiwa-amorous-joint`
- `shimano-clinch-deep-flashboost`

## 公開前チェック
- PHP syntax、`condition-journey.mjs`、Lighthouseが成功する。
- 比較5件／3件と詳細6件の画像がそれぞれ正しい型番を示していることを目視確認する。
- 画像404、読み込み失敗、縦横比の崩れ、別カラー・号数の取り違えがないこと。
- ロリポップへは正規の `rss7-ai-works` broker FTPS workflowでデプロイする。既存DBやSecretは触らない。

## 現在の状態
画像6枚の許諾・素材取得はまだ完了していない。**本手順とコード変更は画像を自動生成したり、未許諾の写真を転載したりするものではない。**
