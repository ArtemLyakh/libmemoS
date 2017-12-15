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
        $stmt->bind_param('sis', $token, $userId, $expires->format('Y-m-d'));

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


}