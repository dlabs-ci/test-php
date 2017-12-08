<?php
declare(strict_types=1);

namespace BOF\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="BOF\Repository\YearlyReportCalculatedRowRepository")
 * @ORM\Table(name="yearly_report_row")
 */
class YearlyReportCalculatedRow
{
    const EMPTY_PLACEHOLDER = 'n/a';

    public static $yearMonthDateFormat = '%Y-%b';
    public static $months = ['jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec'];

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="BOF\Entity\YearlyReport", inversedBy="dataRows")
     * @ORM\JoinColumn(name="report_id", referencedColumnName="id")
     */
    protected $report;

    /**
     * @ORM\ManyToOne(targetEntity="BOF\Entity\Profile", inversedBy="calculatedRows")
     * @ORM\JoinColumn(name="profile_id", referencedColumnName="id")
     */
    protected $profile;

    /** @ORM\Column(type="integer") */
    protected $jan = 0;
    /** @ORM\Column(type="integer") */
    protected $feb = 0;
    /** @ORM\Column(type="integer") */
    protected $mar = 0;
    /** @ORM\Column(type="integer") */
    protected $apr = 0;
    /** @ORM\Column(type="integer") */
    protected $may = 0;
    /** @ORM\Column(type="integer") */
    protected $jun = 0;
    /** @ORM\Column(type="integer") */
    protected $jul = 0;
    /** @ORM\Column(type="integer") */
    protected $aug = 0;
    /** @ORM\Column(type="integer") */
    protected $sep = 0;
    /** @ORM\Column(type="integer") */
    protected $oct = 0;
    /** @ORM\Column(type="integer") */
    protected $nov = 0;
    /** @ORM\Column(type="integer") */
    protected $dec = 0;

    public function __call($name, $arguments)
    {
        $months = implode('|', array_map(function($m) { return ucfirst($m); }, self::$months));
        if (preg_match("#^(get|[{$months}])#", $name) === 1) {
            $varName = strtolower(substr($name,3));
            return $this->{$varName};
        } elseif (preg_match("#^(set|[{$months}])#", $name) === 1) {
            $varName = strtolower(substr($name,3));
            $val = array_shift($arguments);
            if (!is_int($val)) {
                throw new \Exception('Wrong data type for report field');
            }
            $this->{$varName} = $val;
            return $this;
        }

        throw new \Exception(sprintf('Unknown method access %', $name));
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setReport(YearlyReport $report): self
    {
        $this->report = $report;
        return $this;
    }

    public function getReport(): ?YearlyReport
    {
        return $this->report;
    }

    public function setProfile(Profile $profile): self
    {
        $this->profile = $profile;
        return $this;
    }

    public function getProfile(): ?Profile
    {
        return $this->profile;
    }


    public function toArray(): iterable
    {
        $result = [$this->profile->getName()];
        foreach (self::$months as $month) {
            $result[] = $this->{$month}??self::EMPTY_PLACEHOLDER;
        }
        return $result;
    }

    public function __clone()
    {
        $this->id = null;
    }
}
