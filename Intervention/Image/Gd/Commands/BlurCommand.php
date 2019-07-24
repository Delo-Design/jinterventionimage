<?php namespace Intervention\Image\Gd\Commands;
/**
 * @package    Intervention Image
 * @author     Oliver Vogel <info@olivervogel.com>
 * @copyright  Copyright 2015 Oliver Vogel
 * @license    MIT License; see license.txt
 * @link       http://image.intervention.io
 */

defined('_JEXEC') or die;

class BlurCommand extends \Intervention\Image\Commands\AbstractCommand
{
    /**
     * Applies blur effect on image
     *
     * @param  \Intervention\Image\Image $image
     * @return boolean
     */
    public function execute($image)
    {
        $amount = $this->argument(0)->between(0, 100)->value(1);

        for ($i=0; $i < intval($amount); $i++) {
            imagefilter($image->getCore(), IMG_FILTER_GAUSSIAN_BLUR);
        }

        return true;
    }
}
