<?php

namespace App\EventSubscriber;

use ApiPlatform\Symfony\EventListener\EventPriorities;
use App\Entity\User;
use App\Service\MockEmailService;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

readonly class UserCreatedSubscriber implements EventSubscriberInterface
{
    public function __construct(private MockEmailService $emailService, private LoggerInterface $logger)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['sendWelcomeEmail', EventPriorities::POST_WRITE],
        ];
    }

    public function sendWelcomeEmail(ViewEvent $event): void
    {
        $user = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$user instanceof User || Request::METHOD_POST !== $method) {
            return;
        }
        $email = $user->getEmail();

        if (!$email || trim($email) === '') {
            $fullName = $user->getFullName();
            // log that the welcome email cannot be sent
            $this->logger->info("User [$fullName] created, but no email address was provided.");
            return;
        }

        $this->emailService->sendWelcomeEmail($user);
    }
}
