<?php

namespace App\Tests\Service;

use App\Entity\User;
use App\Service\FakeEmailService;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
class MockEmailServiceTest extends TestCase
{
    /**
     * @throws Exception
     */
    #[Test]
    public function testSendWelcomeEmail(): void
    {
        $mockLogger = $this->createMock(LoggerInterface::class);
        $userMock = $this->createMock(User::class);
    
        $userMock->expects($this->once())
            ->method('getId')
            ->willReturn(1);
            
        $userMock->expects($this->once())
            ->method('getEmail')
            ->willReturn('roger.rabbit@toontown.com');

        $mockLogger->expects($this->once())
            ->method('info')
            ->with("User 1 created, sending welcome email to roger.rabbit@toontown.com");

        $emailService = new FakeEmailService($mockLogger);
        $result = $emailService->sendWelcomeEmail($userMock);
        $this->assertTrue($result, 'sendWelcomeEmail should return true');
    }

}
