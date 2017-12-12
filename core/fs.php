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
        App::Instance()->DB()->query(
            "DROP TABLE IF EXISTS `files`"
        );
        App::Instance()->DB()->query(
            "CREATE TABLE `files` ( `id` INT(10) NOT NULL AUTO_INCREMENT , `path` VARCHAR(250) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;"
        );
    }

}