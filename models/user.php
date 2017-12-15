<?php if (!defined("INITIALIZED")) die();

class User
{
    public $id;
    public $email;
    public $password;
    public $isSuper = false;
    public $firstName;
    public $lastName;
    public $secondName;
    public $image;
    public $dateBirth;

    protected function __construct()
    {

    }

    public static function Add(string $email, string $password)
    {
        $sql = "
            INSERT INTO `users` 
            (`email`, `password`, `first_name`)
            VALUES 
            (?, ?, ?);
        ";

        $stmt = DB::Instance()->Prepare($sql);
        $stmt->bind_param('sss', $email, password_hash($password, PASSWORD_DEFAULT), $email);

        DB::Instance()->BeginTransaction();
        try {
            if (!$stmt->execute()) {
                switch ($stmt->errno) {
                    case 1062: throw new UserException("Email ".$email." уже зарегистрирован");
                    default: throw new Exception("Неизвестная ошибка");
                }
            }

            $user = new self();

            $user->id = DB::Instance()->LastInsertedId();
            $user->email = $email;
            $user->password = $password;
            $user->fio = $email;

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
            SELECT `id`, `email`, `password`, `is_super`, `first_name`, `last_name`, `second_name`, `image`, `date_birth`
            FROM `users`
            WHERE `email`=?
        ";

        $stmt = DB::Instance()->Prepare($sql);
        $stmt->bind_param('s', $email);
        try {
            if (!$stmt->execute()) {
                switch ($stmt->errno) {
                    default: throw new Exception("Неизвестная ошибка");
                }
            }

            $stmt->bind_result($_id, $_email, $_password, $_isSuper, $_firstName, $_lastName, $_secondName, $_image, $_dateBirth);

            if (!$stmt->fetch()) {
                throw new UserException('Пользователь не найден');
            }

            if (!password_verify($password, $_password)) {
                throw new UserException('Неверный пароль');
            }

            $user = new self();

            $user->id = $_id;
            $user->email = $_email;
            $user->password = $_password;
            $user->isSuper = $_isSuper != 0;
            $user->firstName = $_firstName;
            $user->lastName = $_lastName;
            $user->secondName = $_secondName;
            $user->image = $_image;
            $user->dateBirth = empty($_dateBirth) ? null : new DateTime($_dateBirth);

            return $user;
        } finally {
            $stmt->close();
        }
    }
}

class UserException extends Exception { }