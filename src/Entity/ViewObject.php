<?php

namespace App\Entity;

use App\Traits\LifeCycleTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ViewObjectRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class ViewObject
{
    use LifeCycleTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=2048)
     */
    private $mediaLink;

    /**
     * @ORM\Column(type="string", length=2048, nullable=true)
     */
    private $minuteLink;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mediaType;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mediaFormat;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $minuteInterpreter;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $minuteVersion;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMediaLink(): ?string
    {
        return $this->mediaLink;
    }

    public function setMediaLink(string $mediaLink): self
    {
        $this->mediaLink = $mediaLink;

        return $this;
    }

    public function getMinuteLink(): ?string
    {
        return $this->minuteLink;
    }

    public function setMinuteLink(?string $minuteLink): self
    {
        $this->minuteLink = $minuteLink;

        return $this;
    }

    public function getMediaType(): ?string
    {
        return $this->mediaType;
    }

    public function setMediaType(string $mediaType): self
    {
        $this->mediaType = $mediaType;

        return $this;
    }

    public function getMediaFormat(): ?string
    {
        return $this->mediaFormat;
    }

    public function setMediaFormat(string $mediaFormat): self
    {
        $this->mediaFormat = $mediaFormat;

        return $this;
    }

    public function getMinuteInterpreter(): ?string
    {
        return $this->minuteInterpreter;
    }

    public function setMinuteInterpreter(?string $minuteInterpreter): self
    {
        $this->minuteInterpreter = $minuteInterpreter;

        return $this;
    }

    public function getMinuteVersion(): ?string
    {
        return $this->minuteVersion;
    }

    public function setMinuteVersion(?string $minuteVersion): self
    {
        $this->minuteVersion = $minuteVersion;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }
}
