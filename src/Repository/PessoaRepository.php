<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Pessoa;
use Doctrine\ORM\EntityManager;

class PessoaRepository
{
    public function __construct(private EntityManager $em) {}

    public function findAll(): array
    {
        return $this->em->getRepository(Pessoa::class)->findAll();
    }

    public function findByNome(string $nome): array
    {
        return $this->em->getRepository(Pessoa::class)
            ->createQueryBuilder('p')
            ->where('p.nome LIKE :nome')
            ->setParameter('nome', "%{$nome}%")
            ->orderBy('p.nome', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findById(int $id): ?Pessoa
    {
        return $this->em->find(Pessoa::class, $id);
    }

    public function save(Pessoa $pessoa): void
    {
        $this->em->persist($pessoa);
        $this->em->flush();
    }

    public function delete(Pessoa $pessoa): void
    {
        $this->em->remove($pessoa);
        $this->em->flush();
    }
}
