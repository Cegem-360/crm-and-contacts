<?php

declare(strict_types=1);

use App\Enums\InvoiceStatus;
use App\Enums\OpportunityStage;
use App\Enums\QuoteStatus;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Opportunity;
use App\Models\Order;
use App\Models\Quote;
use App\Models\Team;
use App\Services\CrmReportingService;

beforeEach(function (): void {
    $this->team = Team::factory()->create();
    $this->service = resolve(CrmReportingService::class);
});

it('returns customer KPIs', function (): void {
    Customer::factory()->for($this->team)->count(2)->create(['is_active' => true]);
    Customer::factory()->for($this->team)->create(['is_active' => false]);

    $kpis = $this->service->getKpis();

    expect($kpis['customers']['total'])->toBe(3)
        ->and($kpis['customers']['active'])->toBe(2);
});

it('returns opportunity KPIs with won value using SendedQuotation stage', function (): void {
    Opportunity::factory()->for($this->team)->create([
        'stage' => OpportunityStage::Lead,
        'value' => 5000,
    ]);

    Opportunity::factory()->for($this->team)->create([
        'stage' => OpportunityStage::SendedQuotation,
        'value' => 20000,
    ]);

    Opportunity::factory()->for($this->team)->create([
        'stage' => OpportunityStage::LostQuotation,
        'value' => 8000,
    ]);

    $kpis = $this->service->getKpis();

    expect($kpis['opportunities']['active'])->toBe(1)
        ->and($kpis['opportunities']['pipeline_value'])->toBe(5000.0)
        ->and($kpis['opportunities']['won_value'])->toBe(20000.0);
});

it('returns quote KPIs with conversion rate', function (): void {
    Quote::factory()->for($this->team)->count(3)->create(['status' => QuoteStatus::Draft]);
    Quote::factory()->for($this->team)->create(['status' => QuoteStatus::Accepted]);

    $kpis = $this->service->getKpis();

    expect($kpis['quotes']['total'])->toBe(4)
        ->and($kpis['quotes']['accepted'])->toBe(1)
        ->and($kpis['quotes']['conversion_rate'])->toBe(25.0);
});

it('returns order KPIs', function (): void {
    Order::factory()->for($this->team)->count(2)->create(['total' => 5000]);

    $kpis = $this->service->getKpis();

    expect($kpis['orders']['total'])->toBe(2)
        ->and($kpis['orders']['total_revenue'])->toBe(10000.0)
        ->and($kpis['orders']['average_value'])->toBe(5000.0);
});

it('returns invoice KPIs with overdue count based on active status and past due date', function (): void {
    Invoice::factory()->for($this->team)->create([
        'status' => InvoiceStatus::Paid,
        'total' => 3000,
    ]);

    Invoice::factory()->for($this->team)->create([
        'status' => InvoiceStatus::Active,
        'due_date' => now()->subDay(),
        'total' => 7000,
    ]);

    Invoice::factory()->for($this->team)->create([
        'status' => InvoiceStatus::Active,
        'due_date' => now()->addWeek(),
        'total' => 2000,
    ]);

    Invoice::factory()->for($this->team)->create([
        'status' => InvoiceStatus::Cancelled,
        'total' => 1000,
    ]);

    $kpis = $this->service->getKpis();

    expect($kpis['invoices']['total'])->toBe(4)
        ->and($kpis['invoices']['paid'])->toBe(1)
        ->and($kpis['invoices']['overdue'])->toBe(1)
        ->and($kpis['invoices']['outstanding_amount'])->toBe(9000.0);
});

it('returns pipeline summary with all stages', function (): void {
    Opportunity::factory()->for($this->team)->create([
        'stage' => OpportunityStage::Proposal,
        'value' => 10000,
        'probability' => 60,
    ]);

    $pipeline = $this->service->getPipelineSummary();

    expect($pipeline)->toHaveKeys(['stages', 'total_pipeline_value', 'weighted_value'])
        ->and($pipeline['stages'])->toHaveCount(count(OpportunityStage::cases()));
});

it('returns monthly sales trend for given months', function (): void {
    Order::factory()->for($this->team)->create([
        'order_date' => now(),
        'total' => 15000,
    ]);

    $trend = $this->service->getMonthlySalesTrend(3);

    expect($trend['trend'])->toHaveCount(3)
        ->and($trend['trend'][2]['orders_count'])->toBe(1)
        ->and($trend['trend'][2]['orders_revenue'])->toBe(15000.0);
});

it('returns revenue forecast', function (): void {
    Opportunity::factory()->for($this->team)->create([
        'stage' => OpportunityStage::Negotiation,
        'value' => 100000,
        'probability' => 80,
        'expected_close_date' => now()->addMonth(),
    ]);

    $forecast = $this->service->getRevenueForecast(3);

    expect($forecast)->toHaveKeys(['forecast', 'generated_at'])
        ->and($forecast['forecast'])->toHaveCount(3);
});

it('returns empty data when no records exist', function (): void {
    $kpis = $this->service->getKpis();

    expect($kpis['customers']['total'])->toBe(0)
        ->and($kpis['opportunities']['active'])->toBe(0)
        ->and($kpis['quotes']['conversion_rate'])->toBe(0.0)
        ->and($kpis['orders']['total'])->toBe(0)
        ->and($kpis['invoices']['overdue'])->toBe(0);
});
