<?php if (!defined("INITIALIZED")) die();

class AuthView extends BaseView
{
    private $user;
    private $token;

    public function __construct(User $user, Token $token)
    {
        $this->user = $user;
        $this->token = $token;
    }

    public function Get() 
    {
        return json_encode(array(
            'id' => $this->user->getId(),
            'email' => $this->user->getEmail(),
            'fio' => $this->user->getFio(),
            'is_admin' => $this->user->getIsSuper(),
            'token' => $this->token->getToken()
        ));
    }
}