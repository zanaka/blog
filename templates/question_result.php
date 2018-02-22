<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>お問い合わせ確認</title>
</head>
<body >
<h3>お問い合わせ確認</h3>
<?php echo "名前：".$data['name'] ?>
<br>
<?="メールアドレス：".$data['email'] ?>
<form action="/question/new">
    <input type="submit" value="戻る">
</form>

</body>
</html>