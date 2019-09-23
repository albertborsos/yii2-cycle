<?php

namespace albertborsos\cycle;

use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\helpers\ArrayHelper;

class Bootstrap implements BootstrapInterface
{
    /**
     * Bootstrap method to be called during application bootstrap stage.
     * @param Application $app the application currently running
     */
    public function bootstrap($app)
    {
        $app->setComponents([
            'cycle' => array_merge([
                'class' => Connection::class,
            ], ArrayHelper::getValue($app->getComponents(), 'cycle', [])),
        ]);
    }
}
