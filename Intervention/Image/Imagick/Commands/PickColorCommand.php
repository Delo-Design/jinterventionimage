<?php namespace Intervention\Image\Imagick\Commands;
/**
 * @package    Intervention Image
 * @author     Oliver Vogel <info@olivervogel.com>
 * @copyright  Copyright 2015 Oliver Vogel
 * @license    MIT License; see license.txt
 * @link       http://image.intervention.io
 */

defined('_JEXEC') or die;

use Intervention\Image\Imagick\Color;

class PickColorCommand extends \Intervention\Image\Commands\AbstractCommand
{
    /**
     * Read color information from a certain position
     *
     * @param  \Intervention\Image\Image $image
     * @return boolean
     */
    public function execute($image)
    {
        $x = $this->argument(0)->type('digit')->required()->value();
        $y = $this->argument(1)->type('digit')->required()->value();
        $format = $this->argument(2)->type('string')->value('array');

        // pick color
        $color = new Color($image->getCore()->getImagePixelColor($x, $y));

        // format to output
        $this->setOutput($color->format($format));

        return true;
    }
}
