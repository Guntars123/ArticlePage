<?php declare(strict_types=1);


use App\Repositories\User\UserRepository;

class DeleteUserService
{
    private UserRepository $userRepository;

    public function __construct
    (
        UserRepository $userRepository
    )
    {
        $this->userRepository = $userRepository;
    }

    public function execute(int $userId): void
    {
      $this->userRepository->delete($userId);
    }
}