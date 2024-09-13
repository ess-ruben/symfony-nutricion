<?php

namespace App\Entity\Client;

use App\Entity\Calendar\Meeting;
use App\EntityListener\Client\UserMeasureListener;
use App\Repository\Client\UserMeasureRepository;
use App\Util\Interfaces\BusinessInterface;
use App\Util\Interfaces\MediaInterface;
use App\Util\Interfaces\UserInterface;
use App\Util\Interfaces\TimeInterface;
use App\Util\Traits\BusinessItem;
use App\Util\Traits\MediaFileItem;
use App\Util\Traits\UserForceItem;
use App\Util\Traits\TimeItem;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[Vich\Uploadable]
#[ORM\EntityListeners([UserMeasureListener::class])]
#[ORM\Entity(repositoryClass: UserMeasureRepository::class)]
class UserMeasure implements UserInterface,TimeInterface,BusinessInterface, MediaInterface
{
    
    use UserForceItem;
    use TimeItem;
    use BusinessItem;
    use MediaFileItem;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true, options: ['default' => 0])]
    private ?float $bicipital = null;

    #[ORM\Column(nullable: true, options: ['default' => 0])]
    private ?float $tricipital = null;

    #[ORM\Column(nullable: true, options: ['default' => 0])]
    private ?float $subscapularis = null;

    #[ORM\Column(nullable: true, options: ['default' => 0])]
    private ?float $suprailiac = null;

    #[ORM\Column(nullable: true, options: ['default' => 0])]
    private ?float $abdominal = null;

    #[ORM\Column(nullable: true, options: ['default' => 0])]
    private ?float $frontThigh = null;

    #[ORM\Column(nullable: true, options: ['default' => 0])]
    private ?float $medialCalf = null;

    #[ORM\Column(nullable: true, options: ['default' => 0])]
    private ?float $waist = null;

    #[ORM\Column(nullable: true, options: ['default' => 0])]
    private ?float $hip = null;

    #[ORM\Column(nullable: true, options: ['default' => 0])]
    private ?float $thigh = null;

    #[ORM\Column(nullable: true, options: ['default' => 0])]
    private ?float $calf = null;

    #[ORM\Column(nullable: true, options: ['default' => 0])]
    private ?float $contractedArm = null;

    #[ORM\OneToOne(inversedBy: 'userMeasure', cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Meeting $meeting = null;

    #[ORM\Column(nullable: true, options: ['default' => 0])]
    private ?float $height = null;

    #[ORM\Column(nullable: true, options: ['default' => 0])]
    private ?float $weight = null;

    #[ORM\Column(nullable: true)]
    private ?float $years = null;

    #[ORM\Column(nullable: true)]
    private ?int $gender = null;

    #[ORM\Column(nullable: true, options: ['default' => 0])]
    private ?float $relaxedArm = null;

    #[ORM\Column(nullable: true, options: ['default' => 0])]
    private ?float $iliacCrest = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBicipital(): ?float
    {
        return $this->bicipital;
    }

    public function setBicipital(?float $bicipital): self
    {
        $this->bicipital = $bicipital;

        return $this;
    }

    public function getTricipital(): ?float
    {
        return $this->tricipital;
    }

    public function setTricipital(?float $tricipital): self
    {
        $this->tricipital = $tricipital;

        return $this;
    }

    public function getSubscapularis(): ?float
    {
        return $this->subscapularis;
    }

    public function setSubscapularis(?float $subscapularis): self
    {
        $this->subscapularis = $subscapularis;

        return $this;
    }

    public function getSuprailiac(): ?float
    {
        return $this->suprailiac;
    }

    public function setSuprailiac(?float $suprailiac): self
    {
        $this->suprailiac = $suprailiac;

        return $this;
    }

    public function getAbdominal(): ?float
    {
        return $this->abdominal;
    }

    public function setAbdominal(?float $abdominal): self
    {
        $this->abdominal = $abdominal;

        return $this;
    }

    public function getFrontThigh(): ?float
    {
        return $this->frontThigh;
    }

    public function setFrontThigh(?float $frontThigh): self
    {
        $this->frontThigh = $frontThigh;

        return $this;
    }

    public function getMedialCalf(): ?float
    {
        return $this->medialCalf;
    }

    public function setMedialCalf(?float $medialCalf): self
    {
        $this->medialCalf = $medialCalf;

        return $this;
    }

    public function getWaist(): ?float
    {
        return $this->waist;
    }

    public function setWaist(?float $waist): self
    {
        $this->waist = $waist;

        return $this;
    }

    public function getHip(): ?float
    {
        return $this->hip;
    }

    public function setHip(?float $hip): self
    {
        $this->hip = $hip;

        return $this;
    }

    public function getThigh(): ?float
    {
        return $this->thigh;
    }

    public function setThigh(?float $thigh): self
    {
        $this->thigh = $thigh;

        return $this;
    }

    public function getCalf(): ?float
    {
        return $this->calf;
    }

    public function setCalf(?float $calf): self
    {
        $this->calf = $calf;

        return $this;
    }

    public function getContractedArm(): ?float
    {
        return $this->contractedArm;
    }

    public function setContractedArm(?float $contractedArm): self
    {
        $this->contractedArm = $contractedArm;

        return $this;
    }

    public function getMeeting(): ?Meeting
    {
        return $this->meeting;
    }

    public function setMeeting(Meeting $meeting): self
    {
        $this->meeting = $meeting;

        return $this;
    }

    public function getForceUser():bool
    {
        return false;
    }

    public function getHeight(): ?float
    {
        if (empty($this->height) && !empty($this->getUser())) {
            $this->setHeight($this->getUser()->getTall() ?? 0);
        }
        return $this->height;
    }

    public function setHeight(float $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight(float $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getYears(): ?float
    {
        return $this->years;
    }

    public function setYears(float $years): self
    {
        $this->years = $years;

        return $this;
    }

    public function getGender(): ?int
    {
        if (empty($this->gender) && !empty($this->getUser())) {
            $this->setGender($this->getUser()->getGender());
        }
        return $this->gender;
    }

    public function setGender(?int $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getRelaxedArm(): ?float
    {
        return $this->relaxedArm;
    }

    public function setRelaxedArm(?float $relaxedArm): self
    {
        $this->relaxedArm = $relaxedArm;

        return $this;
    }

    public function getIliacCrest(): ?float
    {
        return $this->iliacCrest;
    }

    public function setIliacCrest(?float $iliacCrest): self
    {
        $this->iliacCrest = $iliacCrest;

        return $this;
    }
}
