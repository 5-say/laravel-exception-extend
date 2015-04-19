<?php namespace FiveSay;

class ValidationFacade extends \Illuminate\Support\Facades\Facade
{
    protected static function getFacadeAccessor() { return new Validation; }
}
