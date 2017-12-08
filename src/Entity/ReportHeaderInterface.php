<?php
declare(strict_types=1);

namespace BOF\Entity;

interface ReportHeaderInterface
{
    public function getHeaderRow(): iterable;
}
