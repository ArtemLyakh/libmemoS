<?php if (!defined("INITIALIZED")) die();

class FS
{
    private $conf;

    private function __construct()
    {
        $this->conf = (require('conf.php'))['fs'];
    }

    private static $_instance = null;
    public static function Instance() 
    {
        if(is_null(self::$_instance))
        {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    private function GetFilesArray(string $key) 
    {
        $files = array();

        if (!is_array($_FILES[$key]['error'])) {
            if ($_FILES[$key]["error"] !== UPLOAD_ERR_OK)
                throw new FSException("Ошибка при загрузке файла");

            $files[] = array(
                'name' => $_FILES[$key]['name'],
                'type' => $_FILES[$key]['type'],
                'tmp_name' => $_FILES[$key]['tmp_name'],
                'error' => $_FILES[$key]['error'],
                'size' => $_FILES[$key]['size']
            );
        } else {
            foreach ($_FILES[$key]['error'] as $i => $error) {
                if ($error !== UPLOAD_ERR_OK)
                    throw new FSException("Ошибка при загрузке файла");

                $files[] = array(
                    'name' => $_FILES[$key]['name'][$i],
                    'type' => $_FILES[$key]['type'][$i],
                    'tmp_name' => $_FILES[$key]['tmp_name'][$i],
                    'error' => $_FILES[$key]['error'][$i],
                    'size' => $_FILES[$key]['size'][$i]
                );
            }
        }

        return $files;
    }

    public function SaveUploadedFiles(string $key, bool $isImage = false)
    {
        $files = null;
        try {
            $files = $this->GetFilesArray($key);
        } catch (Exception $ex) {
            throw $ex;
        }

        $result = array();
        foreach ($files as $file) {
            if ($isImage) {
                if (getimagesize($file['tmp_name']) === false) {
                    throw new FSException('Файл не является изображением');
                }
            }

            $name = md5(uniqid(rand(), true));
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            if (!$ext) {
                throw new FSException('Неизвестный тип файла');
            }

            $path = $this->conf['upload'].$name.'.'.$ext;

            if (!move_uploaded_file($file['tmp_name'], $_SERVER['DOCUMENT_ROOT'].$path)) {
                throw new Exception('Не удалось сохранить файл');
            }

            $id = null;
            try {
                $id = $this->Save($path);
            } catch (Exception $ex) {
                throw $ex;
            }
            
            $result[] = new File($id, $path);
        }

        return $result;
    }

    private function Save(string $path)
    {
        $sql = "
            INSERT INTO `files` 
            (`path`)
            VALUES 
            (?);
        ";

        $stmt = DB::Instance()->Prepare($sql);

        $_path = $path;

        $stmt->bind_param('s', $_path);

        DB::Instance()->BeginTransaction();
        try {
            if (!$stmt->execute()) {
                switch ($stmt->errno) {
                    default: throw new Exception("Неизвестная ошибка");
                }
            }

            $id = DB::Instance()->LastInsertedId();

            DB::Instance()->CommitTransaction();
            return $id;
        } catch (Exception $ex) {
            DB::Instance()->RollbackTransaction();
            throw $ex;
        } finally {
            $stmt->close();
        }
    }
}

class FSException extends Exception {}