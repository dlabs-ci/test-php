<?php
/**
 * Created by PhpStorm.
 * User: zoki
 * Date: 27/07/2018
 * Time: 11:42
 */

namespace BOF\Command\Renderer;

use Symfony\Component\Console\Output\OutputInterface;


class RendererFactory
{

    const RENDERER_TABLE = "RENDERER_TABLE";
    const RENDERER_CSV = "CSV";

    /**
     * @param $type
     * @param OutputInterface $output
     * @return TableRenderer
     * @throws RendererNotImplementedException
     * @throws RendererNotValidException
     */
    public static function makeRenderer($type, OutputInterface $output)
    {
        switch ($type) {
            case self::RENDERER_TABLE:
                return new TableRenderer($output);
            case self::RENDERER_CSV:
                return new CsvRenderer($output);
        }

        throw new RendererNotValidException();
    }
}