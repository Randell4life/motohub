<?php

namespace Tests\Unit;

use App\Models\ServiceCenter;
use Carbon\Carbon;
use Tests\TestCase;

class ServiceCenterSubscriptionTest extends TestCase
{
    public function test_start_plan_only_allows_public_profile_and_bookings(): void
    {
        $center = new ServiceCenter([
            'subscription_plan' => 'start',
            'subscription_status' => 'active',
        ]);

        $this->assertTrue($center->canUseFeature('services'));
        $this->assertTrue($center->canUseFeature('bookings'));
        $this->assertFalse($center->canUseFeature('work_orders'));
        $this->assertFalse($center->canUseFeature('team'));
        $this->assertFalse($center->canUseFeature('inventory'));
    }

    public function test_business_plan_includes_pro_and_inventory_features(): void
    {
        $center = new ServiceCenter([
            'subscription_plan' => 'business',
            'subscription_status' => 'active',
        ]);

        $this->assertTrue($center->canUseFeature('work_orders'));
        $this->assertTrue($center->canUseFeature('team'));
        $this->assertTrue($center->canUseFeature('finance'));
        $this->assertTrue($center->canUseFeature('inventory'));
        $this->assertFalse($center->canUseFeature('advanced_reports'));
    }

    public function test_expired_or_inactive_subscription_locks_paid_features(): void
    {
        Carbon::setTestNow('2026-10-31 12:00:00');

        $expired = new ServiceCenter([
            'subscription_plan' => 'premium',
            'subscription_status' => 'active',
            'subscription_ends_at' => Carbon::parse('2026-10-30 12:00:00'),
        ]);

        $pending = new ServiceCenter([
            'subscription_plan' => 'pro',
            'subscription_status' => 'pending_payment',
        ]);

        $this->assertSame('expired', $expired->effectiveSubscriptionStatus());
        $this->assertFalse($expired->canUseFeature('work_orders'));
        $this->assertFalse($pending->canUseFeature('work_orders'));

        Carbon::setTestNow();
    }

    public function test_plan_team_limits_are_applied(): void
    {
        $pro = new ServiceCenter(['subscription_plan' => 'pro']);
        $business = new ServiceCenter(['subscription_plan' => 'business']);
        $premium = new ServiceCenter(['subscription_plan' => 'premium']);

        $this->assertSame(5, $pro->technicianLimit());
        $this->assertSame(3, $pro->bayLimit());
        $this->assertSame(15, $business->technicianLimit());
        $this->assertSame(10, $business->bayLimit());
        $this->assertNull($premium->technicianLimit());
        $this->assertNull($premium->bayLimit());
    }
}
