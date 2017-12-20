<?php if (!defined("INITIALIZED")) die();

class ErrorView extends BaseView
{
    private $error;

    public function __construct(string $error)
    {
        $this->error = $error;
    }

    public function Get() 
    {
        return json_encode(array(
            'error' => $this->error
        ));
    }
}