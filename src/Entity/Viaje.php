<?php

namespace App\Entity;

use App\Repository\ViajeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ViajeRepository::class)]
#[ORM\Table(name: 'viajes')]
#[ORM\HasLifecycleCallbacks]
class Viaje {
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: "Ramsey\Uuid\Doctrine\UuidGenerator")]
    #[ORM\Column(type: 'guid', unique: true)]
    private ?string $id = null;

    #[ORM\Column(length: 250)]
    private string $titulo;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $descripcion = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $fecha_inicio = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $fecha_fin = null;

    #[ORM\Column(length: 24, options: ['default' => 'planificando'])]
    private string $estado = 'planificando';

    #[ORM\Column(length: 16, options: ['default' => 'privado'])]
    private string $visibilidad = 'privado';

    #[ORM\Column(type: Types::DECIMAL, precision: 12, scale: 2, nullable: true)]
    private ?string $presupuesto_total = null;

    #[ORM\ManyToOne(targetEntity: Usuario::class)]
    #[ORM\JoinColumn(name: 'admin_actual', referencedColumnName: 'id', nullable: true, onDelete: 'SET NULL')]
    private ?Usuario $admin_actual = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $configuracion = null;

    #[ORM\ManyToOne(targetEntity: Usuario::class)]
    #[ORM\JoinColumn(name: 'creado_por', referencedColumnName: 'id', nullable: true)]
    private ?Usuario $creado_por = null;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE, options: ['default' => 'now()'])]
    private \DateTimeImmutable $creado_en;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE, options: ['default' => 'now()'])]
    private \DateTimeImmutable $actualizado_en;

    // Relaciones OneToMany (inverso)
    #[ORM\OneToMany(mappedBy: 'viaje', targetEntity: Participante::class, orphanRemoval: true)]
    private Collection $participantes;

    #[ORM\OneToMany(mappedBy: 'viaje', targetEntity: Destino::class, orphanRemoval: true)]
    private Collection $destinos;

    #[ORM\OneToMany(mappedBy: 'viaje', targetEntity: Actividad::class, orphanRemoval: true)]
    private Collection $actividades;

    #[ORM\OneToMany(mappedBy: 'viaje', targetEntity: Alojamiento::class, orphanRemoval: true)]
    private Collection $alojamientos;

    #[ORM\OneToMany(mappedBy: 'viaje', targetEntity: Gasto::class, orphanRemoval: true)]
    private Collection $gastos;

    #[ORM\OneToMany(mappedBy: 'viaje', targetEntity: AportacionBote::class, orphanRemoval: true)]
    private Collection $aportacionesBote;

    #[ORM\OneToMany(mappedBy: 'viaje', targetEntity: Imagen::class, orphanRemoval: true)]
    private Collection $imagenes;

    #[ORM\OneToMany(mappedBy: 'viaje', targetEntity: Propuesta::class, orphanRemoval: true)]
    private Collection $propuestas;

    #[ORM\OneToMany(mappedBy: 'viaje', targetEntity: Comentario::class, orphanRemoval: true)]
    private Collection $comentarios;

    #[ORM\OneToMany(mappedBy: 'viaje', targetEntity: Notificacion::class, orphanRemoval: true)]
    private Collection $notificaciones;

    #[ORM\OneToMany(mappedBy: 'viaje', targetEntity: Invitacion::class, orphanRemoval: true)]
    private Collection $invitaciones;

    public function __construct() {
        $this->titulo = ''; // Inicializar título vacío que será establecido posteriormente
        $now = new \DateTimeImmutable();
        $this->creado_en = $now;
        $this->actualizado_en = $now;
        $this->participantes = new ArrayCollection();
        $this->destinos = new ArrayCollection();
        $this->actividades = new ArrayCollection();
        $this->alojamientos = new ArrayCollection();
        $this->gastos = new ArrayCollection();
        $this->aportacionesBote = new ArrayCollection();
        $this->imagenes = new ArrayCollection();
        $this->propuestas = new ArrayCollection();
        $this->comentarios = new ArrayCollection();
        $this->notificaciones = new ArrayCollection();
        $this->invitaciones = new ArrayCollection();
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
    public function getFechaInicio(): ?\DateTimeImmutable {
        return $this->fecha_inicio;
    }
    public function setFechaInicio(\DateTimeInterface|null $fecha_inicio): self {
        if ($fecha_inicio instanceof \DateTime) {
            $this->fecha_inicio = \DateTimeImmutable::createFromMutable($fecha_inicio);
        } else {
            $this->fecha_inicio = $fecha_inicio;
        }
        return $this;
    }
    public function getFechaFin(): ?\DateTimeImmutable {
        return $this->fecha_fin;
    }
    public function setFechaFin(\DateTimeInterface|null $fecha_fin): self {
        if ($fecha_fin instanceof \DateTime) {
            $this->fecha_fin = \DateTimeImmutable::createFromMutable($fecha_fin);
        } else {
            $this->fecha_fin = $fecha_fin;
        }
        return $this;
    }
    public function getEstado(): string {
        return $this->estado;
    }
    public function setEstado(string $estado): self {
        $this->estado = $estado;
        return $this;
    }
    public function getVisibilidad(): string {
        return $this->visibilidad;
    }
    public function setVisibilidad(string $visibilidad): self {
        $this->visibilidad = $visibilidad;
        return $this;
    }
    public function getPresupuestoTotal(): ?string {
        return $this->presupuesto_total;
    }
    public function setPresupuestoTotal(?string $presupuesto_total): self {
        $this->presupuesto_total = $presupuesto_total;
        return $this;
    }
    public function getAdminActual(): ?Usuario {
        return $this->admin_actual;
    }
    public function setAdminActual(?Usuario $admin): self {
        $this->admin_actual = $admin;
        return $this;
    }
    public function getConfiguracion(): ?array {
        return $this->configuracion;
    }
    public function setConfiguracion(?array $config): self {
        $this->configuracion = $config;
        return $this;
    }
    public function getCreadoPor(): ?Usuario {
        return $this->creado_por;
    }
    public function setCreadoPor(?Usuario $user): self {
        $this->creado_por = $user;
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

    /** @return Collection<int, Participante> */
    public function getParticipantes(): Collection {
        return $this->participantes;
    }
    public function addParticipante(Participante $p): self {
        if (!$this->participantes->contains($p)) {
            $this->participantes->add($p);
            $p->setViaje($this);
        }
        return $this;
    }
    public function removeParticipante(Participante $p): self {
        if ($this->participantes->removeElement($p)) {
            if ($p->getViaje() === $this) {
                $p->setViaje(null);
            }
        }
        return $this;
    }

    /** @return Collection<int, Destino> */
    public function getDestinos(): Collection {
        return $this->destinos;
    }
    public function addDestino(Destino $d): self {
        if (!$this->destinos->contains($d)) {
            $this->destinos->add($d);
            $d->setViaje($this);
        }
        return $this;
    }
    public function removeDestino(Destino $d): self {
        if ($this->destinos->removeElement($d)) {
            if ($d->getViaje() === $this) {
                $d->setViaje(null);
            }
        }
        return $this;
    }

    /** @return Collection<int, Actividad> */
    public function getActividades(): Collection {
        return $this->actividades;
    }
    public function addActividad(Actividad $a): self {
        if (!$this->actividades->contains($a)) {
            $this->actividades->add($a);
            $a->setViaje($this);
        }
        return $this;
    }
    public function removeActividad(Actividad $a): self {
        if ($this->actividades->removeElement($a)) {
            if ($a->getViaje() === $this) {
                $a->setViaje(null);
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
            $a->setViaje($this);
        }
        return $this;
    }
    public function removeAlojamiento(Alojamiento $a): self {
        if ($this->alojamientos->removeElement($a)) {
            if ($a->getViaje() === $this) {
                $a->setViaje(null);
            }
        }
        return $this;
    }

    /** @return Collection<int, Gasto> */
    public function getGastos(): Collection {
        return $this->gastos;
    }
    public function addGasto(Gasto $g): self {
        if (!$this->gastos->contains($g)) {
            $this->gastos->add($g);
            $g->setViaje($this);
        }
        return $this;
    }
    public function removeGasto(Gasto $g): self {
        if ($this->gastos->removeElement($g)) {
            if ($g->getViaje() === $this) {
                $g->setViaje(null);
            }
        }
        return $this;
    }

    /** @return Collection<int, AportacionBote> */
    public function getAportacionesBote(): Collection {
        return $this->aportacionesBote;
    }
    public function addAportacionBote(AportacionBote $a): self {
        if (!$this->aportacionesBote->contains($a)) {
            $this->aportacionesBote->add($a);
            $a->setViaje($this);
        }
        return $this;
    }
    public function removeAportacionBote(AportacionBote $a): self {
        if ($this->aportacionesBote->removeElement($a)) {
            if ($a->getViaje() === $this) {
                $a->setViaje(null);
            }
        }
        return $this;
    }

    /** @return Collection<int, Imagen> */
    public function getImagenes(): Collection {
        return $this->imagenes;
    }
    public function addImagen(Imagen $i): self {
        if (!$this->imagenes->contains($i)) {
            $this->imagenes->add($i);
            $i->setViaje($this);
        }
        return $this;
    }
    public function removeImagen(Imagen $i): self {
        if ($this->imagenes->removeElement($i)) {
            if ($i->getViaje() === $this) {
                $i->setViaje(null);
            }
        }
        return $this;
    }

    /** @return Collection<int, Propuesta> */
    public function getPropuestas(): Collection {
        return $this->propuestas;
    }
    public function addPropuesta(Propuesta $p): self {
        if (!$this->propuestas->contains($p)) {
            $this->propuestas->add($p);
            $p->setViaje($this);
        }
        return $this;
    }
    public function removePropuesta(Propuesta $p): self {
        if ($this->propuestas->removeElement($p)) {
            if ($p->getViaje() === $this) {
                $p->setViaje(null);
            }
        }
        return $this;
    }

    /** @return Collection<int, Comentario> */
    public function getComentarios(): Collection {
        return $this->comentarios;
    }
    public function addComentario(Comentario $c): self {
        if (!$this->comentarios->contains($c)) {
            $this->comentarios->add($c);
            $c->setViaje($this);
        }
        return $this;
    }
    public function removeComentario(Comentario $c): self {
        if ($this->comentarios->removeElement($c)) {
            if ($c->getViaje() === $this) {
                $c->setViaje(null);
            }
        }
        return $this;
    }

    /** @return Collection<int, Notificacion> */
    public function getNotificaciones(): Collection {
        return $this->notificaciones;
    }
    public function addNotificacion(Notificacion $n): self {
        if (!$this->notificaciones->contains($n)) {
            $this->notificaciones->add($n);
            $n->setViaje($this);
        }
        return $this;
    }
    public function removeNotificacion(Notificacion $n): self {
        if ($this->notificaciones->removeElement($n)) {
            if ($n->getViaje() === $this) {
                $n->setViaje(null);
            }
        }
        return $this;
    }

    /** @return Collection<int, Invitacion> */
    public function getInvitaciones(): Collection {
        return $this->invitaciones;
    }
    public function addInvitacion(Invitacion $i): self {
        if (!$this->invitaciones->contains($i)) {
            $this->invitaciones->add($i);
            $i->setViaje($this);
        }
        return $this;
    }
    public function removeInvitacion(Invitacion $i): self {
        if ($this->invitaciones->removeElement($i)) {
            if ($i->getViaje() === $this) {
                $i->setViaje(null);
            }
        }
        return $this;
    }
}
