<?php if (!defined("INITIALIZED")) die();

class FileSystem
{
    public function __construct()
    {

    }

    public function Install()
    {
        $this->InstallFS();
        $this->InstallDB();
    }

    private function InstallFS()
    {
        $conf = (require('conf.php'))['fs'];
        
        $uploadPath = $conf['upload'];
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath);
        }

        $tmpPath = $conf['tmp'];
        if (!is_dir($tmpPath)) {
            mkdir($tmpPath);
        }

        $cachePath = $conf['cache'];
        if (!is_dir($cachePath)) {
            mkdir($cachePath);
        }
    }

    private function InstallDB()
    {
        $db = App::Instance()->DB();

        $sql = "DROP TABLE IF EXISTS `files`";
        if (!$db->query($sql)) ErrorDie(500);

        $sql = 
            "CREATE TABLE `files` ( 
                `id` INT(10) NOT NULL AUTO_INCREMENT , 
                `path` VARCHAR(250) NOT NULL , PRIMARY KEY (`id`)
            ) ENGINE = InnoDB;";
        if (!$db->query($sql)) ErrorDie(500);
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