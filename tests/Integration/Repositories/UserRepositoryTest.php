<?php

namespace App\Tests\Integration\Repositories;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

final class UserRepositoryTest extends KernelTestCase
{
    public function testUpgradePassword(): void
    {
        $entityManager = self::getContainer()
            ->get(EntityManagerInterface::class);

        $userRepository = self::getContainer()
            ->get(UserRepository::class);

        $user = new User('test@example.com');
        $user->setPassword('old-hashed-password');

        $entityManager->persist($user);
        $entityManager->flush();

        $userId = $user->getId();

        $userRepository->upgradePassword(
            $user,
            'new-hashed-password'
        );

        $entityManager->clear();

        $updatedUser = $userRepository->find($userId);

        self::assertNotNull($updatedUser);
        self::assertSame(
            'new-hashed-password',
            $updatedUser->getPassword()
        );
    }

    public function testUpgradePasswordRejectsUnsupportedUser(): void
    {
        $entityManager = self::getContainer()
            ->get(EntityManagerInterface::class);

        $userRepository = self::getContainer()
            ->get(UserRepository::class);

        $user = new class implements PasswordAuthenticatedUserInterface {
            public function getPassword(): ?string
            {
                return 'some-hashed-password';
            }
        };

        $this->expectException(UnsupportedUserException::class);

        $userRepository->upgradePassword(
            $user,
            'new-hashed-password'
        );
    }
}
