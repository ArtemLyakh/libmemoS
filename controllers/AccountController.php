<?php if (!defined("INITIALIZED")) die();

class AccountController extends BaseController
{
    public static function GetInfo()
    {
        static::RequestAuth();

        $user = App::Instance()->User();

        return new AccountView($user);
    }

    public static function SaveInfo()
    {
        static::RequestAuth();

        $user = App::Instance()->User();

        if ($firstName = trim($_POST["first_name"])) {		
            $user->setFirstName($firstName);
        }

        if ($lastName = trim($_POST["last_name"])) {
            $user->setLastName($lastName);
        }

        if ($secondName = trim($_POST["second_name"])) {
            $user->setSecondName($secondName);
        }	
        
        if (isset($_POST["date_birth"])) {
            $date = new DateTime($_POST["date_birth"]);
            if (!$date) 
                throw new AppException(400, 'Неверный формат даты рождения');

            $user->setDateBirth($date);	
        }



        if (isset($_FILES["photo"])) {
            $photos = null;
            try {
                $photos = FS::Instance()->SaveUploadedFiles("photo", true);
            } catch (FSException $ex) {
                throw new AppException(400, $ex->getMessage());
            } catch (Exception $ex) {
                throw new AppException(500, 'Серверная ошибка');
            }

            $user->setImage($photos[0]);
        }

        try {
            $user->Save();
        } catch (UserException $ex) {
            throw new AppException(400, $ex->getMessage());
        }
        
        return new AccountView($user);
    }
}