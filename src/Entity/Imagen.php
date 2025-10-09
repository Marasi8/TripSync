<?php

namespace App\Entity;

use App\Repository\ImagenRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ImagenRepository::class)]
#[ORM\Table(name: 'imagenes')]
#[ORM\HasLifecycleCallbacks]
class Imagen {
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: "Ramsey\Uuid\Doctrine\UuidGenerator")]
    #[ORM\Column(type: 'guid', unique: true)]
    private ?string $id = null;

    #[ORM\ManyToOne(targetEntity: Viaje::class, inversedBy: 'imagenes')]
    #[ORM\JoinColumn(name: 'viaje_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private ?Viaje $viaje = null;

    #[ORM\ManyToOne(targetEntity: Usuario::class)]
    #[ORM\JoinColumn(name: 'subido_por', referencedColumnName: 'id', nullable: true)]
    private ?Usuario $subido_por = null;

    #[ORM\Column(name: 'nombre_archivo', length: 512, nullable: true)]
    private ?string $nombre_archivo = null;

    #[ORM\Column(name: 'url', length: 1024, nullable: true)]
    private ?string $url = null;

    #[ORM\Column(name: 'miniatura_url', length: 1024, nullable: true)]
    private ?string $miniatura_url = null;

    #[ORM\Column(name: 'tamanyo_bytes', type: Types::INTEGER, nullable: true)]
    private ?int $tamanyo_bytes = null;

    #[ORM\Column(name: 'mime_type', length: 64, nullable: true)]
    private ?string $mime_type = null;

    #[ORM\Column(name: 'relacion_tipo', length: 32, nullable: true)]
    private ?string $relacion_tipo = null;

    #[ORM\Column(name: 'relacion_id', type: 'guid', nullable: true)]
    private ?string $relacion_id = null;

    #[ORM\Column(name: 'creado_en', type: Types::DATETIMETZ_IMMUTABLE)]
    private \DateTimeImmutable $creado_en;

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

    public function getSubidoPor(): ?Usuario {
        return $this->subido_por;
    }
    public function setSubidoPor(?Usuario $u): self {
        $this->subido_por = $u;
        return $this;
    }

    public function getNombreArchivo(): ?string {
        return $this->nombre_archivo;
    }
    public function setNombreArchivo(?string $s): self {
        $this->nombre_archivo = $s;
        return $this;
    }

    public function getUrl(): ?string {
        return $this->url;
    }
    public function setUrl(?string $s): self {
        $this->url = $s;
        return $this;
    }

    public function getMiniaturaUrl(): ?string {
        return $this->miniatura_url;
    }
    public function setMiniaturaUrl(?string $s): self {
        $this->miniatura_url = $s;
        return $this;
    }

    public function getTamanyoBytes(): ?int {
        return $this->tamanyo_bytes;
    }
    public function setTamanyoBytes(?int $n): self {
        $this->tamanyo_bytes = $n;
        return $this;
    }

    public function getMimeType(): ?string {
        return $this->mime_type;
    }
    public function setMimeType(?string $s): self {
        $this->mime_type = $s;
        return $this;
    }

    public function getRelacionTipo(): ?string {
        return $this->relacion_tipo;
    }
    public function setRelacionTipo(?string $s): self {
        $this->relacion_tipo = $s;
        return $this;
    }

    public function getRelacionId(): ?string {
        return $this->relacion_id;
    }
    public function setRelacionId(?string $id): self {
        $this->relacion_id = $id;
        return $this;
    }

    public function getCreadoEn(): \DateTimeImmutable {
        return $this->creado_en;
    }
}
