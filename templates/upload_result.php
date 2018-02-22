
<!DOCTYPE html>
<head>
    <title>画像</title>
</head>
<body>
<h1>画像アップロード成功</h1>

<?php echo "画像です" ?><br>
<img src="/img/<?=$fileName ?>">

<form action="/upload/new">
    <input type="submit" value="戻る">
</form>
</body>
</html>