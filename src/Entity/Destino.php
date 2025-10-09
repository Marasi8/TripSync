<?php

namespace App\Entity;

use App\Repository\DestinoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DestinoRepository::class)]
#[ORM\Table(name: 'destinos')]
#[ORM\HasLifecycleCallbacks]
class Destino {
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: "Ramsey\Uuid\Doctrine\UuidGenerator")]
    #[ORM\Column(type: 'guid', unique: true)]
    private ?string $id = null;

    #[ORM\ManyToOne(targetEntity: Viaje::class, inversedBy: 'destinos')]
    #[ORM\JoinColumn(name: 'viaje_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private ?Viaje $viaje = null;

    #[ORM\Column(length: 250)]
    private string $nombre;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $descripcion = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $fecha_llegada = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $fecha_salida = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $orden = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $ubicacion = null;

    #[ORM\OneToMany(mappedBy: 'destino', targetEntity: Actividad::class, orphanRemoval: true)]
    private Collection $actividades;

    #[ORM\OneToMany(mappedBy: 'destino', targetEntity: Alojamiento::class, orphanRemoval: true)]
    private Collection $alojamientos;

    public function __construct() {
        $this->nombre = '';
        $this->actividades = new ArrayCollection();
        $this->alojamientos = new ArrayCollection();
    }

    #[ORM\ManyToOne(targetEntity: Usuario::class)]
    #[ORM\JoinColumn(name: 'creado_por', referencedColumnName: 'id', nullable: true)]
    private ?Usuario $creado_por = null;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE, options: ['default' => 'now()'])]
    private \DateTimeImmutable $creado_en;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE, options: ['default' => 'now()'])]
    private \DateTimeImmutable $actualizado_en;

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

    public function getNombre(): string {
        return $this->nombre;
    }
    public function setNombre(string $nombre): self {
        $this->nombre = $nombre;
        return $this;
    }
    public function getDescripcion(): ?string {
        return $this->descripcion;
    }
    public function setDescripcion(?string $descripcion): self {
        $this->descripcion = $descripcion;
        return $this;
    }
    public function getFechaLlegada(): ?\DateTimeImmutable {
        return $this->fecha_llegada;
    }
    public function setFechaLlegada(?\DateTimeImmutable $f): self {
        $this->fecha_llegada = $f;
        return $this;
    }
    public function getFechaSalida(): ?\DateTimeImmutable {
        return $this->fecha_salida;
    }
    public function setFechaSalida(?\DateTimeImmutable $f): self {
        $this->fecha_salida = $f;
        return $this;
    }
    public function getOrden(): ?int {
        return $this->orden;
    }
    public function setOrden(?int $orden): self {
        $this->orden = $orden;
        return $this;
    }
    public function getUbicacion(): ?array {
        return $this->ubicacion;
    }
    public function setUbicacion(?array $ubicacion): self {
        $this->ubicacion = $ubicacion;
        return $this;
    }

    /** @return Collection<int, Actividad> */
    public function getActividades(): Collection {
        return $this->actividades;
    }
    public function addActividad(Actividad $a): self {
        if (!$this->actividades->contains($a)) {
            $this->actividades->add($a);
            $a->setDestino($this);
        }
        return $this;
    }
    public function removeActividad(Actividad $a): self {
        if ($this->actividades->removeElement($a)) {
            if ($a->getDestino() === $this) {
                $a->setDestino(null);
            }
        }
        return $this;
    }

    /** @return Collection<int, Alojamiento> */
    public function getAlojamientos(): Collection {
        return $this->alojamientos;
    }
    public function addAlojamiento(Alojamiento $a): self {
        if (!$this->alojamientos->contains($a)) {
            $this->alojamientos->add($a);
            $a->setDestino($this);
        }
        return $this;
    }
    public function removeAlojamiento(Alojamiento $a): self {
        if ($this->alojamientos->removeElement($a)) {
            if ($a->getDestino() === $this) {
                $a->setDestino(null);
            }
        }
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
