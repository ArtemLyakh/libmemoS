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

    private $photos;
    public function getPhotos() { return $this->photos; }
    public function setPhotos(?array $photos) { $this->photos = $photos; }

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

    public static function Add(array $data)
    {
        if (isset($data['type'])) 
            if ($data['type'] instanceof PersonType) 
                $_type = $data['type']->getType();
            else 
                throw new PersonException('Неверный формат типа');
        else
            throw new PersonException('Не указан тип');

        if (isset($data['owner']))
            if (is_int($data['owner']))
                $_owner = $data['owner'];
            else
                throw new PersonException('Неверный формат владельца');
        else 
            throw new PersonException('Не указан владелец');

        if (isset($data['first_name']))
            $_firstName = $data['first_name'];
        else 
            throw new PersonException('Не указано имя');
        
        if (isset($data['last_name']))
            $_lastName = $data['last_name'];
        else 
            $_lastName = null;

        if (isset($data['second_name']))
            $_secondName = $data['second_name'];
        else 
            $_secondName = null;

        if (isset($data['last_name']))
            $_lastName = $data['last_name'];
        else 
            $_lastName = null;

        if (isset($data['date_birth']))
            if ($data['date_birth'] instanceof DateTime)
                $_dateBirth = $data['date_birth']->format('Y-m-d');
            else
                throw new PersonException('Не верный формат даты рождения');
        else
            $_dateBirth = null;

        if (isset($data['date_death']))
            if ($data['date_death'] instanceof DateTime)
                $_dateDeath = $data['date_death']->format('Y-m-d');
            else
                throw new PersonException('Не верный формат даты смерти');
        else
            $_dateDeath = null;

        if (isset($data['photos']))
            if (is_array($data['photos']))
                $_photos = array_map(function ($el) {
                    if ($el instanceof File)
                        return $el->getId();
                    else
                        throw new PersonException('Неверный формат фото');
                }, $data['photos']);
            else
                throw new PersonException('Неверный формат фото');
        else 
            $_photos = null;

        if (isset($data['latitude']))
            if (is_numeric($data['latitude']))
                $_latitude = $data['latitude'];
            else
                throw new PersonException('Неверный формат широты');
        else
            if ($_type->isDead())
                throw new PersonException('Не указана широта');
            else
                $_latitude = null;

        if (isset($data['longitude']))
            if (is_numeric($data['longitude']))
                $_longitude = $data['longitude'];
            else
                throw new PersonException('Неверный формат долготы');
        else
            if ($_type->isDead())
                throw new PersonException('Не указана долгота');
            else
                $_longitude = null;
 
        if (isset($data['text']))
            $_text = $data['text'];
        else 
            $_text = null;

        if (isset($data['height']))
            if (is_numeric($data['height']))
                $_height = $data['height'];
            else
                throw new PersonException('Неверный формат высоты');
        else 
            $_height = null;

        if (isset($data['width']))
            if (is_numeric($data['width']))
                $_width = $data['width'];
            else
                throw new PersonException('Неверный формат ширины');
        else 
            $_width = null;

        if (isset($data['scheme']))
            if ($data['scheme'] instanceof File)
                $_scheme = $data['scheme']->getId();
            else 
                throw new PersonException('Неверный формат схемы');
        else
            $_scheme = null;

        if (isset($data['city']))
            $_city = $data['city'];
        else
            $_city = null;

        if (isset($data['address']))
            $_address = $data['address'];
        else 
            $_address = null;

        if (isset($data['section']))
            $_section = $data['section'];
        else
            $_section = null;

        if (isset($data['grave_number']))
            $_graveNumber = $data['grave_number'];
        else
            $_graveNumber = null;

        $stmtPerson = DB::Instance()->Prepare(
            "
            INSERT INTO persons 
            (
                type, owner, first_name, last_name, second_name, 
                date_birth, date_death, latitude, longitude, text, 
                height, width, scheme, city, address, 
                section, grave_number
            )
            VALUES 
            (
                ?, ?, ?, ?, ?, 
                ?, ?, ?, ?, ?,
                ?, ?, ?, ?, ?,
                ?, ?
            );
            "
        );
        $stmtPhotos = DB::Instance()->Prepare(
            "
            INSERT INTO person_photos
            (person, file)
            VALUES
            (?, ?)
            "
        );

        $stmtPerson->bind_param('sisssssddsddissss',
            $_type, $_owner, $_firstName, $_lastName, $_secondName,
            $_dateBirth, $_dateDeath, $_latitude, $_longitude, $_text,
            $_height, $_width, $_scheme, $_city, $_address,
            $_section, $_graveNumber
        );

        DB::Instance()->BeginTransaction();
        try {
            if (!$stmtPerson->execute()) {
                switch ($stmtPerson->errno) {
                    default: throw new Exception($stmtPerson->error);
                }
            }

            $personId = DB::Instance()->LastInsertedId();

            if (is_array($_photos)) {
                foreach ($_photos as $photo) {
                    $stmtPhotos->bind_param('ii', $personId, $photo);
                    if (!$stmtPhotos->execute()) {
                        switch ($stmtPhotos->errno) {
                            default: throw new Exception($stmtPhotos->error);
                        }
                    }
                }
            }

            $person = new self();

            $person->setId($personId);
            $person->setType($data['type']);
            $person->setFirstName($data['first_name']);
            $person->setLastName($data['last_name']);
            $person->setSecondName($data['second_name']);
            $person->setDateBirth($data['date_birth']);
            $person->setDateDeath($data['date_death']);
            $person->setPhotos($data['photos']);
            $person->setLatitude($data['latitude']);
            $person->setLongitude($data['longitude']);
            $person->setText($data['text']);
            $person->setHeight($data['height']);
            $person->setWidth($data['width']);
            $person->setScheme($data['scheme']);
            $person->setCity($data['city']);
            $person->setAddress($data['address']);
            $person->setSection($data['section']);
            $person->setGraveNumber($data['grave_number']);

            DB::Instance()->CommitTransaction();

            return $user;
        } catch (Exception $ex) {
            DB::Instance()->RollbackTransaction();
            throw $ex;
        } finally {
            $stmtPerson->close();
            $stmtPhotos->clode();
        }
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

    public static function FromString(string $str)
    {
        switch ($str)
        {
            case self::Dead: return self::Dead();
            case self::Alive: return self::Alive();
            case self::User: return self::User();
            default: throw new Exception('Неверный тип');
        }
    }
}

class PersonException extends Exception {}