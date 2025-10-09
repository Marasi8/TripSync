<?php

namespace App\Entity;

use App\Repository\PropuestaRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

#[ORM\Entity(repositoryClass: PropuestaRepository::class)]
#[ORM\Table(name: 'propuestas')]
#[ORM\HasLifecycleCallbacks]
class Propuesta {
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: "Ramsey\Uuid\Doctrine\UuidGenerator")]
    #[ORM\Column(type: 'guid', unique: true)]
    private ?string $id = null;

    #[ORM\ManyToOne(targetEntity: Viaje::class, inversedBy: 'propuestas')]
    #[ORM\JoinColumn(name: 'viaje_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private ?Viaje $viaje = null;

    #[ORM\ManyToOne(targetEntity: Usuario::class)]
    #[ORM\JoinColumn(name: 'creado_por', referencedColumnName: 'id', nullable: false)]
    private ?Usuario $creado_por = null;

    #[ORM\Column(length: 64)]
    private string $tipo;

    #[ORM\Column(name: 'objetivo_id', type: 'guid', nullable: true)]
    private ?string $objetivoId = null;

    #[ORM\Column(type: Types::JSON, nullable: false)]
    private array $datos = [];

    #[ORM\Column(length: 32)]
    private string $estado = 'pendiente';

    #[ORM\Column(name: 'decision_admin', length: 32, nullable: true)]
    private ?string $decisionAdmin = null;

    #[ORM\Column(name: 'creado_en', type: Types::DATETIMETZ_IMMUTABLE)]
    private \DateTimeImmutable $creado_en;

    #[ORM\Column(name: 'expira_en', type: Types::DATETIMETZ_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $expiraEn = null;

    #[ORM\Column(name: 'resuelto_en', type: Types::DATETIMETZ_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $resueltoEn = null;

    public function __construct() {
        $this->tipo = '';
        $this->creado_en = new \DateTimeImmutable();
    }

    public function getId(): ?string {
        return $this->id;
    }
    
    public function setId(string $id): self {
        $this->id = $id;
        return $this;
    }
    
    public function getTitulo(): string {
        return $this->datos['titulo'] ?? 'Propuesta sin título';
    }
    
    public function setTitulo(string $titulo): self {
        $this->datos['titulo'] = $titulo;
        return $this;
    }
    public function getViaje(): ?Viaje {
        return $this->viaje;
    }
    public function setViaje(?Viaje $viaje): self {
        $this->viaje = $viaje;
        return $this;
    }

    public function getCreadoPor(): ?Usuario {
        return $this->creado_por;
    }
    public function setCreadoPor(Usuario $u): self {
        $this->creado_por = $u;
        return $this;
    }

    public function getTipo(): string {
        return $this->tipo;
    }
    public function setTipo(string $tipo): self {
        $this->tipo = $tipo;
        return $this;
    }

    public function getObjetivoId(): ?string {
        return $this->objetivoId;
    }
    public function setObjetivoId(?string $id): self {
        $this->objetivoId = $id;
        return $this;
    }

    public function getDatos(): array {
        return $this->datos;
    }
    public function setDatos(array $datos): self {
        $this->datos = $datos;
        return $this;
    }

    public function getEstado(): string {
        return $this->estado;
    }
    public function setEstado(string $estado): self {
        $this->estado = $estado;
        return $this;
    }

    public function getDecisionAdmin(): ?string {
        return $this->decisionAdmin;
    }
    public function setDecisionAdmin(?string $d): self {
        $this->decisionAdmin = $d;
        return $this;
    }

    public function getCreadoEn(): \DateTimeImmutable {
        return $this->creado_en;
    }
    public function getExpiraEn(): ?\DateTimeImmutable {
        return $this->expiraEn;
    }
    public function getResueltoEn(): ?\DateTimeImmutable {
        return $this->resueltoEn;
    }
    
    #[ORM\PrePersist]
    public function prePersist(): void {
        if (empty($this->id)) {
            $this->id = Uuid::uuid4()->toString();
        }
        $this->creado_en = new \DateTimeImmutable();
    }
}
