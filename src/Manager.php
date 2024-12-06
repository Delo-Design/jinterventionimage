<?php
namespace Joomla\Libraries\JInterventionimage;

/**
 * @package    jinterventionimage
 * @author     Dmitry Tsymbal <cymbal@delo-design.ru>
 * @copyright  Copyright © 2019 Delo Design. All rights reserved.
 * @license    GNU General Public License version 3 or later; see license.txt
 * @link       https://delo-design.ru
 */


defined('_JEXEC') or die;

use Intervention\Image\ImageManagerStatic as Image;
use Joomla\CMS\Filesystem\Folder;
use Joomla\CMS\Filesystem\Path;

class Manager
{

    /**
     * @param   array  $options
     *
     * @return \Intervention\Image\ImageManager
     *
     * @since version
     */
    public static function getInstance($options = ['driver' => 'gd'])
    {
        require_once dirname(__FILE__, 2) . '/vendor/autoload.php';

        return Image::configure($options);
    }


    /**
     * @param   string  $source
     * @param   int     $max_width
     * @param   int     $max_height
     * @param   string  $algorithm   values: fit|bestfit|resize
     * @param   string  $thumb_path  example: cache/images
     * @param   string  $how_save    values: folder|file
     *
     * @return mixed|string
     *
     * @since version
     */
    public static function generateThumb($source, $max_width, $max_height, $algorithm = 'resize', $thumb_path = null, $how_save = 'file')
    {
        $source     = str_replace(JPATH_ROOT . DIRECTORY_SEPARATOR, '', $source);
        $paths      = explode(DIRECTORY_SEPARATOR, $source);
        $file       = array_pop($paths);
        $file_split = explode('.', $file);
        $file_ext   = mb_strtolower(array_pop($file_split));
        $extAccept  = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (!in_array($file_ext, $extAccept)) {
            return $file;
        }

        if ($how_save === 'file') {
            $file = implode('.', $file_split) . '_' . $max_width . '_' . $max_height . '.' . $file_ext;
        }

        if ($thumb_path === null) {
            $pathThumb     = implode(DIRECTORY_SEPARATOR, array_merge($paths, ['_thumb']));
            $pathFileThumb = implode(DIRECTORY_SEPARATOR, array_merge($paths, ['_thumb'])) . DIRECTORY_SEPARATOR . $file;
        } else {
            $pathThumb     = Path::clean($thumb_path . DIRECTORY_SEPARATOR . '_thumb');
            $pathFileThumb = Path::clean($thumb_path . DIRECTORY_SEPARATOR . '_thumb' . DIRECTORY_SEPARATOR . $file);
        }

        if ($how_save === 'folder') {
            $pathThumb     .= DIRECTORY_SEPARATOR . $max_width . 'x' . $max_height;
            $pathFileThumb = Path::clean($pathThumb . DIRECTORY_SEPARATOR . $file);
        }

        $fullPathThumb = Path::clean(JPATH_ROOT . DIRECTORY_SEPARATOR . $pathThumb . DIRECTORY_SEPARATOR . $file);

        //если есть превью, то отдаем ссылку на файл
        if (file_exists($fullPathThumb)) {
            return $pathFileThumb;
        }


        //если нет, генерируем превью

        //проверяем создан ли каталог для превью
        $pathThumbSplit   = explode(DIRECTORY_SEPARATOR, $pathThumb);
        $pathThumbCurrent = '';
        foreach ($pathThumbSplit as $pathCurrentCheck) {
            $pathThumbCurrent .= DIRECTORY_SEPARATOR . $pathCurrentCheck;
            $pathThumbCheck   = Path::clean(JPATH_ROOT . DIRECTORY_SEPARATOR . $pathThumbCurrent);
            if (!file_exists($pathThumbCheck)) {
                //создаем каталог
                Folder::create($pathThumbCheck);
            }
        }


        if (copy(JPATH_ROOT . DIRECTORY_SEPARATOR . $source, $fullPathThumb)) {
            if ($algorithm === 'fit') {
                self::fit($fullPathThumb, $max_width, $max_height);
            }

            if ($algorithm === 'bestfit') {
                self::bestFit($fullPathThumb, $max_width, $max_height);
            }

            if ($algorithm === 'resize') {
                self::resize($fullPathThumb, $max_width, $max_height);
            }
        }


        return $pathFileThumb;
    }


    /**
     * @param   string  $file
     * @param   int     $width_fit
     * @param   int     $height_fit
     *
     *
     * @since version
     */
    public static function resize($file, $width_fit, $height_fit)
    {
        $max_width  = (int)$width_fit;
        $max_height = (int)$height_fit;

        $manager = self::getInstance(['driver' => self::getNameDriver()]);
        $manager
            ->make($file)
            ->resize($max_width, $max_height, function ($constraint) {
                $constraint->aspectRatio();
            })
            ->resizeCanvas($max_width, $max_height)
            ->save($file);
    }


    /**
     * @param   string  $file
     * @param   int     $width_fit
     * @param   int     $height_fit
     *
     *
     * @since version
     */
    public static function bestFit($file, $width_fit, $height_fit)
    {
        list($width, $height, $type, $attr) = getimagesize($file);
        $new_width  = $width;
        $new_height = $height;
        $max_width  = (int)$width_fit;
        $max_height = (int)$height_fit;

        $ratio = $width / $height;

        if ($width > $max_width) {
            $new_width  = $max_width;
            $new_height = round($new_width / $ratio);
        }

        if ($new_height > $max_height) {
            $new_height = $max_height;
            $new_width  = round($new_height * $ratio);
        }


        $manager = self::getInstance(['driver' => self::getNameDriver()]);
        $manager
            ->make($file)
            ->resize($new_width, $new_height, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })
            ->save($file);
    }


    /**
     * @param   string  $file
     * @param   int     $width_fit
     * @param   int     $height_fit
     *
     *
     * @since version
     */
    public static function fit($file, $width_fit, $height_fit)
    {
        $max_width  = (int)$width_fit;
        $max_height = (int)$height_fit;

        $manager = self::getInstance(['driver' => self::getNameDriver()]);
        $manager
            ->make($file)
            ->fit($max_width, $max_height, function ($constraint) {
                $constraint->aspectRatio();
            })
            ->save($file);
    }


    /**
     *
     * @return string
     *
     * @since version
     */
    public static function getNameDriver()
    {
        if (extension_loaded('imagick')) {
            return 'imagick';
        }

        return 'gd';
    }


}
