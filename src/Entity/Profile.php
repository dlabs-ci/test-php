<?php
declare(strict_types=1);

namespace BOF\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="profiles")
 */
class Profile
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", name="profile_id")
     * @ORM\GeneratedValue
     */
    protected $id;

    /** @ORM\Column(type="string", name="profile_name") */
    protected $name = '';

    /**
     * @ORM\OneToMany(targetEntity="BOF\Entity\YearlyReportCalculatedRow", mappedBy="profile")
     */
    protected $calculatedRows;

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->calculatedRows = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCalculatedRows(): iterable
    {
        return $this->calculatedRows;
    }

    public function addCalculatedRow(YearlyReportCalculatedRow $row): self
    {
        $this->calculatedRows->add($row);
        return $this;
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
