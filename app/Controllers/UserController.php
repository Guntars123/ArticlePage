<?php declare(strict_types=1);

namespace App\Controllers;

use App\Core\NotFoundView;
use App\Core\TwigView;
use App\Core\Validator;
use App\Core\View;
use App\Exceptions\ResourceNotFoundException;
use App\Services\User\Create\CreateUserRequest;
use App\Services\User\Create\CreateUserService;
use App\Services\User\IndexUserService;
use App\Services\User\LoginUserService;
use App\Services\User\Show\ShowUserRequest;
use App\Services\User\Show\ShowUserService;

class UserController
{
    private IndexUserService $indexUserService;
    private ShowUserService $showUserService;
    private CreateUserService $createUserService;
    private LoginUserService $loginUserService;
    private Validator $validator;

    public function __construct
    (
        IndexUserService  $indexUserService,
        ShowUserService   $showUserService,
        CreateUserService $createUserService,
        LoginUserService  $loginUserService,
        Validator         $validator
    )
    {
        $this->indexUserService = $indexUserService;
        $this->showUserService = $showUserService;
        $this->createUserService = $createUserService;
        $this->loginUserService = $loginUserService;
        $this->validator = $validator;
    }

    public function index(): View
    {
        $users = $this->indexUserService->execute();

        return new TwigView("users/index", ['users' => $users]);
    }

    public function show(array $vars): View
    {
        try {
            $userId = (int)$vars["id"];

            $response = $this->showUserService->execute(new ShowUserRequest($userId));

            return new TwigView("users/show", [
                'user' => $response->getUser(),
                'articles' => $response->getArticles()
            ]);

        } catch (ResourceNotFoundException $exception) {
            return new NotFoundView("notFound");
        }
    }

    public function register(): View
    {
        return new TwigView('users/register', []);
    }

    public function store(): void
    {
        try {

            $this->validator->validateRegisterForm($_POST);

            $userName = filter_var($_POST['user_name'], FILTER_SANITIZE_STRING);
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'];
            $passwordRepeat = $_POST['password_repeat'];

            if ($password == $passwordRepeat) {
                $user = $this->createUserService->execute(
                    new CreateUserRequest(
                        $userName,
                        $email,
                        password_hash($password, PASSWORD_BCRYPT)
                    ));

                $_SESSION['user'] = [
                    'email' => $email,
                    'id' => $user->getUser()->getId()
                ];

                header('Location: /');
                exit();
            }
            header('Location: /register');
            exit();
        } catch (\Exception $e) {
            echo $e->getMessage();
            header('Location: /login');
            exit();
        }
    }

    public function loginShow(): View
    {
        return new TwigView("users/login", []);
    }

    public function login(): void
    {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $user = $this->loginUserService->execute($email, $password);


        if ($user == null) {
            header('Location: /login');
            exit();
        }


        $_SESSION['user'] = [
            'email' => $email,
            'id' => $user->getId()
        ];

        header('Location: /');
        exit();
    }

    public function logout(): void
    {
        unset($_SESSION['user']);
        header('Location: /');
        exit();
    }
}


