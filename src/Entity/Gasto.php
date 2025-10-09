<?php

namespace App\Entity;

use App\Repository\GastoRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GastoRepository::class)]
#[ORM\Table(name: 'gastos')]
#[ORM\HasLifecycleCallbacks]
class Gasto
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: "Ramsey\Uuid\Doctrine\UuidGenerator")]
    #[ORM\Column(type: 'guid', unique: true)]
    private ?string $id = null;

    #[ORM\ManyToOne(targetEntity: Viaje::class, inversedBy: 'gastos')]
    #[ORM\JoinColumn(name: 'viaje_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private ?Viaje $viaje = null;

    #[ORM\Column(length: 32, nullable: true)]
    private ?string $tipo_relacion = null;

    #[ORM\Column(type: 'guid', nullable: true)]
    private ?string $relacion_id = null;

    #[ORM\ManyToOne(targetEntity: Usuario::class)]
    #[ORM\JoinColumn(name: 'pagador_id', referencedColumnName: 'id', nullable: false)]
    private ?Usuario $pagador = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 12, scale: 2)]
    private string $importe;

    #[ORM\Column(length: 64)]
    private string $categoria;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private \DateTimeImmutable $fecha;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $descripcion = null;

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private bool $liquidado = false;

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private bool $pagado_con_bote = false;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE, options: ['default' => 'now()'])]
    private \DateTimeImmutable $creado_en;

    public function __construct() {
        $this->importe = '0.00';
        $this->categoria = '';
        $this->fecha = new \DateTimeImmutable();
        $this->creado_en = new \DateTimeImmutable();
    }

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE, options: ['default' => 'now()'])]
    private \DateTimeImmutable $actualizado_en;

    #[ORM\PrePersist]
    public function onPrePersist(): void
    {
        if (empty($this->id)) {
            $this->id = \Ramsey\Uuid\Uuid::uuid4()->toString();
        }
        $now = new \DateTimeImmutable();
        if (!isset($this->creado_en)) { $this->creado_en = $now; }
        if (!isset($this->actualizado_en)) { $this->actualizado_en = $now; }
    }

    #[ORM\PreUpdate]
    public function onPreUpdate(): void
    {
        $this->actualizado_en = new \DateTimeImmutable();
    }

    public function getId(): ?string { return $this->id; }
    public function setId(string $id): self { $this->id = $id; return $this; }
    public function getTitulo(): string { return $this->descripcion ?? 'Gasto sin descripción'; }
    public function getViaje(): ?Viaje { return $this->viaje; }
    public function setViaje(?Viaje $viaje): self { $this->viaje = $viaje; return $this; }
    public function getTipoRelacion(): ?string { return $this->tipo_relacion; }
    public function setTipoRelacion(?string $t): self { $this->tipo_relacion = $t; return $this; }
    public function getRelacionId(): ?string { return $this->relacion_id; }
    public function setRelacionId(?string $id): self { $this->relacion_id = $id; return $this; }
    public function getPagador(): ?Usuario { return $this->pagador; }
    public function setPagador(?Usuario $u): self { $this->pagador = $u; return $this; }
    public function getImporte(): string { return $this->importe; }
    public function setImporte(string $importe): self { $this->importe = $importe; return $this; }
    public function getCategoria(): string { return $this->categoria; }
    public function setCategoria(string $categoria): self { $this->categoria = $categoria; return $this; }
    public function getFecha(): \DateTimeImmutable { return $this->fecha; }
    public function setFecha(\DateTimeImmutable $fecha): self { $this->fecha = $fecha; return $this; }
    public function getDescripcion(): ?string { return $this->descripcion; }
    public function setDescripcion(?string $d): self { $this->descripcion = $d; return $this; }
    public function isLiquidado(): bool { return $this->liquidado; }
    public function setLiquidado(bool $v): self { $this->liquidado = $v; return $this; }
    public function isPagadoConBote(): bool { return $this->pagado_con_bote; }
    public function setPagadoConBote(bool $v): self { $this->pagado_con_bote = $v; return $this; }
    public function getCreadoEn(): \DateTimeImmutable { return $this->creado_en; }
    public function setCreadoEn(\DateTimeImmutable $dt): self { $this->creado_en = $dt; return $this; }
    public function getActualizadoEn(): \DateTimeImmutable { return $this->actualizado_en; }
    public function setActualizadoEn(\DateTimeImmutable $dt): self { $this->actualizado_en = $dt; return $this; }
}
