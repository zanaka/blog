<?php

namespace Classes\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;

class TicketsController extends Controller
{
    // トップ表示
    public function top(Request $request, Response $response)
    {
        return $this->renderer->render($response, 'home/top.phtml');
    }

    // 一覧表示
    public function index(Request $request, Response $response)
    {
        $sql = 'SELECT * FROM tickets ORDER BY date DESC';
        $stmt = $this->db->query($sql);
        $tickets = [];
        while($row = $stmt->fetch()) {
            $tickets[] = $row;
        }
        $data = ['tickets' => $tickets];
        return $this->renderer->render($response, 'tickets/index.phtml', $data);
    }

    // 新規作成フォームの表示
    public function create(Request $request, Response $response)
    {
        return $this->renderer->render($response, 'tickets/create.phtml');
    }

    //新規作成
    public function store(Request $request, Response $response)
    {
        //$title = $request->getParsedBodyParam('title');
        //$contents = $request->getParsedBodyParam('contents');

        $body = $request->getParsedBody();

        //$bodyの中身
        //array(2) { ["menu"]=> string(4) "soba" ["num"]=> string(1) "1" }

        $menu = $body['menu'];
        $num = $body['num'] ?: "0";

        // ここに保存の処理を書く
        $sql = 'INSERT INTO tickets (menu,num) values (:menu,:num)';

        // コンテナに登録したPDOのオブジェクトは$this->dbでアクセスできる
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute(['num' => $num]);
//        $result = $stmt->execute(['menu' => $menu , 'num' => $num]);
        if (!$result) {
            throw new \Exception('could not save the ticket');
        }

        // 保存が正常にできたら一覧ページへリダイレクトする
        return $response->withRedirect("/tickets");
    }

    // 詳細表示(1件ごと)
    public function show(Request $request, Response $response, array $args)
    {
        $sql = 'SELECT * FROM tickets WHERE id = :id';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $args['id']]);
        $ticket = $stmt->fetch();
        if (!$ticket) {
            return $response->withStatus(404)->write('not found');
        }
        $data = ['ticket' => $ticket];
        return $this->renderer->render($response, 'tickets/show.phtml', $data);
    }

    // 編集用フォームの表示
    public function edit(Request $request, Response $response, array $args)
    {
        $sql = 'SELECT * FROM tickets WHERE id = :id';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $args['id']]);
        $ticket = $stmt->fetch();
        if (!$ticket) {
            return $response->withStatus(404)->write('not found');
        }
        $data = ['ticket' => $ticket];
        return $this->renderer->render($response, 'tickets/edit.phtml', $data);
    }

    // 更新
    public function update(Request $request, Response $response, array $args)
    {
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
    }

    // 削除
    public function delete(Request $request, Response $response, array $args)
    {
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
    }

}
