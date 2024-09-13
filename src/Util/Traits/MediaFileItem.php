<?php

namespace App\Util\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Serializer\Annotation\Groups;

trait MediaFileItem
{

    #[Vich\UploadableField(mapping: 'media_file', fileNameProperty: 'fileName', size: 'fileSize')]
    private ?File $file = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['read:media','read:profile'])]
    private ?string $fileName = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['read:media','read:profile'])]
    private ?int $fileSize = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedFileAt = null;

    #[Groups(['read:media','read:profile'])]
    public ?string $contentUrl = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['read:media','read:profile'])]
    private ?string $alternativeUrl = null;

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $file
     */
    public function setFile(?File $file = null): void
    {
        $this->file = $file;

        if (null !== $file) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedFileAt = new \DateTimeImmutable();
        }
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setFileName(?string $fileName): void
    {
        $this->fileName = $fileName;
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setfileSize(?int $fileSize): void
    {
        $this->fileSize = $fileSize;
    }

    public function getfileSize(): ?int
    {
        return $this->fileSize;
    }

    public function setCompleteUrl(?string $contentUrl,string $field)
    {
        $this->$field = $contentUrl;
    }

    public function getSerializerFields() : array 
    {
        return [
            "file" => "contentUrl"
        ];
    }

    public function setAlternativeUrl(?string $alternativeUrl): void
    {
        $this->alternativeUrl = $alternativeUrl;
    }

    public function getAlternativeUrl(): ?string
    {
        return $this->alternativeUrl;
    }
}