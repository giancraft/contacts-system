<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/database.php';

use App\Controller\ContatoController;
use App\Controller\PessoaController;
use App\Core\Router;
use App\Repository\ContatoRepository;
use App\Repository\PessoaRepository;

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

$uri = $_SERVER['REQUEST_URI'];
if (!str_starts_with($uri, '/api/')) {
    readfile(__DIR__ . '/app.html');
    exit;
}

$em = createEntityManager();

$pessoaRepo = new PessoaRepository($em);
$contatoRepo = new ContatoRepository($em);
$pessoaCtrl  = new PessoaController($pessoaRepo);
$contatoCtrl = new ContatoController($contatoRepo, $pessoaRepo);

$router = new Router();

$router->get('/api/pessoas',         fn() => $pessoaCtrl->index());
$router->get('/api/pessoas/{id}',    fn(int $id) => $pessoaCtrl->show($id));
$router->post('/api/pessoas',        fn() => $pessoaCtrl->store());
$router->put('/api/pessoas/{id}',    fn(int $id) => $pessoaCtrl->update($id));
$router->delete('/api/pessoas/{id}', fn(int $id) => $pessoaCtrl->destroy($id));

$router->get('/api/contatos',         fn() => $contatoCtrl->index());
$router->get('/api/contatos/{id}',    fn(int $id) => $contatoCtrl->show($id));
$router->post('/api/contatos',        fn() => $contatoCtrl->store());
$router->put('/api/contatos/{id}',    fn(int $id) => $contatoCtrl->update($id));
$router->delete('/api/contatos/{id}', fn(int $id) => $contatoCtrl->destroy($id));

$router->dispatch($_SERVER['REQUEST_METHOD'], $uri);
