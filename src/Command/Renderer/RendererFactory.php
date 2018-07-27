<?php
/**
 * Created by PhpStorm.
 * User: zoki
 * Date: 27/07/2018
 * Time: 11:42
 */

namespace BOF\Command\Renderer;

use BOF\Command\Renderer\Exception\RendererNotValidException;
use Symfony\Component\Console\Output\OutputInterface;


class RendererFactory
{

    const RENDERER_TABLE = "TABLE";
    const RENDERER_CSV = "CSV";
    const XX = "CSV";

    /**
     * Make Renderer
     *
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

    /**
     * Return available renderers.
     *
     * @return array
     * @throws \ReflectionException
     */
    public static function getAvailableRenderers()
    {
        $refl = new \ReflectionClass(self::class);
        return array_filter($refl->getConstants(), function ($value, $name) {
            return strpos($name, "RENDERER_") === 0;
        }, ARRAY_FILTER_USE_BOTH);
    }
}