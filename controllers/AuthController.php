<? if (!defined("INITIALIZED")) die();

class AuthController 
{
    public static function Register()
    {
        try {
            self::Validate();
        } catch (Exception $ex) {
            throw $ex;
        }

        $user = self::AddUser();
        return self::Response($user);
    }

    private static function Validate()
    {
        if (empty($_REQUEST['email'])) 
            throw new AppException(400, 'Не указан email');
        if (!filter_var($_REQUEST['email'], FILTER_VALIDATE_EMAIL)) 
            throw new AppException(400, 'Неверный формат email');
        if (empty($_REQUEST['password']))
            throw new AppException(400, 'Не задан пароль');
        if (strlen($_REQUEST['password']) < 6)
            throw new AppException(400, 'Пароль должен быть больше 6 символов');
        if (empty($_REQUEST['confirm']))
            throw new AppException(400, 'Не задано подтверждение пароля');
        if ($_REQUEST['password'] != $_REQUEST['confirm'])
            throw new AppException(400, 'Пароли не совпадают');
    }

    private static function AddUser()
    {
        $data = array(
            'email' => $_REQUEST['email'],
            'password' => $_REQUEST['password']
        );

        $user = null;
        try {
            $user = User::Add($data);
        } catch (UserException $ex) {
            throw new AppException(400, $ex->getMessage());
        } catch (Exception $ex) {
            throw new AppException(500, 'Неизвестная ошибка');
        }

        return $user;
    }

    private static function Response(User $user)
    {
        $data = array(
            'id' => $user->id,
            'email' => $user->email,
            'fio' => $user->fio,
            'is_admin' => false
        );

        return new RegisterView($data);
    }
}