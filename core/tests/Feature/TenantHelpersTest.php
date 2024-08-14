<?php

namespace Tests\Feature;

use App\Helpers\TenantHelper\TenantHelpers;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\PaymentLogs;
use App\Models\Tenant;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Mockery;

class TenantHelpersTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
    public function testSetAndGetTenantId()
    {
        $tenantId = 'example-tenant-id';
        $tenantHelpers = TenantHelpers::init();

        $result = $tenantHelpers->setTenantId($tenantId);

        $this->assertEquals($tenantId, $tenantHelpers->getTenantId());
        $this->assertInstanceOf(TenantHelpers::class, $result);
    }


    public function testSetAndGetTheme()
    {
        $theme = 'example-theme';
        $tenantHelpers = TenantHelpers::init();

        $result = $tenantHelpers->setTheme($theme);

        $this->assertEquals($theme, $tenantHelpers->getTheme());
        $this->assertInstanceOf(TenantHelpers::class, $result);
    }

    private function createMockTenant($id, $renewStatus = 0)
    {
        $mockTenant = Mockery::mock(Tenant::class);
        $mockTenant->shouldReceive('find')->with($id)->andReturn($mockTenant);
        $mockTenant->renew_status = $renewStatus;
        $mockTenant->shouldReceive('update');

        return $mockTenant;
    }

    // Helper function to create a mock of the PaymentLogs model
    private function createMockPaymentLogs($id, $package, $expireDate, $renewStatus = null)
    {
        $mockPaymentLogs = Mockery::mock(PaymentLogs::class);
        $mockPaymentLogs->shouldReceive('findOrFail')->with($id)->andReturn($mockPaymentLogs);
        $mockPaymentLogs->package_id = $package->id;
        $mockPaymentLogs->expire_date = $expireDate;
        $mockPaymentLogs->renew_status = $renewStatus;
        $mockPaymentLogs->shouldReceive('update');

        return $mockPaymentLogs;
    }

    public function testInitReturnsInstanceOfTenantHelpers()
    {
        $tenantHelpers = TenantHelpers::init();
        $this->assertInstanceOf(TenantHelpers::class, $tenantHelpers);
    }


//    public function testPaymentLogUpdateWithoutUpdateOnlyGivenField()
//    {
//        $tenantId = 'example-tenant-id';
//        $mockTenant = $this->createMockTenant($tenantId, 0);
//
//        $package = (object) [
//            'id' => 1,
//            'getTranslation' => function ($field, $lang) {
//                return 'Example Package';
//            },
//            'price' => 100,
//            'has_trial' => 0,
//        ];
//
//        $expireDate = Carbon::now()->addMonth(1);
//        $mockPaymentLogs = $this->createMockPaymentLogs(1, $package, $expireDate);
//
//        $this->app->instance(Tenant::class, $mockTenant);
//        $this->app->instance(PaymentLogs::class, $mockPaymentLogs);
//
//        $tenantHelpers = TenantHelpers::init()
//            ->setTenantId($tenantId)
//            ->setPackage($package)
//            ->paymentLogUpdate([
//                'payment_status' => 'paid',
//            ]);
//
//        $result = $tenantHelpers->getPaymentLog();
//
//
//
//        $this->assertInstanceOf(PaymentLogs::class, $result);
//        $this->assertEquals(2, $result->renew_status);
//        $this->assertEquals(1, $result->is_renew);
//        $this->assertEquals($expireDate, $result->expire_date);
//        $this->assertEquals('Example Package', $result->package_name);
//        $this->assertEquals(100, $result->package_price);
//        $this->assertEquals('paid', $result->payment_status);
//    }

    // Add test cases for other methods

    // ...

    // Clean up the mocked instances after each test
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
