<?php

declare(strict_types=1);

use App\Models\Discount;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

beforeEach(function (): void {
    $this->team = Team::factory()->create();
    $this->user = User::factory()->create([
        'email' => 'discounts@example.com',
        'password' => Hash::make('password'),
    ]);
    $this->user->teams()->attach($this->team);
});

function loginAndVisitDiscounts(object $context): mixed
{
    $page = visit('/admin/login');

    $page->type('#form\\.email', 'discounts@example.com')
        ->type('#form\\.password', 'password')
        ->submit()
        ->wait(2);

    return visit('/dashboard/'.$context->team->slug.'/discounts');
}

it('renders the discounts list page', function (): void {
    $page = loginAndVisitDiscounts($this);

    $page->assertPathIs('/dashboard/'.$this->team->slug.'/discounts')
        ->assertSee('Discounts')
        ->assertSee('New Discount')
        ->assertNoJavaScriptErrors()
        ->screenshot(filename: screenshotPath('discounts/list'), fullPage: true);
});

it('displays seeded discounts in the table', function (): void {
    Discount::factory()->for($this->team)->create([
        'name' => 'Summer Sale Test',
        'customer_id' => null,
        'product_id' => null,
    ]);

    $page = loginAndVisitDiscounts($this);

    $page->assertSee('Summer Sale Test')
        ->assertNoJavaScriptErrors()
        ->screenshot(filename: screenshotPath('discounts/with-data'), fullPage: true);
});

it('renders the discount view page', function (): void {
    $discount = Discount::factory()->for($this->team)->create([
        'name' => 'View Discount Test',
        'customer_id' => null,
        'product_id' => null,
    ]);

    loginAndVisitDiscounts($this);

    $page = visit('/dashboard/'.$this->team->slug.'/discounts/'.$discount->id);

    $page->assertSee('View Discount Test')
        ->assertSee('Discount details')
        ->assertNoJavaScriptErrors()
        ->screenshot(filename: screenshotPath('discounts/view'), fullPage: true);
});

it('renders the discount edit page', function (): void {
    $discount = Discount::factory()->for($this->team)->create([
        'customer_id' => null,
        'product_id' => null,
    ]);

    loginAndVisitDiscounts($this);

    $page = visit('/dashboard/'.$this->team->slug.'/discounts/'.$discount->id.'/edit');

    $page->assertSee('Edit Discount')
        ->assertNoJavaScriptErrors()
        ->screenshot(filename: screenshotPath('discounts/edit'), fullPage: true);
});
