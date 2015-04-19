<?php namespace FiveSay;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Model extends Eloquent
{
    /**
     * @var array
     */
    protected static $allowExceptionList = array('find', 'save', 'delete');

    /**
     * 禁止抛出异常
     * @param  dynamic  mixed
     * @return void
     */
    public static function closeException()
    {
        $argList = func_get_args();
        if (empty($argList)) {
            static::$allowExceptionList = array();
        } else {
            static::$allowExceptionList = array_diff(static::$allowExceptionList, $argList);
        }
    }

    /**
     * 允许抛出异常
     * @param  dynamic  mixed
     * @return void
     */
    public static function allowException()
    {
        $argList = func_get_args();
        if (empty($argList)) {
            static::$allowExceptionList = array('find', 'save', 'delete');
        } else {
            static::$allowExceptionList = array_merge(static::$allowExceptionList, $argList);
        }
    }

    /**
     * 引导
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        # 注册模型事件观察者
        $observer = get_called_class().'Observer';
        if (class_exists($observer)) {
            static::observe(new $observer);
        }
    }

    /**
     * 复写系统方法
     * @param  mixed  $id
     * @param  array  $columns
     * @return \Illuminate\Database\Eloquent\Model|Collection|static
     */
    public static function find($id, $columns = array('*'))
    {
        if (! in_array('find', static::$allowExceptionList)) {
            return parent::find($id, $columns);
        }
        # 异常化编程
        if ($result = parent::find($id, $columns)) {
            return $result;
        } else {
            $modelClassName = get_called_class();
            $e = $modelClassName.'NotFindException';
            if (! class_exists($e)) {
                eval('class '.$e.' extends \FiveSay\ModelNotFoundException {}');
            }
            throw with(new $e)->setModel($modelClassName);
        }
    }

    /**
     * 复写系统方法
     * @param  array  $options
     * @return bool
     */
    public function save(array $options = array())
    {
        if (! in_array('save', static::$allowExceptionList)) {
            return parent::save($options);
        }
        # 异常化编程
        if ($saved = parent::save($options)) {
            return $saved;
        } else {
            $modelClassName = get_called_class();
            $e = $modelClassName.'SaveFailException';
            if (! class_exists($e)) {
                eval('class '.$e.' extends \FiveSay\ModelSaveFailException {}');
            }
            throw with(new $e)->setModel($modelClassName);
        }
    }

    /**
     * 复写系统方法
     * @return bool|null
     */
    public function delete()
    {
        if (! in_array('delete', static::$allowExceptionList)) {
            return parent::delete();
        }
        # 异常化编程
        if ($deleted = parent::delete()) {
            return $deleted;
        } else {
            $modelClassName = get_called_class();
            $e = $modelClassName.'DeleteFailException';
            if (! class_exists($e)) {
                eval('class '.$e.' extends \FiveSay\ModelDeleteFailException {}');
            }
            throw with(new $e)->setModel($modelClassName);
        }
    }

    /**
     * 复写系统方法
     * @return void
     */
    protected function performDeleteOnModel()
    {
        # 拓展软删除事件监听
        if ($this->softDelete) {
            $this->fireModelEvent('softing');
            parent::performDeleteOnModel();
            $this->fireModelEvent('softed', false);
        } else {
            $this->fireModelEvent('forcing');
            parent::performDeleteOnModel();
            $this->fireModelEvent('forced', false);
        }
    }

    /**
     * 复写系统方法
     * @return array
     */
    public function getObservableEvents()
    {
        # 拓展软删除事件监听
        return array_merge(
            array('softing', 'softed', 'forcing', 'forced'),
            parent::getObservableEvents()
        );
    }


}