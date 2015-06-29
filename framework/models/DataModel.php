<?php namespace framework\models;

use framework\Connector;

abstract class DataModel extends \framework\models\SmartList
{

    const KEY_PREFIX = 'DataModel_';

    protected $model = null;

    public static function cache($key = null)
    {
        
        $reference = get_called_class();
        $model = new $reference;

        if (!$model->model || !isset ($model->model['model_id']) || !isset ($model->model['source'])) {
            return false;
        }

        $db = Connector::take('database');
        $m = Connector::take('memcached');
        $result = $db->query(
            self::buildQuery($model->model, $key)
        );

        if (!$db->rows($result)) {
            return false;
        }

        $list = [];
        while ($data = $db->fetch($result)) {
            $list[$data[$model->model['model_id']]] = $data;
            $m->set(self::getRealKey($data[$model->model['model_id']]), $data);
        }

        $m->set(self::getRealKey('__ALL'), $list);

        return true;
    }

    protected static function buildQuery($model, $key = null) {

        $query = [
            'SELECT * FROM',
            $model['source']
        ];

        if (isset ($model['join']) && is_array ($model['join'])) {
            foreach ($model['join'] as $table) {
                $query = array_merge($query, [
                    'LEFT JOIN',
                        $table,
                    'ON',
                        $table.'.'.$model['model_id'],
                    '=',
                        $model['source'].'.'.$model['model_id']
                ]);
            }
        }

        if ($key) {
            $query = array_merge($query, [
                'WHERE',
                    $model['source'].'.'.$model['model_id'],
                '=',
                    '"'.$key.'"'
            ]);
        }


        return join(' ', $query);
    }

    public static function load($key = null) {

        $m = Connector::take('memcached');

        if (is_array($key)) {

            $list = [];
            foreach ($key as $model_id) {
                    $list[$model_id] = $m->get(self::getRealKey($model_id));
            }
            return $list;
        }

        $key = self::getRealKey($key?:'__ALL');

        return Connector::touch('memcached', function($m) use ($key) {
            return $m->get($key);
        });

    }

    public static function del($key) {

        $key = self::getRealKey($key);

        return Connector::touch('memcached', function($m) use ($key) {
            return $m->delete($key);
        });
    }

    protected static function getRealKey($key = '') {
        return self::KEY_PREFIX . get_called_class() .'/'. $key;
    }


}
