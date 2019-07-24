<?php namespace Intervention\Image\Commands;
/**
 * @package    Intervention Image
 * @author     Oliver Vogel <info@olivervogel.com>
 * @copyright  Copyright 2015 Oliver Vogel
 * @license    MIT License; see license.txt
 * @link       http://image.intervention.io
 */

defined('_JEXEC') or die;

class ChecksumCommand extends AbstractCommand
{
    /**
     * Calculates checksum of given image
     *
     * @param  \Intervention\Image\Image $image
     * @return boolean
     */
    public function execute($image)
    {
        $colors = [];

        $size = $image->getSize();

        for ($x=0; $x <= ($size->width-1); $x++) {
            for ($y=0; $y <= ($size->height-1); $y++) {
                $colors[] = $image->pickColor($x, $y, 'array');
            }
        }

        $this->setOutput(md5(serialize($colors)));

        return true;
    }
}
