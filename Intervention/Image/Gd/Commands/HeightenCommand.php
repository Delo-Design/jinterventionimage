<?php namespace Intervention\Image\Gd\Commands;
/**
 * @package    Intervention Image
 * @author     Oliver Vogel <info@olivervogel.com>
 * @copyright  Copyright 2015 Oliver Vogel
 * @license    MIT License; see license.txt
 * @link       http://image.intervention.io
 */

defined('_JEXEC') or die;

class HeightenCommand extends ResizeCommand
{
    /**
     * Resize image proportionally to given height
     *
     * @param  \Intervention\Image\Image $image
     * @return boolean
     */
    public function execute($image)
    {
        $height = $this->argument(0)->type('digit')->required()->value();
        $additionalConstraints = $this->argument(1)->type('closure')->value();

        $this->arguments[0] = null;
        $this->arguments[1] = $height;
        $this->arguments[2] = function ($constraint) use ($additionalConstraints) {
            $constraint->aspectRatio();
            if(is_callable($additionalConstraints))
                $additionalConstraints($constraint);
        };

        return parent::execute($image);
    }
}
