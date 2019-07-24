<?php namespace Intervention\Image\Imagick\Commands;
/**
 * @package    Intervention Image
 * @author     Oliver Vogel <info@olivervogel.com>
 * @copyright  Copyright 2015 Oliver Vogel
 * @license    MIT License; see license.txt
 * @link       http://image.intervention.io
 */

defined('_JEXEC') or die;

use Intervention\Image\Size;

class GetSizeCommand extends \Intervention\Image\Commands\AbstractCommand
{
    /**
     * Reads size of given image instance in pixels
     *
     * @param  \Intervention\Image\Image $image
     * @return boolean
     */
    public function execute($image)
    {
        /** @var \Imagick $core */
        $core = $image->getCore();

        $this->setOutput(new Size(
            $core->getImageWidth(),
            $core->getImageHeight()
        ));

        return true;
    }
}
