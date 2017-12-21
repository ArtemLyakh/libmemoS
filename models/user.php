<?php if (!defined("INITIALIZED")) die();

class User
{
    private $id;
    public function getId() { return $this->id; }
    protected function setId(int $id) { $this->id = $id; }

    private $email;
    public function getEmail() { return $this->email; }
    public function setEmail(string $email) { $this->email = $email; }

    private $password;
    public function getPassword() { return $this->password; }
    protected function setPassword(string $password) { $this->password = $password; }

    private $isSuper = false;
    public function getIsSuper() { return $this->isSuper; }
    protected function setIsSuper(bool $isSuper) { $this->isSuper = $isSuper; }

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

    private $image;
    public function getImage() { return $this->image; }
    public function setImage(?File $image) { $this->image = $image; }

    private $dateBirth;
    public function getDateBirth() { return $this->dateBirth; }
    public function setDateBirth(?DateTime $dateBirth) { $this->dateBirth = $dateBirth; }

    protected function __construct()
    {

    }

    public static function Add(array $data)
    {
        if (empty($data['email']))
            throw new UserException('Не указан email');

        if (emtpy($data['password']))
            throw new UserException('Не указан пароль');

        $sql = "
            INSERT INTO `users` 
            (`email`, `password`, `first_name`)
            VALUES 
            (?, ?, ?);
        ";

        $stmt = DB::Instance()->Prepare($sql);

        $_email = $data['email'];
        $_password = password_hash($data['password'], PASSWORD_DEFAULT);
        $_firstName = $data['email'];

        $stmt->bind_param('sss', $_email, $_password, $_firstName);

        DB::Instance()->BeginTransaction();
        try {
            if (!$stmt->execute()) {
                switch ($stmt->errno) {
                    case 1062: throw new UserException("Email ".$email." уже зарегистрирован");
                    default: throw new Exception("Неизвестная ошибка");
                }
            }

            $user = new self();

            $user->setId(DB::Instance()->LastInsertedId());
            $user->setEmail($_email);
            $user->setPassword($_password);
            $user->setFirstName($_firstName);

            DB::Instance()->CommitTransaction();

            return $user;
        } catch (Exception $ex) {
            DB::Instance()->RollbackTransaction();
            throw $ex;
        } finally {
            $stmt->close();
        }
    }

    public static function GetByAuth(string $email, string $password)
    {
        $sql = "
            SELECT 
                u.id, 
                u.email, 
                u.password, 
                u.is_super, 
                u.first_name, 
                u.last_name, 
                u.second_name, 
                u.date_birth, 
                u.image, 
                f.path
            FROM users AS u
                LEFT JOIN files AS f ON u.image = f.id
            WHERE u.email = ?
        ";

        $stmt = DB::Instance()->Prepare($sql);

        $_email = $email;

        $stmt->bind_param('s', $_email);
        try {
            if (!$stmt->execute()) {
                switch ($stmt->errno) {
                    default: throw new Exception("Неизвестная ошибка");
                }
            }

            $_id = null;
            $_email = null;
            $_password = null;
            $_isSuper = null;
            $_firstName = null;
            $_lastName = null;
            $_secondName = null;
            $_dateBirth = null;
            $_imageId = null;
            $_imagePath = null;

            $stmt->bind_result($_id, $_email, $_password, $_isSuper, $_firstName, $_lastName, $_secondName, $_dateBirth, $_imageId, $_imagePath);

            if (!$stmt->fetch()) {
                throw new UserException('Пользователь не найден');
            }

            if (!password_verify($password, $_password)) {
                throw new UserException('Неверный пароль');
            }

            $user = new self();

            $user->setId($_id);
            $user->setEmail($_email);
            $user->setPassword($_password);
            $user->setIsSuper($_isSuper == 1);
            $user->setFirstName($_firstName);
            $user->setLastName($_lastName);
            $user->setSecondName($_secondName);
            if (!is_null($_dateBirth)) {
                $user->setDateBirth(new DateTime($_dateBirth));
            }
            if (is_int($_imageId) && !empty($_imagePath)) {
                $user->setImage(new File($_imageId, $_imagePath));
            }

            return $user;
        } finally {
            $stmt->close();
        }
    }

    public static function GetById(int $id) 
    {
        $sql = "
            SELECT 
                u.id, 
                u.email, 
                u.password, 
                u.is_super, 
                u.first_name, 
                u.last_name, 
                u.second_name, 
                u.date_birth, 
                u.image, 
                f.path
            FROM users AS u
                LEFT JOIN files AS f ON u.image = f.id
            WHERE u.id = ?
        ";

        $stmt = DB::Instance()->Prepare($sql);

        $_id = $id;

        $stmt->bind_param('i', $_id);
        try {
            if (!$stmt->execute()) {
                switch ($stmt->errno) {
                    default: throw new Exception("Неизвестная ошибка");
                }
            }

            $_id = null;
            $_email = null;
            $_password = null;
            $_isSuper = null;
            $_firstName = null;
            $_lastName = null;
            $_secondName = null;
            $_dateBirth = null;
            $_imageId = null;
            $_imagePath = null;

            $stmt->bind_result($_id, $_email, $_password, $_isSuper, $_firstName, $_lastName, $_secondName, $_dateBirth, $_imageId, $_imagePath);

            if (!$stmt->fetch()) {
                throw new UserException('Пользователь не найден');
            }

            $user = new self();

            $user->setId($_id);
            $user->setEmail($_email);
            $user->setPassword($_password);
            $user->setIsSuper($_isSuper == 1);
            $user->setFirstName($_firstName);
            $user->setLastName($_lastName);
            $user->setSecondName($_secondName);
            if (!is_null($_dateBirth)) {
                $user->setDateBirth(new DateTime($_dateBirth));
            }
            if (is_int($_imageId) && !empty($_imagePath)) {
                $user->setImage(new File($_imageId, $_imagePath));
            }

            return $user;
        } finally {
            $stmt->close();
        }
    }

    public function Save()
    {
        $sql = "
            UPDATE `users` 
            SET
                `updated` = FROM_UNIXTIME(?),
                `first_name` = ?,
                `last_name` = ?,
                `second_name` = ?,
                `image` = ?,
                `date_birth` = FROM_UNIXTIME(?)
            WHERE `id` = ?
        ";

        $stmt = DB::Instance()->Prepare($sql);

        $_id = $this->getId();
        $_timestamp = time();
        $_firstName = $this->getFirstName();
        $_lastName = $this->getLastName();
        $_secondName = $this->getSecondName();
        $_image = is_null($this->getImage()) 
            ? null 
            : $this->getImage()->getId();
        $_dateBirth = is_null($this->getDateBirth()) 
            ? null 
            : $this->getDateBirth()->getTimestamp();

        $stmt->bind_param(
            'isssiii', 
            $_timestamp,
            $_firstName,
            $_lastName,
            $_secondName,
            $_image,
            $_dateBirth,
            $_id
        );

        DB::Instance()->BeginTransaction();
        try {
            if (!$stmt->execute()) {
                switch ($stmt->errno) {
                    default: 
                    var_dump($stmt->errno, $stmt->error, $_image, $this->getImage());die();
                    throw new Exception("Неизвестная ошибка");
                }
            }

            DB::Instance()->CommitTransaction();

            return $this;
        } catch (Exception $ex) {
            DB::Instance()->RollbackTransaction();
            throw $ex;
        } finally {
            $stmt->close();
        }
    }

}

class UserException extends Exception { }