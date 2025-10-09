<?php

namespace App\Entity;

use App\Repository\VotoRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Mapea la tabla `votos` con clave primaria compuesta (propuesta_id, usuario_id).
 */
#[ORM\Entity(repositoryClass: VotoRepository::class)]
#[ORM\Table(name: 'votos')]
#[ORM\HasLifecycleCallbacks]
class Voto {
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Propuesta::class)]
    #[ORM\JoinColumn(name: 'propuesta_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private ?Propuesta $propuesta = null;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Usuario::class)]
    #[ORM\JoinColumn(name: 'usuario_id', referencedColumnName: 'id', nullable: false)]
    private ?Usuario $usuario = null;

    #[ORM\Column(length: 16)]
    private string $opcion;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $comentario = null;

    #[ORM\Column(name: 'creado_en', type: Types::DATETIMETZ_IMMUTABLE)]
    private \DateTimeImmutable $creado_en;

    public function __construct() {
        $this->opcion = '';
        $this->creado_en = new \DateTimeImmutable();
    }

    public function getId(): ?string {
        return null; // No tiene ID individual, usa clave compuesta
    }
    
    public function setId(string $id): self {
        // No aplicable para clave compuesta
        return $this;
    }
    
    public function getTitulo(): string {
        return 'Voto: ' . $this->opcion;
    }
    
    public function getPropuesta(): ?Propuesta {
        return $this->propuesta;
    }
    public function setPropuesta(Propuesta $p): self {
        $this->propuesta = $p;
        return $this;
    }
    public function getUsuario(): ?Usuario {
        return $this->usuario;
    }
    public function setUsuario(Usuario $u): self {
        $this->usuario = $u;
        return $this;
    }
    public function getOpcion(): string {
        return $this->opcion;
    }
    public function setOpcion(string $o): self {
        $this->opcion = $o;
        return $this;
    }
    public function getComentario(): ?string {
        return $this->comentario;
    }
    public function setComentario(?string $c): self {
        $this->comentario = $c;
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
