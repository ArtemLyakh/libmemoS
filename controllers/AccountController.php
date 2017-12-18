<?php if (!defined("INITIALIZED")) die();

class AccountController extends BaseController
{
    public static function GetInfo()
    {
        static::RequestAuth();

        $user = App::Instance()->User();

        return new AccountView(
            $user->firstName,
            $user->lastName,
            $user->secondName,
            $user->dateBirth,
            null
        );
    }

    public static function SaveInfo()
    {
        static::RequestAuth();

        $user = App::Instance()->User();

        if ($firstName = trim($_POST["first_name"])) {		
            $user->firstName = $firstName;
        }

        if ($lastName = trim($_POST["last_name"])) {
            $user->lastName = $lastName;
        }

        if ($secondName = trim($_POST["second_name"])) {
            $user->secondName = $secondName;
        }	
        
        if (isset($_POST["date_birth"])) {
            $date = new DateTime($_POST["date_birth"]);
            if (!$date) 
                throw new AppException(400, 'Неверный формат даты рождения');

            $user->dateBirth = $date;	
        }

        if (isset($_FILES["photo"]) && !$_FILES["photo"]["error"]) {
            // $uploadfile = Config::UPLOAD_DIR.md5(uniqid(rand(), true)).'_'.$_FILES["photo"]["name"];
        
            // if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadfile)) {
            //     $arFields["PERSONAL_PHOTO"] = CFile::MakeFileArray($uploadfile);
            //     $arFields["PERSONAL_PHOTO"]['del'] = "N";           
            //     $arFields["PERSONAL_PHOTO"]["MODULE_ID"] = "main";
        
            //     $fileArr = CFile::MakeFileArray($uploadfile);
            //     $id = CFile::SaveFile($fileArr, "photos");
        
            //     $person->setPhotos([$id]);
            // }
        }

        try {
            $user->Save();
        } catch (UserException $ex) {
            throw new AppException(400, $ex->getMessage());
        }
        
        return new AccountView(
            $user->firstName,
            $user->lastName,
            $user->secondName,
            $user->dateBirth,
            null
        );
    }
}