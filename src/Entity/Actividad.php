<?php

namespace App\Entity;

use App\Repository\ActividadRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActividadRepository::class)]
#[ORM\Table(name: 'actividades')]
#[ORM\HasLifecycleCallbacks]

class Actividad {
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: "Ramsey\Uuid\Doctrine\UuidGenerator")]
    #[ORM\Column(type: 'guid', unique: true)]
    private ?string $id = null;

    #[ORM\ManyToOne(targetEntity: Viaje::class, inversedBy: 'actividades')]
    #[ORM\JoinColumn(name: 'viaje_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private ?Viaje $viaje = null;

    #[ORM\ManyToOne(targetEntity: Destino::class, inversedBy: 'actividades')]
    #[ORM\JoinColumn(name: 'destino_id', referencedColumnName: 'id', nullable: true, onDelete: 'SET NULL')]
    private ?Destino $destino = null;

    #[ORM\Column(length: 250)]
    private string $titulo;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $descripcion = null;

    #[ORM\Column(length: 64, nullable: true)]
    private ?string $tipo = null;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $inicio_en = null;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $fin_en = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 12, scale: 2, nullable: true)]
    private ?string $precio_estimado = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 12, scale: 2, nullable: true)]
    private ?string $coste_real = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $ubicacion = null;

    #[ORM\Column(length: 32, options: ['default' => 'pendiente'])]
    private string $estado = 'pendiente';

    #[ORM\Column(type: Types::DECIMAL, precision: 3, scale: 1, nullable: true)]
    private ?string $valoracion_promedio = null;

    #[ORM\ManyToOne(targetEntity: Usuario::class)]
    #[ORM\JoinColumn(name: 'creado_por', referencedColumnName: 'id', nullable: true)]
    private ?Usuario $creado_por = null;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE, options: ['default' => 'now()'])]
    private \DateTimeImmutable $creado_en;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE, options: ['default' => 'now()'])]
    private \DateTimeImmutable $actualizado_en;

    public function __construct() {
        $this->titulo = '';
    }

    public function getId(): ?string {
        return $this->id;
    }

    public function setId(string $id): static {
        $this->id = $id;
        return $this;
    }
    public function getTitulo(): string {
        return $this->titulo;
    }
    public function setTitulo(string $titulo): self {
        $this->titulo = $titulo;
        return $this;
    }
    public function getDescripcion(): ?string {
        return $this->descripcion;
    }
    public function setDescripcion(?string $descripcion): self {
        $this->descripcion = $descripcion;
        return $this;
    }
    public function getTipo(): ?string {
        return $this->tipo;
    }
    public function setTipo(?string $tipo): self {
        $this->tipo = $tipo;
        return $this;
    }
    public function getInicioEn(): ?\DateTimeImmutable {
        return $this->inicio_en;
    }
    public function setInicioEn(?\DateTimeImmutable $dt): self {
        $this->inicio_en = $dt;
        return $this;
    }
    public function getFinEn(): ?\DateTimeImmutable {
        return $this->fin_en;
    }
    public function setFinEn(?\DateTimeImmutable $dt): self {
        $this->fin_en = $dt;
        return $this;
    }
    public function getPrecioEstimado(): ?string {
        return $this->precio_estimado;
    }
    public function setPrecioEstimado(?string $p): self {
        $this->precio_estimado = $p;
        return $this;
    }
    public function getCosteReal(): ?string {
        return $this->coste_real;
    }
    public function setCosteReal(?string $c): self {
        $this->coste_real = $c;
        return $this;
    }
    public function getUbicacion(): ?array {
        return $this->ubicacion;
    }
    public function setUbicacion(?array $ubicacion): self {
        $this->ubicacion = $ubicacion;
        return $this;
    }
    public function getViaje(): ?Viaje {
        return $this->viaje;
    }
    public function setViaje(?Viaje $viaje): self {
        $this->viaje = $viaje;
        return $this;
    }
    public function getDestino(): ?Destino {
        return $this->destino;
    }
    public function setDestino(?Destino $destino): self {
        $this->destino = $destino;
        return $this;
    }
    public function getEstado(): string {
        return $this->estado;
    }
    public function setEstado(string $e): self {
        $this->estado = $e;
        return $this;
    }
    public function getValoracionPromedio(): ?string {
        return $this->valoracion_promedio;
    }
    public function setValoracionPromedio(?string $v): self {
        $this->valoracion_promedio = $v;
        return $this;
    }
    public function getCreadoPor(): ?Usuario {
        return $this->creado_por;
    }
    public function setCreadoPor(?Usuario $u): self {
        $this->creado_por = $u;
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
