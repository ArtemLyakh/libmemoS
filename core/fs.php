<?php if (!defined("INITIALIZED")) die();

class FileSystem
{
    public function __construct()
    {

    }


    public function RegisterFile($path)
    {
        $db = App::Instance()->DB();

        $error = false;
        $db->begin_transaction();

        $sql = "INSERT INTO `files` (`path`) VALUES (?)";
        if (!$stmt = $db->prepare($sql)) $error = true;

        if (!$stmt->bind_param("s", $path)) $error = true;

        if (!$stmt->execute()) $error = true;

        if ($error) {
            $db->rollback();
            ErrorDie(500);
        } else {
            $db->commit();
        }

        $stmt->close();
    }


}