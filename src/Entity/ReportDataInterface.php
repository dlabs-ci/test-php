<?php
declare(strict_types=1);

namespace BOF\Entity;

interface ReportDataInterface
{
    public function getDataRows(): iterable;
}
