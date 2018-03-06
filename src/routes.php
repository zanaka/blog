<?php
use Slim\Http\Request;
use Slim\Http\Response;

// Routes

// トップページの表示
$app->get('/', function (Request $request, Response $response) {
    return $this->renderer->render($response, 'home/top.phtml');
});

// 一覧表示
$app->get('/tickets', function (Request $request, Response $response) {
    $sql = 'SELECT * FROM tickets';
    $stmt = $this->db->query($sql);
    $tickets = [];
    while($row = $stmt->fetch()) {
        $tickets[] = $row;
    }
    $data = ['tickets' => $tickets];
    return $this->renderer->render($response, 'tickets/index.phtml', $data);
});

// 新規作成用フォームの表示
$app->get('/tickets/create', function (Request $request, Response $response) {
    return $this->renderer->render($response, 'tickets/create.phtml');
});

// 新規作成
$app->post('/tickets', function (Request $request, Response $response) {
    $title = $request->getParsedBodyParam('title');
    $contents = $request->getParsedBodyParam('contents');

    // ここに保存の処理を書く
    $sql = 'INSERT INTO tickets (title,contents) values (:title,:contents)';

    // コンテナに登録したPDOのオブジェクトは$this->dbでアクセスできる
    $stmt = $this->db->prepare($sql);
    $result = $stmt->execute(['title' => $title, 'contents' => $contents]);
    if (!$result) {
        throw new \Exception('could not save the ticket');
    }

    // 保存が正常にできたら一覧ページへリダイレクトする
    return $response->withRedirect("/tickets");
});

// 表示
$app->get('/tickets/{id}', function (Request $request, Response $response, array $args) {
    $sql = 'SELECT * FROM tickets WHERE id = :id';
    $stmt = $this->db->prepare($sql);
    $stmt->execute(['id' => $args['id']]);
    $ticket = $stmt->fetch();
    if (!$ticket) {
        return $response->withStatus(404)->write('not found');
    }
    $data = ['ticket' => $ticket];
    return $this->renderer->render($response, 'tickets/show.phtml', $data);
});

// 編集用フォームの表示
$app->get('/tickets/{id}/edit', function (Request $request, Response $response, array $args) {
    $sql = 'SELECT * FROM tickets WHERE id = :id';
    $stmt = $this->db->prepare($sql);
    $stmt->execute(['id' => $args['id']]);
    $ticket = $stmt->fetch();
    if (!$ticket) {
        return $response->withStatus(404)->write('not found');
    }
    $data = ['ticket' => $ticket];
    return $this->renderer->render($response, 'tickets/edit.phtml', $data);
});

// 更新
$app->put('/tickets/{id}', function (Request $request, Response $response, array $args) {
    $sql = 'SELECT * FROM tickets WHERE id = :id';
    $stmt = $this->db->prepare($sql);
    $stmt->execute(['id' => $args['id']]);
    $ticket = $stmt->fetch();
    if (!$ticket) {
        return $response->withStatus(404)->write('not found');
    }
    $ticket['title'] = $request->getParsedBodyParam('title');
    $ticket['contents'] = $request->getParsedBodyParam('contents');

    $stmt = $this->db->prepare('UPDATE tickets SET title = :title, contents = :contents WHERE id = :id');
    $stmt->execute($ticket);
    return $response->withRedirect("/tickets");
});

// 削除
$app->delete('/tickets/{id}', function (Request $request, Response $response, array $args) {
    $sql = 'SELECT * FROM tickets WHERE id = :id';
    $stmt = $this->db->prepare($sql);
    $stmt->execute(['id' => $args['id']]);
    $ticket = $stmt->fetch();
    if (!$ticket) {
        return $response->withStatus(404)->write('not found');
    }
    $stmt = $this->db->prepare('DELETE FROM tickets WHERE id = :id');
    $stmt->execute(['id' => $ticket['id']]);
    return $response->withRedirect("/tickets");
});