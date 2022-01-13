<?php
namespace App\Faker\Provider;

use Faker\Provider\Base as BaseProvider;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class PasswordProvider extends BaseProvider
{

    public function __construct(private UserPasswordHasherInterface $encoder)
    {
    }

    public function encodePassword($user, string $plainPassword): string
    {
        return $this->encoder->hashPassword($user, $plainPassword);
    }
}
