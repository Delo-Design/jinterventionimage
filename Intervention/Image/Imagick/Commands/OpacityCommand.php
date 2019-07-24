<?php namespace Intervention\Image\Imagick\Commands;
/**
 * @package    Intervention Image
 * @author     Oliver Vogel <info@olivervogel.com>
 * @copyright  Copyright 2015 Oliver Vogel
 * @license    MIT License; see license.txt
 * @link       http://image.intervention.io
 */

defined('_JEXEC') or die;

class OpacityCommand extends \Intervention\Image\Commands\AbstractCommand
{
    /**
     * Defines opacity of an image
     *
     * @param  \Intervention\Image\Image $image
     * @return boolean
     */
    public function execute($image)
    {
        $transparency = $this->argument(0)->between(0, 100)->required()->value();
        
        $transparency = $transparency > 0 ? (100 / $transparency) : 1000;

        return $image->getCore()->evaluateImage(\Imagick::EVALUATE_DIVIDE, $transparency, \Imagick::CHANNEL_ALPHA);
    }
}
