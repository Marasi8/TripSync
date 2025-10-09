<?php

namespace App\Entity;

use App\Repository\ParticipanteRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParticipanteRepository::class)]
#[ORM\Table(name: 'participantes')]
#[ORM\UniqueConstraint(name: 'uq_participantes_viaje_usuario', columns: ['viaje_id', 'usuario_id'])]
#[ORM\HasLifecycleCallbacks]
class Participante {
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: "Ramsey\Uuid\Doctrine\UuidGenerator")]
    #[ORM\Column(type: 'guid', unique: true)]
    private ?string $id = null;

    #[ORM\ManyToOne(targetEntity: Viaje::class, inversedBy: 'participantes')]
    #[ORM\JoinColumn(name: 'viaje_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private ?Viaje $viaje = null;

    #[ORM\ManyToOne(targetEntity: Usuario::class)]
    #[ORM\JoinColumn(name: 'usuario_id', referencedColumnName: 'id', nullable: false)]
    private ?Usuario $usuario = null;

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private bool $es_admin = false;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE, options: ['default' => 'now()'])]
    private \DateTimeImmutable $unido_en;

    #[ORM\Column(length: 16, options: ['default' => 'aceptado'])]
    private string $estado_participacion = 'aceptado';

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE, options: ['default' => 'now()'])]
    private \DateTimeImmutable $creado_en;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE, options: ['default' => 'now()'])]
    private \DateTimeImmutable $actualizado_en;

    public function __construct() {
        $this->unido_en = new \DateTimeImmutable();
        $now = new \DateTimeImmutable();
        $this->creado_en = $now;
        $this->actualizado_en = $now;
    }

    public function getId(): ?string {
        return $this->id;
    }

    public function setId(string $id): static {
        $this->id = $id;
        return $this;
    }
    public function getViaje(): ?Viaje {
        return $this->viaje;
    }
    public function setViaje(?Viaje $viaje): self {
        $this->viaje = $viaje;
        return $this;
    }
    public function getUsuario(): ?Usuario {
        return $this->usuario;
    }
    public function setUsuario(?Usuario $usuario): self {
        $this->usuario = $usuario;
        return $this;
    }

    public function isAdmin(): bool {
        return $this->es_admin;
    }
    
    public function getEsAdmin(): bool {
        return $this->es_admin;
    }
    
    public function setEsAdmin(bool $es): self {
        $this->es_admin = $es;
        return $this;
    }

    public function getUnidoEn(): \DateTimeImmutable {
        return $this->unido_en;
    }
    public function setUnidoEn(\DateTimeImmutable $dt): self {
        $this->unido_en = $dt;
        return $this;
    }

    public function getEstadoParticipacion(): string {
        return $this->estado_participacion;
    }
    public function setEstadoParticipacion(string $estado): self {
        $this->estado_participacion = $estado;
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
}
