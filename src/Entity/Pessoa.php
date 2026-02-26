<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'pessoa')]
class Pessoa
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 150)]
    private string $nome;

    #[ORM\Column(type: 'string', length: 14, unique: true)]
    private string $cpf;

    #[ORM\OneToMany(mappedBy: 'pessoa', targetEntity: Contato::class, cascade: ['remove'], orphanRemoval: true)]
    private Collection $contatos;

    public function __construct(string $nome, string $cpf)
    {
        $this->nome     = $nome;
        $this->cpf      = $cpf;
        $this->contatos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function setNome(string $nome): void
    {
        $this->nome = $nome;
    }

    public function getCpf(): string
    {
        return $this->cpf;
    }

    public function setCpf(string $cpf): void
    {
        $this->cpf = $cpf;
    }

    public function getContatos(): Collection
    {
        return $this->contatos;
    }

    public function toArray(): array
    {
        return [
            'id'   => $this->id,
            'nome' => $this->nome,
            'cpf'  => $this->cpf,
        ];
    }
}
