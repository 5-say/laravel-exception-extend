<?php namespace FiveSay;

class ModelException extends \RuntimeException
{

	/**
	 * Name of the affected Eloquent model.
	 *
	 * @var string
	 */
	protected $model;

	/**
	 * Set the affected Eloquent model.
	 *
	 * @param  string   $model
	 * @return ModelNotFoundException
	 */
	public function setModel($model)
	{
		$this->model = $model;

		$this->message = "Model [{$model}] exception.";

		return $this;
	}

	/**
	 * Get the affected Eloquent model.
	 *
	 * @return string
	 */
	public function getModel()
	{
		return $this->model;
	}

}
