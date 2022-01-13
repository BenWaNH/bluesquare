<?php

namespace App\Identifier;

use App\Entity\User;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

final class UserIdentifierNormalizer implements DenormalizerInterface
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $class, $format = '', array $context = [])
    {
        $user = $this->security->getUser();

        if (null === $user || !$user instanceof User) {
            throw new AccessDeniedHttpException();
        }

        // @TODO: return the object once https://github.com/api-platform/core/pull/3578 gets merged
        /* @phpstan-ignore-next-line */

        return (string) $user->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = '')
    {
        return 'me' === $data && ($type === Uuid::class || $type === 'int');
    }
}
