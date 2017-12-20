<?php if (!defined("INITIALIZED")) die();

class Person
{
    private $id;
    public function getId() { return $this->id; }
    protected function setId(int $id) { $this->id = $id; }

    private $type;
    public function getType() { return $this->type; }
    public function setType(PersonType $type) { $this->type = $type; }

    private $ownerId;
    public function getOwnerId() { return $this->ownerId; }
    public function setOwnerId() { $this->ownerId = $ownerId; }

    private $firstName;
    public function getFirstName() { return $this->firstName; }
    public function setFirstName(string $firstName) { $this->firstName = $firstName; }

    private $lastName;
    public function getLastName() { return $this->lastName; }
    public function setLastName(?string $lastName) { $this->lastName = $lastName; }

    private $secondName;
    public function getSecondName() { return $this->secondName; }
    public function setSecondName(?string $secondName) { $this->secondName = $secondName; }

    public function getFio()
    {
        $fio = array();
        if ($ln = $this->getLastName()) $fio[] = $ln;
        if ($fn = $this->getFirstName()) $fio[] = $fn;
        if ($sn = $this->getSecondName()) $fio[] = $sn;

        return implode(' ', $fio);
    }

    private $dateBirth;
    public function getDateBirth() { return $this->dateBirth; }
    public function setDateBirth(?DateTime $dateBirth) { $this->dateBirth = $dateBirth; }

    private $dateDeath;
    public function getDateDeath() { return $this->dateDeath; }
    public function setDateDeath(?DateTime $dateDeath) { $this->dateDeath = $dateDeath; }

    private $latitude;
    public function getLatitude() { return $this->latitude; }
    public function setLatitude(?numeric $latitude) { $this->latitude = $latitude; }

    private $longitude;
    public function getLongitude() { return $this->longitude; }
    public function setLongitude(?numeric $lobgitude) { $this->longitude = $longitude; }

    private $text;
    public function getText() { return $this->text; }
    public function setText(?string $text) { $this->text = $text; }

    private $height;
    public function getHeight() { return $this->height; }
    public function setHeight(?numeric $height) { $this->height = $height; }
    
    private $width;
    public function getWidth() { return $this->width; }
    public function setWidth(?numeric $width) { $this->width = $width; }
        
    private $scheme;
    public function getScheme() { return $this->scheme; }
    public function setScheme(?File $scheme) { $this->scheme = $scheme; }
    
    private $city;
    public function getCity() { return $this->city; }
    public function setCity(?string $city) { $this->city = $city; }

    private $address;
    public function getAddress() { return $this->address; }
    public function setAddress(?string $address) { $this->address = $address; }

    private $section;
    public function getSection() { return $this->section; }
    public function setSection(?string $section) { $this->section = $section; }

    private $graveNumber;
    public function getGraveNumber() { return $this->graveNumber; }
    public function setGraveNumber(?numeric $gravenumber) { $this->graveNumber = $gravenumber; }

    protected function __construct()
    {

    }
}

class PersonType {
    const Dead = 'dead';
    const Alive = 'alive';
    const User = 'user';

    private $type;
    public function getType()
    {
        return $this->type;
    }

    private function __construct(string $type)
    {
        $this->type = $type;
    }

    public function isDead()
    {
        return $this->getType() == self::Dead;
    }

    public function isAlive()
    {
        return $this->getType() == self::Alive;
    }

    public function isUser()
    {
        return $this->getType() == self::User;
    }

    public static function Dead()
    {
        return new self(self::Dead);
    }

    public static function Alive()
    {
        return new self(self::Alive);
    }

    public static function User()
    {
        return new self(self::User);
    }
}