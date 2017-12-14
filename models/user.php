<?php

class User
{
    public $id;
    public $email;
    public $password;
    public $fio;
    public $image;
    public $isSuper;

    protected function __construct()
    {

    }

    private static function HashPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }


    private static function ValidateAdd($data)
    {
        if (empty($data['email'])) {
            throw new UserException('Email не указан');
        }

        if (empty($data['password'])) {
            throw new UserException('Пароль не указан');
        }
    }

    public static function Add($data)
    {
        try {
            self::ValidateAdd($data);
        } catch (Exception $ex) {
            throw $ex;
        }

        $sql = "
            INSERT INTO `users` 
            (`email`, `password`, `fio`)
            VALUES 
            (?, ?, ?);
        ";

        $stmt = DB::Instance()->Prepare($sql);

        $email = $data['email'];
        $password = self::HashPassword($data['password']);
        $fio = isset($data['fio']) ? $data['fio'] : $data['email'];

        $stmt->bind_param('sss', $email, $password, $fio);

        DB::Instance()->BeginTransaction();
        try {
            if (!$stmt->execute()) {
                switch ($stmt->errno) {
                    case 1062: throw new UserException("Email ".$data['email']." уже зарегистрирован");
                    default: throw new Exception("Неизвестная ошибка");
                }
            }

            $user = new self();

            $user->id = DB::Instance()->LastInsertedId();
            $user->email = $email;
            $user->password = $password;
            $user->fio = $fio;
            $user->isSuper = false;
            $user->image = null;

            DB::Instance()->CommitTransaction();

            return $user;
        } catch (Exception $ex) {
            DB::Instance()->RollbackTransaction();
            throw $ex;
        } finally {
            $stmt->close();
        }
    }
}

class UserException extends Exception { }