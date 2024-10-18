<?php

namespace App\Service;

use App\Entity\User;
use Psr\Log\LoggerInterface;

final readonly class MockEmailService
{

    public function __construct(private LoggerInterface $logger)
    {
    }

    public function sendWelcomeEmail(User $user): void
    {
        $id = $user->getId();
        $email = $user->getEmail();
        $fullName = $user->getFullName();

        // In a real application, this would send an actual email
        $this->logger->info("User {{ $id }} created, sending welcome email to {{ $email }}");
        
    }
}
