<?php

namespace App\Entity;

use App\Repository\ValoracionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ValoracionRepository::class)]
#[ORM\Table(name: 'valoraciones')]
#[ORM\HasLifecycleCallbacks]
class Valoracion {
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: "Ramsey\Uuid\Doctrine\UuidGenerator")]
    #[ORM\Column(type: 'guid', unique: true)]
    private ?string $id = null;

    #[ORM\ManyToOne(targetEntity: Usuario::class)]
    #[ORM\JoinColumn(name: 'usuario_id', referencedColumnName: 'id', nullable: false)]
    private ?Usuario $usuario = null;

    #[ORM\Column(length: 32)]
    private string $tipo_elemento;

    #[ORM\Column(type: 'guid')]
    private ?string $elemento_id = null;

    #[ORM\Column(type: Types::INTEGER)]
    private int $puntuacion;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $comentario = null;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE, options: ['default' => 'now()'])]
    private \DateTimeImmutable $creado_en;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE, options: ['default' => 'now()'])]
    private \DateTimeImmutable $actualizado_en;

    /**
     * UNIQUE(usuario_id, tipo_elemento, elemento_id) and CHECKs are enforced at DB level in migration/DDL.
     */

    public function __construct() {
        $this->tipo_elemento = '';
        $this->puntuacion = 0;
        $now = new \DateTimeImmutable();
        $this->creado_en = $now;
        $this->actualizado_en = $now;
    }

    #[ORM\PrePersist]
    public function onPrePersist(): void {
        $now = new \DateTimeImmutable();
        if (!isset($this->creado_en)) {
            $this->creado_en = $now;
        }
        if (!isset($this->actualizado_en)) {
            $this->actualizado_en = $now;
        }
    }

    #[ORM\PreUpdate]
    public function onPreUpdate(): void {
        $this->actualizado_en = new \DateTimeImmutable();
    }

    public function getId(): ?string {
        return $this->id;
    }
    public function getUsuario(): ?Usuario {
        return $this->usuario;
    }
    public function setUsuario(?Usuario $u): self {
        $this->usuario = $u;
        return $this;
    }
    public function getTipoElemento(): string {
        return $this->tipo_elemento;
    }
    public function setTipoElemento(string $t): self {
        $this->tipo_elemento = $t;
        return $this;
    }
    public function getElementoId(): ?string {
        return $this->elemento_id;
    }
    public function setElementoId(?string $id): self {
        $this->elemento_id = $id;
        return $this;
    }
    public function getPuntuacion(): int {
        return $this->puntuacion;
    }
    public function setPuntuacion(int $p): self {
        $this->puntuacion = $p;
        return $this;
    }
    public function getComentario(): ?string {
        return $this->comentario;
    }
    public function setComentario(?string $c): self {
        $this->comentario = $c;
        return $this;
    }
    public function getCreadoEn(): \DateTimeImmutable {
        return $this->creado_en;
    }
    public function setCreadoEn(\DateTimeImmutable $dt): self {
        $this->creado_en = $dt;
        return $this;
    }
    public function getActualizadoEn(): \DateTimeImmutable {
        return $this->actualizado_en;
    }
    public function setActualizadoEn(\DateTimeImmutable $dt): self {
        $this->actualizado_en = $dt;
        return $this;
    }
}
