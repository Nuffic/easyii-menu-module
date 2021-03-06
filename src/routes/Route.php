<?php

namespace qwestern\easyii\menu\routes;

use qwestern\easyii\menu\models\Url;
use yii\base\Component;
use yii\helpers\ArrayHelper;

class Route extends Component
{

    /**
     * @example
     * For route with no entities: ['route' => '/blog/index'],
     * Route with entities:
     *
     * 'yii\easyii\modules\article\models\Item' => [ // FQ classname
     *   'route' => '/blog/view', // urlManager route
     *   'attribute' => 'slug', // model slug attribute
     *   'routeParam' => 'slug' // route slug attribute
     *   'updatedAttribute' => 'time', // last update model param
     *  ],
     *
     * @var array
     */
    public $classes = [];

    /**
     * @return Url[]
     */
    public function getAll()
    {
        $urls = [];
        foreach ($this->classes as $class => $options) {
            if (is_integer($class)) {
                $urls[] = new Url($options);
                continue;
            }
            $models = call_user_func([$class, 'find'])->all();
            $urls = ArrayHelper::merge($urls, array_map(function ($item) use ($options) {
                return new Url(ArrayHelper::merge($options, [
                    'model' => $item
                ]));
            }, $models));
        }
        return $urls;
    }
}
