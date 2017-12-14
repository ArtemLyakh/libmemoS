<?php if (!defined("INITIALIZED")) die();

class ErrorView extends BaseView
{
    public function Get() 
    {
        return json_encode(array(
            'error' => $this->data['error']
        ));
    }
}