<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>お問い合わせ確認</title>
</head>
<body >
<h3>お問い合わせ確認</h3><br>
<?= "名前：".$_SESSION['name']; ?><br>
<?= "メールアドレス：".$_SESSION['email']; ?><br>
<?= "内容：".$_SESSION['description']; ?>
<form action="/question_session/new">
    <input type="submit" value="戻る">
</form>
<?php var_dump($_SESSION) ?>
</body>
</html>