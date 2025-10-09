<?php

namespace App\Entity;

use App\Repository\UsuarioRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UsuarioRepository::class)]
#[ORM\Table(name: 'usuarios')]
#[ORM\HasLifecycleCallbacks]
class Usuario implements UserInterface, PasswordAuthenticatedUserInterface {
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: "Ramsey\Uuid\Doctrine\UuidGenerator")]
    #[ORM\Column(type: 'uuid', unique: true)]
    private ?string $id = null;

    #[ORM\Column(length: 255, unique: true)]
    private string $correo;

    #[ORM\Column(length: 255)]
    private string $contrasena_hash;

    #[ORM\Column(length: 150, unique: true)]
    private string $username;

    #[ORM\Column(length: 200)]
    private string $nombre;

    #[ORM\Column(length: 1024, nullable: true)]
    private ?string $img_avatar_url = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $bio = null;

    #[ORM\Column(type: 'boolean', options: ['default' => true])]
    private bool $activo = true;

    #[ORM\Column(type: Types::JSON)]
    private array $config_privacidad = [
        'perfil' => 'privada',
        'imagenes' => 'privada',
        'comentarios' => 'privada',
        'gastos' => 'privada',
    ];

    #[ORM\Column(type: Types::JSON)]
    private array $roles = ['ROLE_USER'];

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE, options: ['default' => 'now()'])]
    private \DateTimeImmutable $creado_en;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE, options: ['default' => 'now()'])]
    private \DateTimeImmutable $actualizado_en;


    public function __construct() {
        $this->correo = '';
        $this->contrasena_hash = '';
        $this->username = '';
        $this->nombre = '';
        $now = new \DateTimeImmutable();
        
        $this->creado_en = $now;
        $this->actualizado_en = $now;

        // Inicializar colecciones inversas
        $this->participaciones = new ArrayCollection();
        $this->comentarios = new ArrayCollection();
        $this->notificaciones = new ArrayCollection();
        $this->invitacionesEnviadas = new ArrayCollection();
        $this->invitacionesRecibidas = new ArrayCollection();
        $this->imagenesSubidas = new ArrayCollection();
        $this->propuestasCreadas = new ArrayCollection();
        $this->actividadesCreadas = new ArrayCollection();
        $this->destinosCreados = new ArrayCollection();
        $this->alojamientosCreados = new ArrayCollection();
        $this->gastosPagados = new ArrayCollection();
        $this->aportacionesBote = new ArrayCollection();
        $this->valoraciones = new ArrayCollection();
        $this->votos = new ArrayCollection();
        $this->viajesCreados = new ArrayCollection();
        $this->viajesAdministrados = new ArrayCollection();
    }

    public function getId(): ?string {
        return $this->id;
    }

    public function setId(string $id): static {
        $this->id = $id;

        return $this;
    }

    public function getCorreo(): ?string {
        return $this->correo;
    }

    public function setCorreo(string $correo): static {
        $this->correo = $correo;

        return $this;
    }

    public function getContrasenaHash(): ?string {
        return $this->contrasena_hash;
    }

    public function setContrasenaHash(string $contrasena_hash): static {
        $this->contrasena_hash = $contrasena_hash;

        return $this;
    }

    public function getUsername(): ?string {
        return $this->username;
    }

    public function setUsername(string $username): static {
        $this->username = $username;

        return $this;
    }

    public function getNombre(): ?string {
        return $this->nombre;
    }

    public function setNombre(string $nombre): static {
        $this->nombre = $nombre;

        return $this;
    }

    public function getImgAvatarUrl(): ?string {
        return $this->img_avatar_url;
    }

    public function setImgAvatarUrl(?string $img_avatar_url): static {
        $this->img_avatar_url = $img_avatar_url;

        return $this;
    }

    public function getBio(): ?string {
        return $this->bio;
    }

    public function setBio(?string $bio): static {
        $this->bio = $bio;

        return $this;
    }

    public function isActivo(): ?bool {
        return $this->activo;
    }

    public function setActivo(bool $activo): static {
        $this->activo = $activo;

        return $this;
    }

    public function getConfigPrivacidad(): array {
        return $this->config_privacidad;
    }

    public function setConfigPrivacidad(array $config_privacidad): static {
        $this->config_privacidad = $config_privacidad;

        return $this;
    }

    public function getCreadoEn(): ?\DateTimeImmutable {
        return $this->creado_en;
    }

    public function setCreadoEn(\DateTimeImmutable $creado_en): static {
        $this->creado_en = $creado_en;

        return $this;
    }

    public function getActualizadoEn(): ?\DateTimeImmutable {
        return $this->actualizado_en;
    }

    public function setActualizadoEn(\DateTimeImmutable $actualizado_en): static {
        $this->actualizado_en = $actualizado_en;

        return $this;
    }

    public function getPassword(): ?string {
        return $this->contrasena_hash;
    }

    public function getRoles(): array {
        return $this->roles;
    }

    public function eraseCredentials(): void {
        // No hay datos sensibles para borrar
    }

    public function getUserIdentifier(): string {
        return $this->username;
    }

    public function setRoles(array $roles): static {
        $this->roles = $roles;

        return $this;
    }

    // Relaciones inversas (OneToMany)

    // Participaciones en viajes
    #[ORM\OneToMany(mappedBy: 'usuario', targetEntity: Participante::class)]
    private Collection $participaciones;
    /** @return Collection<int, Participante> */
    public function getParticipaciones(): Collection {
        return $this->participaciones;
    }
    public function addParticipacion(Participante $p): static {
        if (!$this->participaciones->contains($p)) {
            $this->participaciones->add($p);
            $p->setUsuario($this);
        }
        return $this;
    }
    public function removeParticipacion(Participante $p): static {
        if ($this->participaciones->removeElement($p) && $p->getUsuario() === $this) {
            $p->setUsuario(null);
        }
        return $this;
    }

    // Comentarios creados por el usuario
    #[ORM\OneToMany(mappedBy: 'usuario', targetEntity: Comentario::class)]
    private Collection $comentarios;
    /** @return Collection<int, Comentario> */
    public function getComentarios(): Collection {
        return $this->comentarios;
    }
    public function addComentario(Comentario $c): static {
        if (!$this->comentarios->contains($c)) {
            $this->comentarios->add($c);
            $c->setUsuario($this);
        }
        return $this;
    }
    public function removeComentario(Comentario $c): static {
        // usuario_id es NOT NULL en comentarios; no se debe desasociar a null
        $this->comentarios->removeElement($c);
        return $this;
    }

    // Notificaciones recibidas
    #[ORM\OneToMany(mappedBy: 'usuario', targetEntity: Notificacion::class)]
    private Collection $notificaciones;
    /** @return Collection<int, Notificacion> */
    public function getNotificaciones(): Collection {
        return $this->notificaciones;
    }
    public function addNotificacion(Notificacion $n): static {
        if (!$this->notificaciones->contains($n)) {
            $this->notificaciones->add($n);
            $n->setUsuario($this);
        }
        return $this;
    }
    public function removeNotificacion(Notificacion $n): static {
        // usuario_id es NOT NULL en notificaciones
        $this->notificaciones->removeElement($n);
        return $this;
    }

    // Invitaciones (enviadas por y recibidas por el usuario)
    #[ORM\OneToMany(mappedBy: 'invitadoPor', targetEntity: Invitacion::class)]
    private Collection $invitacionesEnviadas;
    /** @return Collection<int, Invitacion> */
    public function getInvitacionesEnviadas(): Collection {
        return $this->invitacionesEnviadas;
    }
    public function addInvitacionEnviada(Invitacion $i): static {
        if (!$this->invitacionesEnviadas->contains($i)) {
            $this->invitacionesEnviadas->add($i);
            $i->setInvitadoPor($this);
        }
        return $this;
    }
    public function removeInvitacionEnviada(Invitacion $i): static {
        // invitado_por es NOT NULL en invitaciones
        $this->invitacionesEnviadas->removeElement($i);
        return $this;
    }

    #[ORM\OneToMany(mappedBy: 'usuarioInvitado', targetEntity: Invitacion::class)]
    private Collection $invitacionesRecibidas;
    /** @return Collection<int, Invitacion> */
    public function getInvitacionesRecibidas(): Collection {
        return $this->invitacionesRecibidas;
    }
    public function addInvitacionRecibida(Invitacion $i): static {
        if (!$this->invitacionesRecibidas->contains($i)) {
            $this->invitacionesRecibidas->add($i);
            $i->setUsuarioInvitado($this);
        }
        return $this;
    }
    public function removeInvitacionRecibida(Invitacion $i): static {
        if ($this->invitacionesRecibidas->removeElement($i) && $i->getUsuarioInvitado() === $this) {
            $i->setUsuarioInvitado(null);
        }
        return $this;
    }

    // Imágenes subidas por el usuario
    #[ORM\OneToMany(mappedBy: 'subidoPor', targetEntity: Imagen::class)]
    private Collection $imagenesSubidas;
    /** @return Collection<int, Imagen> */
    public function getImagenesSubidas(): Collection {
        return $this->imagenesSubidas;
    }
    public function addImagenSubida(Imagen $img): static {
        if (!$this->imagenesSubidas->contains($img)) {
            $this->imagenesSubidas->add($img);
            $img->setSubidoPor($this);
        }
        return $this;
    }
    public function removeImagenSubida(Imagen $img): static {
        if ($this->imagenesSubidas->removeElement($img) && $img->getSubidoPor() === $this) {
            $img->setSubidoPor(null);
        }
        return $this;
    }

    // Propuestas creadas por el usuario
    #[ORM\OneToMany(mappedBy: 'creadoPor', targetEntity: Propuesta::class)]
    private Collection $propuestasCreadas;
    /** @return Collection<int, Propuesta> */
    public function getPropuestasCreadas(): Collection {
        return $this->propuestasCreadas;
    }
    public function addPropuestaCreada(Propuesta $p): static {
        if (!$this->propuestasCreadas->contains($p)) {
            $this->propuestasCreadas->add($p);
            $p->setCreadoPor($this);
        }
        return $this;
    }
    public function removePropuestaCreada(Propuesta $p): static {
        // creado_por es NOT NULL en propuestas
        $this->propuestasCreadas->removeElement($p);
        return $this;
    }

    // Actividades/Destinos/Alojamientos creados por el usuario
    #[ORM\OneToMany(mappedBy: 'creado_por', targetEntity: Actividad::class)]
    private Collection $actividadesCreadas;
    /** @return Collection<int, Actividad> */
    public function getActividadesCreadas(): Collection {
        return $this->actividadesCreadas;
    }
    public function addActividadCreada(Actividad $a): static {
        if (!$this->actividadesCreadas->contains($a)) {
            $this->actividadesCreadas->add($a);
            $a->setCreadoPor($this);
        }
        return $this;
    }
    public function removeActividadCreada(Actividad $a): static {
        if ($this->actividadesCreadas->removeElement($a) && $a->getCreadoPor() === $this) {
            $a->setCreadoPor(null);
        }
        return $this;
    }

    #[ORM\OneToMany(mappedBy: 'creado_por', targetEntity: Destino::class)]
    private Collection $destinosCreados;
    /** @return Collection<int, Destino> */
    public function getDestinosCreados(): Collection {
        return $this->destinosCreados;
    }
    public function addDestinoCreado(Destino $d): static {
        if (!$this->destinosCreados->contains($d)) {
            $this->destinosCreados->add($d);
            $d->setCreadoPor($this);
        }
        return $this;
    }
    public function removeDestinoCreado(Destino $d): static {
        if ($this->destinosCreados->removeElement($d) && $d->getCreadoPor() === $this) {
            $d->setCreadoPor(null);
        }
        return $this;
    }

    #[ORM\OneToMany(mappedBy: 'creado_por', targetEntity: Alojamiento::class)]
    private Collection $alojamientosCreados;
    /** @return Collection<int, Alojamiento> */
    public function getAlojamientosCreados(): Collection {
        return $this->alojamientosCreados;
    }
    public function addAlojamientoCreado(Alojamiento $a): static {
        if (!$this->alojamientosCreados->contains($a)) {
            $this->alojamientosCreados->add($a);
            $a->setCreadoPor($this);
        }
        return $this;
    }
    public function removeAlojamientoCreado(Alojamiento $a): static {
        if ($this->alojamientosCreados->removeElement($a) && $a->getCreadoPor() === $this) {
            $a->setCreadoPor(null);
        }
        return $this;
    }

    // Gastos pagados por el usuario
    #[ORM\OneToMany(mappedBy: 'pagador', targetEntity: Gasto::class)]
    private Collection $gastosPagados;
    /** @return Collection<int, Gasto> */
    public function getGastosPagados(): Collection {
        return $this->gastosPagados;
    }
    public function addGastoPagado(Gasto $g): static {
        if (!$this->gastosPagados->contains($g)) {
            $this->gastosPagados->add($g);
            $g->setPagador($this);
        }
        return $this;
    }
    public function removeGastoPagado(Gasto $g): static {
        if ($this->gastosPagados->removeElement($g) && $g->getPagador() === $this) {
            $g->setPagador(null);
        }
        return $this;
    }

    // Aportaciones al bote realizadas por el usuario
    #[ORM\OneToMany(mappedBy: 'usuario', targetEntity: AportacionBote::class)]
    private Collection $aportacionesBote;
    /** @return Collection<int, AportacionBote> */
    public function getAportacionesBote(): Collection {
        return $this->aportacionesBote;
    }
    public function addAportacionBote(AportacionBote $a): static {
        if (!$this->aportacionesBote->contains($a)) {
            $this->aportacionesBote->add($a);
            $a->setUsuario($this);
        }
        return $this;
    }
    public function removeAportacionBote(AportacionBote $a): static {
        if ($this->aportacionesBote->removeElement($a) && $a->getUsuario() === $this) {
            $a->setUsuario(null);
        }
        return $this;
    }

    // Valoraciones realizadas por el usuario
    #[ORM\OneToMany(mappedBy: 'usuario', targetEntity: Valoracion::class)]
    private Collection $valoraciones;
    /** @return Collection<int, Valoracion> */
    public function getValoraciones(): Collection {
        return $this->valoraciones;
    }
    public function addValoracion(Valoracion $v): static {
        if (!$this->valoraciones->contains($v)) {
            $this->valoraciones->add($v);
            $v->setUsuario($this);
        }
        return $this;
    }
    public function removeValoracion(Valoracion $v): static {
        if ($this->valoraciones->removeElement($v) && $v->getUsuario() === $this) {
            $v->setUsuario(null);
        }
        return $this;
    }

    // Votos emitidos por el usuario
    #[ORM\OneToMany(mappedBy: 'usuario', targetEntity: Voto::class)]
    private Collection $votos;
    /** @return Collection<int, Voto> */
    public function getVotos(): Collection {
        return $this->votos;
    }
    public function addVoto(Voto $v): static {
        if (!$this->votos->contains($v)) {
            $this->votos->add($v);
            $v->setUsuario($this);
        }
        return $this;
    }
    public function removeVoto(Voto $v): static {
        // usuario_id es parte de la PK compuesta y NOT NULL
        $this->votos->removeElement($v);
        return $this;
    }

    // Viajes creados por el usuario y administrados por el usuario
    #[ORM\OneToMany(mappedBy: 'creado_por', targetEntity: Viaje::class)]
    private Collection $viajesCreados;
    /** @return Collection<int, Viaje> */
    public function getViajesCreados(): Collection {
        return $this->viajesCreados;
    }
    public function addViajeCreado(Viaje $v): static {
        if (!$this->viajesCreados->contains($v)) {
            $this->viajesCreados->add($v);
            $v->setCreadoPor($this);
        }
        return $this;
    }
    public function removeViajeCreado(Viaje $v): static {
        if ($this->viajesCreados->removeElement($v) && $v->getCreadoPor() === $this) {
            $v->setCreadoPor(null);
        }
        return $this;
    }

    #[ORM\OneToMany(mappedBy: 'admin_actual', targetEntity: Viaje::class)]
    private Collection $viajesAdministrados;
    /** @return Collection<int, Viaje> */
    public function getViajesAdministrados(): Collection {
        return $this->viajesAdministrados;
    }
    public function addViajeAdministrado(Viaje $v): static {
        if (!$this->viajesAdministrados->contains($v)) {
            $this->viajesAdministrados->add($v);
            $v->setAdminActual($this);
        }
        return $this;
    }
    public function removeViajeAdministrado(Viaje $v): static {
        if ($this->viajesAdministrados->removeElement($v) && $v->getAdminActual() === $this) {
            $v->setAdminActual(null);
        }
        return $this;
    }

    /**
     * Se ejecuta automáticamente antes de actualizar la entidad
     */
    #[ORM\PreUpdate]
    public function actualizarTimestamp(): void {
        $this->actualizado_en = new \DateTimeImmutable();
    }
}
