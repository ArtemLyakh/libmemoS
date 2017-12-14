<?php if (!defined("INITIALIZED")) die();

class RegisterView extends BaseView
{
    public function Get() 
    {
        $data = $this->data;
        return json_encode(array(
            'id' => $data['id'],
            'email' => $data['email'],
            'fio' => $data['fio'],
            'is_admin' => $data['is_admin']
        ));
    }
}