<?php
declare(strict_types=1);

namespace BOF\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="yearly_report")
 */
use Doctrine\Common\Collections\ArrayCollection;

class YearlyReport implements ReportInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $year;

    /**
     * @ORM\OneToMany(targetEntity="YearlyReportCalculatedRow", mappedBy="report")
     */
    protected $dataRows;

    public function __construct(string $year)
    {
        $this->year = $year;
        $this->dataRows = new ArrayCollection();
    }

    public function getDataRows(): iterable
    {
        return $this->dataRows;
    }

    public function addDataRow(YearlyReportCalculatedRow $calculatedRow): self
    {
        $this->dataRows->add($calculatedRow);
        $calculatedRow->setReport($this);
        return $this;
    }

    public function getHeaderRow(): iterable
    {
        return [
            sprintf('Profile    %s', $this->year),
            'Jan',
            'Feb',
            'Mar',
            'Apr',
            'May',
            'Jun',
            'Jul',
            'Aug',
            'Sep',
            'Oct',
            'Nov',
            'Dec'
        ];
    }

    public function getYear(): string
    {
        return $this->year;
    }
}
