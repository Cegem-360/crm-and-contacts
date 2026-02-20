<?php

declare(strict_types=1);

use App\Enums\QuoteStatus;
use App\Models\Customer;
use App\Models\Quote;
use App\Models\QuoteItem;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('displays quote via valid token', function (): void {
    $customer = Customer::factory()->create();
    $quote = Quote::factory()->sent()->create([
        'customer_id' => $customer->id,
    ]);
    QuoteItem::factory()->create([
        'quote_id' => $quote->id,
        'team_id' => $quote->team_id,
    ]);

    $response = $this->get(route('quotes.public-view', $quote->view_token));

    $response->assertSuccessful()
        ->assertSee($quote->quote_number)
        ->assertSee($customer->name);
});

it('returns 404 for invalid token', function (): void {
    $response = $this->get(route('quotes.public-view', 'invalid-token'));

    $response->assertNotFound();
});

it('marks quote as viewed on first access', function (): void {
    $quote = Quote::factory()->sent()->create([
        'customer_id' => Customer::factory(),
    ]);

    $this->get(route('quotes.public-view', $quote->view_token));

    $quote->refresh();

    expect($quote->status)->toBe(QuoteStatus::Viewed)
        ->and($quote->viewed_at)->not->toBeNull();
});

it('does not change status on subsequent views', function (): void {
    $quote = Quote::factory()->viewed()->create([
        'customer_id' => Customer::factory(),
    ]);

    $this->get(route('quotes.public-view', $quote->view_token));

    $quote->refresh();

    expect($quote->status)->toBe(QuoteStatus::Viewed);
});
