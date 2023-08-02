<?php declare(strict_types=1);

namespace Loncat\Moody;

use PHPUnit\Framework\TestCase;
use Loncat\Moody\AppFactory;

class AppFactoryTest extends TestCase {
    public function testCreateAppShouldReturnAppImplInstance() {
        $config = $this->createMock(Config::class);
        $app = AppFactory::create($config);

        $this->assertInstanceOf(AppImpl::class, $app);
    }
}