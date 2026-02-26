<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Contato;
use App\Entity\Pessoa;
use Doctrine\ORM\EntityManager;

class ContatoRepository
{
    public function __construct(private EntityManager $em) {}

    public function findAll(): array
    {
        return $this->em->getRepository(Contato::class)
            ->createQueryBuilder('c')
            ->join('c.pessoa', 'p')
            ->orderBy('p.nome', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByPessoa(Pessoa $pessoa): array
    {
        return $this->em->getRepository(Contato::class)->findBy(['pessoa' => $pessoa]);
    }

    public function findById(int $id): ?Contato
    {
        return $this->em->find(Contato::class, $id);
    }

    public function save(Contato $contato): void
    {
        $this->em->persist($contato);
        $this->em->flush();
    }

    public function delete(Contato $contato): void
    {
        $this->em->remove($contato);
        $this->em->flush();
    }
}
