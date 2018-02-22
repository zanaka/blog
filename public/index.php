<?php
if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
}

require __DIR__ . '/../vendor/autoload.php';

session_start();

// Instantiate the app
$settings = require __DIR__ . '/../src/settings.php';
//Slimのインスタンスを渡してルーティングを出力している
$app = new \Slim\App($settings);

//固定path
$app->get('/hello', function ($request, $response, $args) {
    echo 'Hello,world!';
});

//pathで引数取る “/books/{id}”
//URLで引数を渡す
$app->get('/books/{id}', function ($request, $response, $args) {
    echo "Book ID:" .$args['id'];
});

//可変引数 “/date/[year/[month]]”
$app->get('/news/{year}/{month}', function ($request, $response, $args) {
    echo "Data:" .$args['year']."年".$args['month']."月";
});

//可変引数を画面に表示
$app->get('/news_get[/{year}[/{month}]]', function ($request, $response, $args) {
    // reponds to `/news`, `/news/2016` and `/news/2016/03`
    $uri = $request->getUri();
    $queryParams = $request->getQueryParams();
    print_r($args);
    print_r($queryParams);
});

//POSTメソッド
$container = $app->getContainer();
// viewのコンテナを宣言
$container['view'] = new \Slim\Views\PhpRenderer("../templates/");

// 問い合わせ(初期表示)
$app->get('/question/new', function ($request, $response) {
    $response = $this->view->render($response, "question.php");
    return $response;
});
// 問い合わせ(POSTする時)
$app->post('/question/result', function ($request, $response) {
    $data = $request->getParsedBody();
    $response = $this->view->render($response, "question_result.php", ["data" => $data]);
    return $response;
});


// 画像アップロード(初期表示)
$app->get('/upload/new', function ($request, $response) {
    $response = $this->view->render($response, "upload.php");
    return $response;
});
// 画像アップロード(POSTする時)
$app->post('/upload/result', function ($request, $response) {
    /* @var $request \Slim\Http\Request  */
    $files = $request->getUploadedFiles();
    $uploadFile = $files['file'];
    $fileName = $uploadFile->getClientFilename();
    $targetPath = __DIR__ . '/img/'. $fileName;
    $uploadFile->moveTo($targetPath);
    $response = $this->view->render($response, "upload_result.php", ["fileName" => $fileName]);
    return $response;
});

//セッション使用
// 問い合わせ(初期表示)
$app->get('/question_session/new', function ($request, $response) {
    $response = $this->view->render($response, "question_session.php");
    return $response;
});
// 問い合わせ(POSTする時)
$app->post('/question_session/result', function ($request, $response) {
    $data = $request->getParsedBody();
    $_SESSION['name'] = $data['name'];
    $_SESSION['email'] = $data['email'];
    $_SESSION['description'] = $data['description'];
    $response = $this->view->render($response, "question_session_result.php");
    return $response;
});


// Set up dependencies
require __DIR__ . '/../src/dependencies.php';

// Register middleware
require __DIR__ . '/../src/middleware.php';

// Register routes
require __DIR__ . '/../src/routes.php';

// Run app
$app->run();
