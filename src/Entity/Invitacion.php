<?php

namespace App\Entity;

use App\Repository\InvitacionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InvitacionRepository::class)]
#[ORM\Table(name: 'invitaciones')]
#[ORM\HasLifecycleCallbacks]
class Invitacion {
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: "Ramsey\Uuid\Doctrine\UuidGenerator")]
    #[ORM\Column(type: 'guid', unique: true)]
    private ?string $id = null;

    #[ORM\ManyToOne(targetEntity: Viaje::class, inversedBy: 'invitaciones')]
    #[ORM\JoinColumn(name: 'viaje_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private ?Viaje $viaje = null;

    #[ORM\Column(name: 'email_invitado', length: 255, nullable: true)]
    private ?string $emailInvitado = null;

    #[ORM\ManyToOne(targetEntity: Usuario::class)]
    #[ORM\JoinColumn(name: 'usuario_invitado_id', referencedColumnName: 'id', nullable: true)]
    private ?Usuario $usuarioInvitado = null;

    #[ORM\ManyToOne(targetEntity: Usuario::class)]
    #[ORM\JoinColumn(name: 'invitado_por', referencedColumnName: 'id', nullable: false)]
    private ?Usuario $invitadoPor = null;

    #[ORM\Column(length: 32)]
    private string $estado = 'pendiente';

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $tipo = 'participante';

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $mensaje = null;

    #[ORM\Column(name: 'enlace_invitacion', length: 1024, nullable: true)]
    private ?string $enlaceInvitacion = null;

    #[ORM\Column(name: 'expira_en', type: Types::DATETIMETZ_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $expiraEn = null;

    #[ORM\Column(name: 'creado_en', type: Types::DATETIMETZ_IMMUTABLE)]
    private \DateTimeImmutable $creado_en;

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
    public function getEmailInvitado(): ?string {
        return $this->emailInvitado;
    }
    public function setEmailInvitado(?string $e): self {
        $this->emailInvitado = $e;
        return $this;
    }
    public function getUsuarioInvitado(): ?Usuario {
        return $this->usuarioInvitado;
    }
    public function setUsuarioInvitado(?Usuario $u): self {
        $this->usuarioInvitado = $u;
        return $this;
    }
    public function getInvitadoPor(): ?Usuario {
        return $this->invitadoPor;
    }
    public function setInvitadoPor(Usuario $u): self {
        $this->invitadoPor = $u;
        return $this;
    }
    public function getEstado(): string {
        return $this->estado;
    }
    public function setEstado(string $estado): self {
        $this->estado = $estado;
        return $this;
    }
    public function getEnlaceInvitacion(): ?string {
        return $this->enlaceInvitacion;
    }
    public function setEnlaceInvitacion(?string $s): self {
        $this->enlaceInvitacion = $s;
        return $this;
    }
    public function getExpiraEn(): ?\DateTimeImmutable {
        return $this->expiraEn;
    }
    public function getTipo(): ?string {
        return $this->tipo;
    }
    
    public function setTipo(?string $tipo): static {
        $this->tipo = $tipo;
        return $this;
    }
    
    public function getMensaje(): ?string {
        return $this->mensaje;
    }
    
    public function setMensaje(?string $mensaje): static {
        $this->mensaje = $mensaje;
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
