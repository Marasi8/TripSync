<?php

namespace App\Entity;

use App\Repository\NotificacionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NotificacionRepository::class)]
#[ORM\Table(name: 'notificaciones')]
#[ORM\HasLifecycleCallbacks]
class Notificacion {
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: "Ramsey\Uuid\Doctrine\UuidGenerator")]
    #[ORM\Column(type: 'guid', unique: true)]
    private ?string $id = null;

    #[ORM\ManyToOne(targetEntity: Usuario::class)]
    #[ORM\JoinColumn(name: 'usuario_id', referencedColumnName: 'id', nullable: false)]
    private ?Usuario $usuario = null;

    #[ORM\ManyToOne(targetEntity: Viaje::class, inversedBy: 'notificaciones')]
    #[ORM\JoinColumn(name: 'viaje_id', referencedColumnName: 'id', nullable: true, onDelete: 'SET NULL')]
    private ?Viaje $viaje = null;

    #[ORM\Column(length: 128)]
    private string $tipo;

    #[ORM\Column(type: Types::JSON, nullable: false)]
    private array $payload = [];

    #[ORM\Column(name: 'leido_en', type: Types::DATETIMETZ_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $leidoEn = null;

    #[ORM\Column(name: 'creado_en', type: Types::DATETIMETZ_IMMUTABLE)]
    private \DateTimeImmutable $creado_en;

    public function __construct() {
        $this->tipo = '';
        $this->creado_en = new \DateTimeImmutable();
    }

    public function getId(): ?string {
        return $this->id;
    }

    public function setId(string $id): static {
        $this->id = $id;
        return $this;
    }
    public function getUsuario(): ?Usuario {
        return $this->usuario;
    }
    public function setUsuario(Usuario $u): self {
        $this->usuario = $u;
        return $this;
    }
    public function getViaje(): ?Viaje {
        return $this->viaje;
    }
    public function setViaje(?Viaje $viaje): self {
        $this->viaje = $viaje;
        return $this;
    }
    public function getTipo(): string {
        return $this->tipo;
    }
    public function setTipo(string $tipo): self {
        $this->tipo = $tipo;
        return $this;
    }
    public function getPayload(): array {
        return $this->payload;
    }
    
    public function setPayload(array $payload): static {
        $this->payload = $payload;
        return $this;
    }
    public function getLeidoEn(): ?\DateTimeImmutable {
        return $this->leidoEn;
    }
    public function setLeidoEn(?\DateTimeImmutable $t): self {
        $this->leidoEn = $t;
        return $this;
    }
    public function getCreadoEn(): \DateTimeImmutable {
        return $this->creado_en;
    }

    #[ORM\PrePersist]
    public function prePersist(): void {
        $this->creado_en = $this->creado_en ?? new \DateTimeImmutable();
    }
}
