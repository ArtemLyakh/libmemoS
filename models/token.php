<?php if (!defined("INITIALIZED")) die();

class Token
{
    private $token;
    public function getToken() { return $this->token; }
    protected function setToken(string $token) { $this->token = $token; }

    private $userId;
    public function getUserId() { return $this->userId; }
    protected function setUserId(int $userId) { $this->userId = $userId; }

    private $expires;
    public function getExpires() { return $this->expires; }
    protected function setExpires(DateTime $expires) { $this->expires = $expires; }

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

    public static function GetUserIdByToken(string $token)
    {
        $sql = "
            SELECT `user`
            FROM `tokens`
            WHERE `token` = ? AND `expires` > ?
        ";

        $stmt = DB::Instance()->Prepare($sql);

        $_token = $token;
        $_time = time();

        $stmt->bind_param('si', $_token, $_time);

        try {
            if (!$stmt->execute()) {
                switch ($stmt->errno) {
                    default: throw new Exception("Неизвестная ошибка");
                }
            }

            $_userId = null;
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