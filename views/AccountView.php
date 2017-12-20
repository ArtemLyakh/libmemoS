<?php if (!defined("INITIALIZED")) die();

class AccountView extends BaseView
{
    private $user;

    public function __construct(User $user) 
    {
        $this->user = $user;
    }

    public function Get() 
    {
        return json_encode(array(
            'first_name' => $this->user->getFirstName(),
            'last_name' => $this->user->getLastName(),
	        'second_name' => $this->user->getSecondName(),
            'date_birth' => is_null($this->user->getDateBirth())
                ? null
                : $this->user->getDateBirth()->format('d.m.Y'),
            'photo_url' => is_null($this->user->getImage())
                ? null
                : Util::GetFullPath($this->user->getImage()->getPath())
        ));
    }
}