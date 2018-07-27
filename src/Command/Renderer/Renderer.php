<?php
/**
 * Created by PhpStorm.
 * User: zoki
 * Date: 27/07/2018
 * Time: 11:40
 */

namespace BOF\Command\Renderer;


interface Renderer
{
    public function render(array $reportData);
}