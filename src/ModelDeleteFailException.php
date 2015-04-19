<?php namespace FiveSay;

class ModelDeleteFailException extends ModelException
{

    /**
     * Set the affected Eloquent model.
     *
     * @param  string   $model
     * @return ModelNotFoundException
     */
    public function setModel($model)
    {
        $this->model = $model;

        $this->message = "Model [{$model}] delete fail.";

        return $this;
    }


}
