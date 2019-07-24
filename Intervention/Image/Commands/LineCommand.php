<?php namespace Intervention\Image\Commands;
/**
 * @package    Intervention Image
 * @author     Oliver Vogel <info@olivervogel.com>
 * @copyright  Copyright 2015 Oliver Vogel
 * @license    MIT License; see license.txt
 * @link       http://image.intervention.io
 */

defined('_JEXEC') or die;

use Closure;

class LineCommand extends \Intervention\Image\Commands\AbstractCommand
{
    /**
     * Draws line on given image
     *
     * @param  \Intervention\Image\Image $image
     * @return boolean
     */
    public function execute($image)
    {
        $x1 = $this->argument(0)->type('numeric')->required()->value();
        $y1 = $this->argument(1)->type('numeric')->required()->value();
        $x2 = $this->argument(2)->type('numeric')->required()->value();
        $y2 = $this->argument(3)->type('numeric')->required()->value();
        $callback = $this->argument(4)->type('closure')->value();

        $line_classname = sprintf('\Intervention\Image\%s\Shapes\LineShape',
            $image->getDriver()->getDriverName());

        $line = new $line_classname($x2, $y2);

        if ($callback instanceof Closure) {
            $callback($line);
        }

        $line->applyToImage($image, $x1, $y1);

        return true;
    }
}
