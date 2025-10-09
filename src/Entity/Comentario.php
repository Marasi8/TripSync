<?php

namespace App\Entity;

use App\Repository\ComentarioRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ComentarioRepository::class)]
#[ORM\Table(name: 'comentarios')]
#[ORM\HasLifecycleCallbacks]
class Comentario {
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: "Ramsey\Uuid\Doctrine\UuidGenerator")]
    #[ORM\Column(type: 'guid', unique: true)]
    private ?string $id = null;

    #[ORM\ManyToOne(targetEntity: Usuario::class)]
    #[ORM\JoinColumn(name: 'usuario_id', referencedColumnName: 'id', nullable: false)]
    private ?Usuario $usuario = null;

    #[ORM\ManyToOne(targetEntity: Viaje::class, inversedBy: 'comentarios')]
    #[ORM\JoinColumn(name: 'viaje_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private ?Viaje $viaje = null;

    #[ORM\Column(name: 'tipo_objetivo', length: 64)]
    private string $tipoObjetivo;

    #[ORM\Column(name: 'objetivo_id', type: 'guid')]
    private string $objetivoId;

    #[ORM\Column(type: Types::TEXT)]
    private string $cuerpo;

    #[ORM\Column(name: 'creado_en', type: Types::DATETIMETZ_IMMUTABLE)]
    private \DateTimeImmutable $creado_en;

    #[ORM\Column(name: 'actualizado_en', type: Types::DATETIMETZ_IMMUTABLE)]
    private \DateTimeImmutable $actualizado_en;

    public function __construct() {
        $this->tipoObjetivo = '';
        $this->objetivoId = '';
        $this->cuerpo = '';
        $now = new \DateTimeImmutable();
        $this->creado_en = $now;
        $this->actualizado_en = $now;
    }

    public function getId(): ?string {
        return $this->id;
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
    public function getTipoObjetivo(): string {
        return $this->tipoObjetivo;
    }
    public function setTipoObjetivo(string $t): self {
        $this->tipoObjetivo = $t;
        return $this;
    }
    public function getObjetivoId(): string {
        return $this->objetivoId;
    }
    public function setObjetivoId(string $id): self {
        $this->objetivoId = $id;
        return $this;
    }
    public function getCuerpo(): string {
        return $this->cuerpo;
    }
    public function setCuerpo(string $cuerpo): self {
        $this->cuerpo = $cuerpo;
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
        $now = new \DateTimeImmutable();
        $this->creado_en = $this->creado_en ?? $now;
        $this->actualizado_en = $this->actualizado_en ?? $now;
    }

    #[ORM\PreUpdate]
    public function preUpdate(): void {
        $this->actualizado_en = new \DateTimeImmutable();
    }
}
