<?php namespace Intervention\Image\Gd\Commands;
/**
 * @package    Intervention Image
 * @author     Oliver Vogel <info@olivervogel.com>
 * @copyright  Copyright 2015 Oliver Vogel
 * @license    MIT License; see license.txt
 * @link       http://image.intervention.io
 */

defined('_JEXEC') or die;

class FlipCommand extends ResizeCommand
{
    /**
     * Mirrors an image
     *
     * @param  \Intervention\Image\Image $image
     * @return boolean
     */
    public function execute($image)
    {
        $mode = $this->argument(0)->value('h');

        $size = $image->getSize();
        $dst = clone $size;

        switch (strtolower($mode)) {
            case 2:
            case 'v':
            case 'vert':
            case 'vertical':
                $size->pivot->y = $size->height - 1;
                $size->height = $size->height * (-1);
                break;

            default:
                $size->pivot->x = $size->width - 1;
                $size->width = $size->width * (-1);
                break;
        }

        return $this->modify($image, 0, 0, $size->pivot->x, $size->pivot->y, $dst->width, $dst->height, $size->width, $size->height);
    }
}
