<?php namespace FiveSay;

class ModelNotFoundException extends ModelException
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

        $this->message = "No query results for model [{$model}].";

        return $this;
    }


}
