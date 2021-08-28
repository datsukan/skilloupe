# Skilloupe

Skilloupeはスキル共有を目的としたWebアプリケーションです。  
管理者と利用者のユーザーを登録して、スキル情報の登録・更新・参照が可能です。

|                                                                                                             |                                                                                                             |
| ----------------------------------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------------------------------- |
| ![1](https://user-images.githubusercontent.com/49118806/131217769-87f35e71-9bec-4b73-aaf9-53ae8565ae09.png) | ![2](https://user-images.githubusercontent.com/49118806/131217771-f0d8ceef-3a83-449e-b0f7-99d81eacf9a1.png) |
| ![3](https://user-images.githubusercontent.com/49118806/131217772-1a9601c2-7660-44d2-b485-bece208efc17.png) | ![6](https://user-images.githubusercontent.com/49118806/131217853-9e56ec05-db77-4272-9830-845cd3508242.png) |
| ![4](https://user-images.githubusercontent.com/49118806/131217774-e9671276-37e1-4ab7-8315-834fbc7d2541.png) | ![5](https://user-images.githubusercontent.com/49118806/131217775-bf859234-c24a-4ae4-90ee-7b9f3b7aa74f.png) |

# Requirement \ 前提要件

- [Docker](https://www.docker.com/get-started)
- [Visual Studio Code](https://azure.microsoft.com/ja-jp/products/visual-studio-code/) （任意）
- [PHP Debug](https://marketplace.visualstudio.com/items?itemName=felixfbecker.php-debug) （任意）

# Installation \ 導入

1. ソースを取得する
```bash
git clone https://github.com/datsukan/skilloupe.git
cd skilloupe
```

2. 環境変数ファイルを作成する
```bash
cp src/.env.example src/.env
```

3. Dockerコンテナを起動する
```bash
docker-compose up -d
```

4. Dockerコンテナに接続して初期設定を行う
```bash
docker exec -it skilloupe-php7 bash
composer install
php artisan key:generate
php artisan migrate
php artisan db:seed --class=InitializationSeeder
chown nginx storage/ -R
npm i
npm run dev
```

5. 画面が表示されて認証可能であることを確認する
   1. Webブラウザで`http:localhost:8000`に接続する
   2. メールアドレスが`abcd@example.com`、パスワードが`password`でログインする

   問題なければ完了。

# Usage \ 使用方法

導入手順の実施後は Docker コンテナとNgrokを起動すればShopifyストア管理ページから使える状態になります。  
使い終わったら Docker コンテナとNgrokを停止してください。  
composer や artisan のコマンドを使用する場合はアプリケーションのコンテナに接続して実行してください。

## 起動

```bash
docker-compose up -d
```

## 停止

```bash
docker-compose stop
```

## アプリケーションコンテナに接続

```bash
docker exec -it skilloupe-php7 bash
```

## サンプルデータの登録

```bash
docker exec -it skilloupe-php7 bash
php artisan db:seed
```

## テスト実行

```bash
./vendor/bin/phpunit
```

## デバッグ

VSCode（拡張機能：PHP Debug 導入済み）のデバッグの実行（F5）でデバッグを開始します。  
任意の箇所にブレークポイントを設定してステップ実行してください。  
処理分岐の確認・特定時点での変数値の参照・Exception のキャッチなどを行えます。

## SQLクライアントでのDBへの接続

例としてDBeaverを用いますが、PostgreSQLをサポートしているソフトであればなんでも使えます。  

1. ローカル環境を起動する ※migrateを実行してない場合は実行しておく
2. [DBeaver](https://dbeaver.io/)のインストーラーをダウンロードする  
   ※Community EditionでOK
3. ダウンロードしたインストーラーを実行してインストールする  
   基本的にデフォルトのまま次へを選択していけば問題ありません。  
   必要に応じて変更してください。  
4. インストールが完了後にDBeaverを起動する
5. [ 上部メニュー > データベース(D) > 新しい接続先 ]をクリックする
6. `PostgreSQL`を選択して`次へ`をクリックする
7. 下記の情報を入力する  
   | 項目       | 値        |
   | ---------- | --------- |
   | Host       | localhost |
   | Port       | 3306      |
   | Database   | skilloupe |
   | ユーザー名 | root      |
   | パスワード | password  |
8. `テスト接続`をクリックする
9. 接続に成功した場合は`接続の詳細`をクリックする
10. `接続先名`に画面上で自分が分かりやすい名称を入力する
11. `接続タイプ`で`test`を選択する  
    ※自分で定義もできるので必要ならカスタマイズしてください
12. `説明`を必要なら入力する
13. `終了`をクリックして設定を保存する
14. 左側に表示されている保存した接続先の`>`をクリックするか、右クリックして`接続する`をクリックする
15. [ 接続先名 > shopify > スキーマ > public > テーブル ]を開く
16. 参照したいテーブル名をダブルクリックする
17. ダブルクリックすると表示される枠は初期表示が`プロパティ`なので、登録されたレコードを見たい場合は`データ`タブを選択する
18. 入力やレコードの選択を行う場合は、画面上の表示に対して直接クリック、ドラッグ等を行う
19. 編集、削除、キャンセル、確定などはウィンドウ下部のバーのアイコンボタンにて操作する

# Note \ 注意事項

開発中

# Author \ 著者

神達 小楠
