<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'contato')]
class Contato
{
    public const TIPO_TELEFONE = 'telefone';
    public const TIPO_EMAIL    = 'email';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 20)]
    private string $tipo;

    #[ORM\Column(type: 'string', length: 255)]
    private string $descricao;

    #[ORM\ManyToOne(targetEntity: Pessoa::class, inversedBy: 'contatos')]
    #[ORM\JoinColumn(name: 'idPessoa', referencedColumnName: 'id', nullable: false)]
    private Pessoa $pessoa;

    public function __construct(string $tipo, string $descricao, Pessoa $pessoa)
    {
        $this->tipo      = $tipo;
        $this->descricao = $descricao;
        $this->pessoa    = $pessoa;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTipo(): string
    {
        return $this->tipo;
    }

    public function setTipo(string $tipo): void
    {
        $this->tipo = $tipo;
    }

    public function getDescricao(): string
    {
        return $this->descricao;
    }

    public function setDescricao(string $descricao): void
    {
        $this->descricao = $descricao;
    }

    public function getPessoa(): Pessoa
    {
        return $this->pessoa;
    }

    public function setPessoa(Pessoa $pessoa): void
    {
        $this->pessoa = $pessoa;
    }

    public function toArray(): array
    {
        return [
            'id'        => $this->id,
            'tipo'      => $this->tipo,
            'descricao' => $this->descricao,
            'idPessoa'  => $this->pessoa->getId(),
            'pessoa'    => $this->pessoa->getNome(),
        ];
    }
}
