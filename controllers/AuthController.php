<?php if (!defined("INITIALIZED")) die();

class AuthController extends BaseController
{
    public static function Register()
    {
        if (empty($_REQUEST['email'])) 
            throw new AppException(400, 'Не указан email');
        $email = $_REQUEST['email'];

        if (empty($_REQUEST['password'])) 
            throw new AppException(400, 'Не указан пароль');
        $password = $_REQUEST['password'];

        if (empty($_REQUEST['confirm'])) 
            throw new AppException(400, 'Не указано подтверждение пароля');
        $confirm = $_REQUEST['confirm'];


        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
            throw new AppException(400, 'Неверный формат email');
        if (strlen($password) < 6)
            throw new AppException(400, 'Пароль должен быть не менее 6 символов');
        if ($password != $confirm)
            throw new AppException(400, 'Пароли не совпадают');

        $user = null;
        try {
            $user = User::Add($email, $password);
        } catch (UserException $ex) {
            throw new AppException(400, $ex->getMessage());
        } catch (Exception $ex) {
            throw new AppException(500, 'Неизвестная ошибка');
        }

        $token = null;
        try {
            $token = Token::GetNew($user->id);
        } catch (Exception $ex) {
            throw new AppException(500, 'Не удалось авторизовать');
        }

        return new AuthView(array(
            'id' => $user->id,
            'email' => $user->email,
            'fio' => $user->fio,
            'is_admin' => false,
            'token' => $token->token
        ));
    }

    public static function Login()
    {
        if (empty($_REQUEST['email']))
            throw new AppException(400, 'Не указан email');
        $email = $_REQUEST['email'];

        if (empty($_REQUEST['password'])) 
            throw new AppException(400, 'Не указан пароль');
        $password = $_REQUEST['password'];

        $user = null;
        try {
            $user = User::GetByAuth($email, $password);
        } catch (UserException $ex) {
            throw new AppException(400, 'Неверный логин или пароль');
        } catch (Exception $ex) {
            throw new AppException(500, 'Неизвестная ошибка');
        }

        $token = null;
        try {
            $token = Token::GetNew($user->id);
        } catch (Exception $ex) {
            throw new AppException(500, 'Не удалось авторизовать');
        }

        return new AuthView(array(
            'id' => $user->id,
            'email' => $user->email,
            'fio' => $user->fio,
            'is_admin' => false,
            'token' => $token->token
        ));
    }
}