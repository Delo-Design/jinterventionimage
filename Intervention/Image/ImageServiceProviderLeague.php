<?php namespace Intervention\Image;
/**
 * @package    Intervention Image
 * @author     Oliver Vogel <info@olivervogel.com>
 * @copyright  Copyright 2015 Oliver Vogel
 * @license    MIT License; see license.txt
 * @link       http://image.intervention.io
 */

defined('_JEXEC') or die;

use League\Container\ServiceProvider\AbstractServiceProvider;

class ImageServiceProviderLeague extends AbstractServiceProvider
{
    /**
     * @var array $config
     */
    protected $config;

    /**
     * @var array $provides
     */
    protected $provides = [
        'Intervention\Image\ImageManager'
    ];

    /**
     * Constructor.
     *
     * @param array $config
     */
    public function __construct($config = [])
    {
        $this->config = $config;
    }

    /**
     * Register the server provider.
     *
     * @return void
     */
    public function register()
    {
        $this->getContainer()->share('Intervention\Image\ImageManager', function () {
            return new ImageManager($this->config);
        });
    }
}
