<?php

namespace App\Service;

use App\Entity\User;
use Psr\Log\LoggerInterface;

final readonly class FakeEmailService
{

    public function __construct(private LoggerInterface $logger)
    { }

    public function sendWelcomeEmail(User $user): bool
    {
        $id = $user->getId();
        $email = $user->getEmail();

        // In a real application, this would send an actual email
        $this->logger->info(sprintf("User %s created, sending welcome email to %s", $id, $email));

        return true;
    }
}
