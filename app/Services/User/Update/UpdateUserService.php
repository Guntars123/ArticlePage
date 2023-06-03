<?php declare(strict_types=1);


use App\Exceptions\ResourceNotFoundException;
use App\Repositories\User\UserRepository;

class UpdateUserService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;

    }

    public function execute(UpdateUserRequest $request): UpdateUserResponse
    {
        $user = $this->userRepository->getById($request->getId());

        if ($user == null) {
            throw new ResourceNotFoundException("User by id {$request->getId()} not found");
        }

        $user->update(
            [
                'user_name' => $request->getUserName(),
                'password' => $request->getPassword()
            ]
        );

        $this->userRepository->save($user);

        return new UpdateUserResponse($user);
    }
}
