<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\BaseController;
use App\Entity\Contato;
use App\Repository\ContatoRepository;
use App\Repository\PessoaRepository;

class ContatoController extends BaseController
{
    public function __construct(
        private ContatoRepository $contatoRepo,
        private PessoaRepository  $pessoaRepo
    ) {}

    public function index(): void
    {
        $pessoaId = isset($_GET['pessoaId']) ? (int)$_GET['pessoaId'] : null;

        if ($pessoaId) {
            $pessoa = $this->pessoaRepo->findById($pessoaId);
            if (!$pessoa) {
                $this->error('Pessoa não encontrada.', 404);
                return;
            }
            $contatos = $this->contatoRepo->findByPessoa($pessoa);
        } else {
            $contatos = $this->contatoRepo->findAll();
        }

        $this->success(array_map(fn($c) => $c->toArray(), $contatos));
    }

    public function show(int $id): void
    {
        $contato = $this->contatoRepo->findById($id);

        if (!$contato) {
            $this->error('Contato não encontrado.', 404);
            return;
        }

        $this->success($contato->toArray());
    }

    public function store(): void
    {
        $body      = $this->getBody();
        $tipo      = $body['tipo']      ?? '';
        $descricao = trim($body['descricao'] ?? '');
        $pessoaId  = (int)($body['idPessoa'] ?? 0);

        if (!in_array($tipo, [Contato::TIPO_TELEFONE, Contato::TIPO_EMAIL])) {
            $this->error('Tipo inválido. Use "telefone" ou "email".');
            return;
        }

        if (!$descricao) {
            $this->error('Descrição é obrigatória.');
            return;
        }

        $pessoa = $this->pessoaRepo->findById($pessoaId);
        if (!$pessoa) {
            $this->error('Pessoa não encontrada.', 404);
            return;
        }

        $contato = new Contato($tipo, $descricao, $pessoa);
        $this->contatoRepo->save($contato);

        $this->success($contato->toArray(), 'Contato cadastrado com sucesso.', 201);
    }

    public function update(int $id): void
    {
        $contato = $this->contatoRepo->findById($id);

        if (!$contato) {
            $this->error('Contato não encontrado.', 404);
            return;
        }

        $body      = $this->getBody();
        $tipo      = $body['tipo']      ?? '';
        $descricao = trim($body['descricao'] ?? '');
        $pessoaId  = isset($body['idPessoa']) ? (int)$body['idPessoa'] : null;

        if (!in_array($tipo, [Contato::TIPO_TELEFONE, Contato::TIPO_EMAIL])) {
            $this->error('Tipo inválido. Use "telefone" ou "email".');
            return;
        }

        if (!$descricao) {
            $this->error('Descrição é obrigatória.');
            return;
        }

        $contato->setTipo($tipo);
        $contato->setDescricao($descricao);

        if ($pessoaId && $pessoaId !== $contato->getPessoa()->getId()) {
            $pessoa = $this->pessoaRepo->findById($pessoaId);
            if (!$pessoa) {
                $this->error('Pessoa não encontrada.', 404);
                return;
            }
            $contato->setPessoa($pessoa);
        }

        $this->contatoRepo->save($contato);
        $this->success($contato->toArray(), 'Contato atualizado com sucesso.');
    }

    public function destroy(int $id): void
    {
        $contato = $this->contatoRepo->findById($id);

        if (!$contato) {
            $this->error('Contato não encontrado.', 404);
            return;
        }

        $this->contatoRepo->delete($contato);
        $this->success(null, 'Contato excluído com sucesso.');
    }
}
