<?php

namespace Tests\Feature;

use Tests\TestCase;

class MobileApiAuthenticationTest extends TestCase
{
    public function test_private_mobile_endpoints_require_a_valid_mobile_token(): void
    {
        $this->getJson('/api/mobile/garage')
            ->assertUnauthorized();
    }

    public function test_mobile_admin_dashboard_requires_authentication(): void
    {
        $this->getJson('/api/mobile/admin/dashboard')
            ->assertUnauthorized();
    }
}
