<?php namespace Intervention\Image\Gd\Commands;
/**
 * @package    Intervention Image
 * @author     Oliver Vogel <info@olivervogel.com>
 * @copyright  Copyright 2015 Oliver Vogel
 * @license    MIT License; see license.txt
 * @link       http://image.intervention.io
 */

defined('_JEXEC') or die;

class ResetCommand extends \Intervention\Image\Commands\AbstractCommand
{
    /**
     * Resets given image to its backup state
     *
     * @param  \Intervention\Image\Image $image
     * @return boolean
     */
    public function execute($image)
    {
        $backupName = $this->argument(0)->value();

        if (is_resource($backup = $image->getBackup($backupName))) {

            // destroy current resource
            imagedestroy($image->getCore());

            // clone backup
            $backup = $image->getDriver()->cloneCore($backup);

            // reset to new resource
            $image->setCore($backup);

            return true;
        }

        throw new \Intervention\Image\Exception\RuntimeException(
            "Backup not available. Call backup() before reset()."
        );
    }
}
