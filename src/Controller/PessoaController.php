<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\BaseController;
use App\Entity\Pessoa;
use App\Repository\PessoaRepository;

class PessoaController extends BaseController
{
    public function __construct(private PessoaRepository $repository) {}

    public function index(): void
    {
        $search  = $_GET['search'] ?? '';
        $pessoas = $search
            ? $this->repository->findByNome($search)
            : $this->repository->findAll();

        $this->success(array_map(fn($p) => $p->toArray(), $pessoas));
    }

    public function show(int $id): void
    {
        $pessoa = $this->repository->findById($id);

        if (!$pessoa) {
            $this->error('Pessoa não encontrada.', 404);
            return;
        }

        $this->success($pessoa->toArray());
    }

    public function store(): void
    {
        $body = $this->getBody();

        $nome = trim($body['nome'] ?? '');
        $cpf  = preg_replace('/\D/', '', $body['cpf'] ?? '');

        if (!$nome || !$cpf) {
            $this->error('Nome e CPF são obrigatórios.');
            return;
        }

        if (strlen($cpf) !== 11) {
            $this->error('CPF inválido. Informe 11 dígitos numéricos.');
            return;
        }

        $pessoa = new Pessoa($nome, $cpf);
        $this->repository->save($pessoa);

        $this->success($pessoa->toArray(), 'Pessoa cadastrada com sucesso.', 201);
    }

    public function update(int $id): void
    {
        $pessoa = $this->repository->findById($id);

        if (!$pessoa) {
            $this->error('Pessoa não encontrada.', 404);
            return;
        }

        $body = $this->getBody();
        $nome = trim($body['nome'] ?? '');
        $cpf  = preg_replace('/\D/', '', $body['cpf'] ?? '');

        if (!$nome || !$cpf) {
            $this->error('Nome e CPF são obrigatórios.');
            return;
        }

        if (strlen($cpf) !== 11) {
            $this->error('CPF inválido. Informe 11 dígitos numéricos.');
            return;
        }

        $pessoa->setNome($nome);
        $pessoa->setCpf($cpf);
        $this->repository->save($pessoa);

        $this->success($pessoa->toArray(), 'Pessoa atualizada com sucesso.');
    }

    public function destroy(int $id): void
    {
        $pessoa = $this->repository->findById($id);

        if (!$pessoa) {
            $this->error('Pessoa não encontrada.', 404);
            return;
        }

        $this->repository->delete($pessoa);
        $this->success(null, 'Pessoa excluída com sucesso.');
    }
}
