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
        $this->setCycleComponent($app);
        $this->setDbComponent($app);
    }

    /**
     * @param Application $app
     */
    protected function setCycleComponent($app): void
    {
        $app->setComponents([
            'cycle' => array_merge([
                'class' => Connection::class,
            ], ArrayHelper::getValue($app->getComponents(), 'cycle', [])),
        ]);
    }

    /**
     * @param Application $app
     */
    protected function setDbComponent($app): void
    {
        if (ArrayHelper::getValue($app->getComponents(), 'cycle.setDbComponent', false) === false) {
            return;
        }

        $app->setComponents([
            'db' => [
                'class' => \yii\db\Connection::class,
                'dsn' => ArrayHelper::getValue($app->getComponents(), 'cycle.dsn'),
                'username' => ArrayHelper::getValue($app->getComponents(), 'cycle.username'),
                'password' => ArrayHelper::getValue($app->getComponents(), 'cycle.password'),
            ],
        ]);
    }
}
