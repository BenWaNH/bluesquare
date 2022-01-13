<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\MediaObjectRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation\UploadableField;

#[ORM\Entity(repositoryClass: MediaObjectRepository::class)]
#[ApiResource]
class MediaObject
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[ApiProperty(identifier: false)]
    private int $id;

    #[ORM\Column(type: 'uuid', unique: true)]
    #[ApiProperty(identifier: true)]
    protected string $uuid;

    #[ApiProperty(iri: 'https://schema.org/contentUrl')]
    #[Groups(['media_object:read', 'read'])]
    protected ?string $contentUrl = null;

    #[UploadableField(mapping: 'media_object', fileNameProperty: 'filePath', size: 'size', mimeType: 'mimeType', originalName: 'originalName')]
    protected ?File $file = null;

    /**
     * @ORM\Column(nullable=true)
     */
    protected ?string $filePath = null;

    /**
     * @ORM\Column(nullable=true)
     */
    #[Groups(['media_object:read', 'read'])]
    protected ?string $originalName = null;

    /**
     * @ORM\Column(nullable=true)
     */
    protected ?string $mimeType = null;

    /**
     * @ORM\Column(nullable=true)
     */
    #[Groups(['media_object:read'])]
    protected ?string $size = null;

    public function __construct()
    {
        $this->uuid = Uuid::v4();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * @return string|null
     */
    public function getContentUrl(): ?string
    {
        return $this->contentUrl;
    }

    /**
     * @param string|null $contentUrl
     * @return MediaObject
     */
    public function setContentUrl(?string $contentUrl): MediaObject
    {
        $this->contentUrl = $contentUrl;
        return $this;
    }

    /**
     * @return File|null
     */
    public function getFile(): ?File
    {
        return $this->file;
    }

    /**
     * @param File|null $file
     * @return MediaObject
     */
    public function setFile(?File $file): MediaObject
    {
        $this->file = $file;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFilePath(): ?string
    {
        return $this->filePath;
    }

    /**
     * @param string|null $filePath
     * @return MediaObject
     */
    public function setFilePath(?string $filePath): MediaObject
    {
        $this->filePath = $filePath;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getOriginalName(): ?string
    {
        return $this->originalName;
    }

    /**
     * @param string|null $originalName
     * @return MediaObject
     */
    public function setOriginalName(?string $originalName): MediaObject
    {
        $this->originalName = $originalName;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    /**
     * @param string|null $mimeType
     * @return MediaObject
     */
    public function setMimeType(?string $mimeType): MediaObject
    {
        $this->mimeType = $mimeType;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSize(): ?string
    {
        $units = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        $power = $this->size > 0 ? floor(log($this->size, 1024)) : 0;
        return number_format($this->size / pow(1024, $power), 2, '.', ',') . ' ' . $units[$power];

//        return $this->size;
    }

    /**
     * @param int|null $size
     * @return MediaObject
     */
    public function setSize(?int $size): MediaObject
    {
        $this->size = $size;
        return $this;
    }
}
