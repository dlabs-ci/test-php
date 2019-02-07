<?php

namespace BOF\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Intl\DateFormatter\IntlDateFormatter;

/**
 * @ORM\Entity(repositoryClass="BOF\Repository\ProfileViewRepository")
 * @ORM\Table(name="profile_view")
 */
class ProfileView
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $profile_id;

    /**
     * @ORM\Column()
     */
    private $date;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $views;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProfileId(): ?int
    {
        return $this->profile_id;
    }

    public function setProfileId(int $profile_id): self
    {
        $this->profile_id = $profile_id;

        return $this;
    }

    public function getData(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setData($data): self
    {
        $this->date = $data;

        return $this;
    }

    public function getViews(): ?int
    {
        return $this->views;
    }

    public function setViews(?int $views): self
    {
        $this->views = $views;

        return $this;
    }

    public function getMonth(): ?string
    {
        return date("j", strtotime($this->date));
//        $formatter = new IntlDateFormatter(
//            'en',
//            IntlDateFormatter::FULL,
//            IntlDateFormatter::FULL
//        );
//        $tt = date("m", strtotime('2016-05-17 16:41:51'));
//        echo '<pre>';
//        print_r($tt);
//        die();
//        $formatter->setPattern('m');
//        return $formatter->format($this->date);
    }

}
