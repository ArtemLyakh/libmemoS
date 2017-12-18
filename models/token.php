<?php if (!defined("INITIALIZED")) die();

class Token
{
    public $token;
    public $userId;
    public $expires;

    protected function __construct()
    {

    }

    public static function GetNew(int $userId)
    {
        $sql = "
            INSERT INTO `tokens` 
            (`token`, `user`, `expires`)
            VALUES 
            (?, ?, ?);
        ";

        $token = md5(uniqid($userId, true));
        $expires = new DateTime('+1 year');

        $stmt = DB::Instance()->Prepare($sql);
        $stmt->bind_param('sii', $token, $userId, $expires->getTimestamp());

        DB::Instance()->BeginTransaction();
        try {
            if (!$stmt->execute()) {
                switch ($stmt->errno) {
                    default: throw new Exception("Неизвестная ошибка");
                }
            }

            $item = new self();

            $item->token = $token;
            $item->userId = $userId;
            $item->expires = $expires;

            DB::Instance()->CommitTransaction();

            return $item;
        } catch (Exception $ex) {
            DB::Instance()->RollbackTransaction();
            throw $ex;
        } finally {
            $stmt->close();
        }
    }

    public static function GetUser(string $token)
    {
        $sql = "
            SELECT `user`
            FROM `tokens`
            WHERE `token` = ? AND `expires` > ?
        ";

        $stmt = DB::Instance()->Prepare($sql);

        $stmt->bind_param('si', $token, time());

        try {
            if (!$stmt->execute()) {
                switch ($stmt->errno) {
                    default: throw new Exception("Неизвестная ошибка");
                }
            }

            $stmt->bind_result($_userId);

            if (!$stmt->fetch()) {
                throw new TokenException('Токен не найден');
            }

            return $_userId;
        } finally {
            $stmt->close();
        }
    }

}

class TokenException extends Exception {}