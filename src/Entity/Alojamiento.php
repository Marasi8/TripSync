<?php

namespace App\Entity;

use App\Repository\AlojamientoRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AlojamientoRepository::class)]
#[ORM\Table(name: 'alojamientos')]
#[ORM\HasLifecycleCallbacks]
class Alojamiento {
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: "Ramsey\Uuid\Doctrine\UuidGenerator")]
    #[ORM\Column(type: 'guid', unique: true)]
    private ?string $id = null;

    #[ORM\ManyToOne(targetEntity: Viaje::class, inversedBy: 'alojamientos')]
    #[ORM\JoinColumn(name: 'viaje_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private ?Viaje $viaje = null;

    #[ORM\ManyToOne(targetEntity: Destino::class, inversedBy: 'alojamientos')]
    #[ORM\JoinColumn(name: 'destino_id', referencedColumnName: 'id', nullable: true, onDelete: 'SET NULL')]
    private ?Destino $destino = null;

    #[ORM\Column(length: 200)]
    private string $nombre;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $fecha_checkin = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $fecha_checkout = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 12, scale: 2, nullable: true)]
    private ?string $precio_estimado = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 12, scale: 2, nullable: true)]
    private ?string $coste_real = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 3, scale: 1, nullable: true)]
    private ?string $valoracion_promedio = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $direccion = null;

    #[ORM\Column(length: 64, nullable: true)]
    private ?string $tipo = null;

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
    public function getNombre(): string {
        return $this->nombre;
    }
    public function setNombre(string $nombre): self {
        $this->nombre = $nombre;
        return $this;
    }
    public function getFechaCheckin(): ?\DateTimeImmutable {
        return $this->fecha_checkin;
    }
    public function setFechaCheckin(?\DateTimeImmutable $f): self {
        $this->fecha_checkin = $f;
        return $this;
    }
    public function getFechaCheckout(): ?\DateTimeImmutable {
        return $this->fecha_checkout;
    }
    public function setFechaCheckout(?\DateTimeImmutable $f): self {
        $this->fecha_checkout = $f;
        return $this;
    }



    public function getTipo(): ?string {
        return $this->tipo;
    }
    public function setTipo(?string $tipo): self {
        $this->tipo = $tipo;
        return $this;
    }
    public function getDireccion(): ?string {
        return $this->direccion;
    }
    public function setDireccion(?string $direccion): self {
        $this->direccion = $direccion;
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
