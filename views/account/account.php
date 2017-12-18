<?php if (!defined("INITIALIZED")) die();

class AccountView extends BaseView
{
    private $firstName;
    private $lastName;
    private $secondName;
    private $dateBirth;
    private $photo;

    public function __construct(
        string $firstName,
        ?string $lastName,
        ?string $secondName,
        ?DateTime $dateBirth,
        ?string $photo
    ) {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->secondName = $secondName;
        $this->dateBirth = $dateBirth;
        $this->photo = $photo;
    }

    public function Get() 
    {
        return json_encode(array(
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
	        'second_name' => $this->secondName,
            'date_birth' => is_null($this->dateBirth)
                ? null
                : $this->dateBirth->format('d.m.Y'),
            'photo_url' => $photo
        ));
    }
}