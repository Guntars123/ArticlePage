<?php declare(strict_types=1);

namespace App\Services\User\Create;

use App\Models\User;
use App\Repositories\User\UserRepository;


class CreateUserService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute(CreateUserRequest $request): CreateUserResponse
    {
        $user = new User(
            $request->getUserName(),
            $request->getEmail(),
            $request->getPassword()
        );

        $this->userRepository->save($user);

        return new CreateUserResponse($user);
    }
}
