<?php
declare(strict_types=1);

namespace Merlin\CookieGuard\Plugin;

use Magento\Framework\Stdlib\Cookie\CookieMetadata;
use Magento\Framework\Stdlib\Cookie\PhpCookieManager;
use Psr\Log\LoggerInterface;

class PhpCookieManagerPlugin
{
    private const COOKIE_LIMIT = 45;

    /**
     * Cookies that must never be blocked
     */
    private const CRITICAL_COOKIES = [
        'PHPSESSID',
        'form_key',
        'private_content_version',
        'mage-cache-sessid',
        'admin',
    ];

    public function __construct(
        private readonly LoggerInterface $logger
    ) {}

    public function aroundSetPublicCookie(
        PhpCookieManager $subject,
        callable $proceed,
        string $name,
        string $value,
        CookieMetadata $metadata = null
    ) {
        if ($this->shouldSkip($name)) {
            $this->logger->warning('CookieGuard: skipped non-critical cookie near limit', [
                'cookie' => $name,
                'cookie_count' => count($_COOKIE ?? []),
            ]);
            return;
        }

        return $proceed($name, $value, $metadata);
    }

    public function aroundSetSensitiveCookie(
        PhpCookieManager $subject,
        callable $proceed,
        string $name,
        string $value,
        CookieMetadata $metadata = null
    ) {
        if ($this->shouldSkip($name)) {
            $this->logger->warning('CookieGuard: skipped non-critical sensitive cookie near limit', [
                'cookie' => $name,
                'cookie_count' => count($_COOKIE ?? []),
            ]);
            return;
        }

        return $proceed($name, $value, $metadata);
    }

    private function shouldSkip(string $cookieName): bool
    {
        $cookieCount = count($_COOKIE ?? []);

        if ($cookieCount <= self::COOKIE_LIMIT) {
            return false;
        }

        return !in_array($cookieName, self::CRITICAL_COOKIES, true);
    }
}
