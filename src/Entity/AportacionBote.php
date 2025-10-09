<?php

namespace App\Entity;

use App\Repository\AportacionBoteRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AportacionBoteRepository::class)]
#[ORM\Table(name: 'aportaciones_bote')]
#[ORM\HasLifecycleCallbacks]
class AportacionBote {
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: "Ramsey\Uuid\Doctrine\UuidGenerator")]
    #[ORM\Column(type: 'guid', unique: true)]
    private ?string $id = null;

    #[ORM\ManyToOne(targetEntity: Viaje::class, inversedBy: 'aportacionesBote')]
    #[ORM\JoinColumn(name: 'viaje_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private ?Viaje $viaje = null;

    #[ORM\ManyToOne(targetEntity: Usuario::class)]
    #[ORM\JoinColumn(name: 'usuario_id', referencedColumnName: 'id', nullable: false)]
    private ?Usuario $usuario = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 12, scale: 2)]
    private string $importe;

    // En el DDL es DATE (sin hora)
    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private \DateTimeImmutable $fecha;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $descripcion = null;

    #[ORM\Column(name: 'creado_en', type: Types::DATETIMETZ_IMMUTABLE)]
    private \DateTimeImmutable $creado_en;

    #[ORM\Column(name: 'actualizado_en', type: Types::DATETIMETZ_IMMUTABLE)]
    private \DateTimeImmutable $actualizado_en;

    public function __construct() {
        $this->importe = '0.00';
        $this->fecha = new \DateTimeImmutable();
        $now = new \DateTimeImmutable();
        $this->creado_en = $now;
        $this->actualizado_en = $now;
    }

    public function getId(): ?string {
        return $this->id;
    }
    
    public function setId(string $id): self {
        $this->id = $id;
        return $this;
    }
    
    public function getTitulo(): string {
        return $this->descripcion ?? 'Aportación al bote';
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

    public function getImporte(): string {
        return $this->importe;
    }

    public function setImporte(string $importe): self {
        $this->importe = $importe;
        return $this;
    }

    public function getFecha(): \DateTimeImmutable {
        return $this->fecha;
    }

    public function setFecha(\DateTimeImmutable $fecha): self {
        $this->fecha = $fecha;
        return $this;
    }

    public function getDescripcion(): ?string {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): self {
        $this->descripcion = $descripcion;
        return $this;
    }

    public function getCreadoEn(): \DateTimeImmutable {
        return $this->creado_en;
    }

    public function getActualizadoEn(): \DateTimeImmutable {
        return $this->actualizado_en;
    }

    #[ORM\PrePersist]
    public function prePersist(): void {
        if (empty($this->id)) {
            $this->id = \Ramsey\Uuid\Uuid::uuid4()->toString();
        }
        $now = new \DateTimeImmutable();
        $this->creado_en = $this->creado_en ?? $now;
        $this->actualizado_en = $this->actualizado_en ?? $now;
    }

    #[ORM\PreUpdate]
    public function preUpdate(): void {
        $this->actualizado_en = new \DateTimeImmutable();
    }
}
