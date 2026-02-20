<?php

declare(strict_types=1);

use App\Models\Product;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

beforeEach(function (): void {
    $this->team = Team::factory()->create();
    $this->user = User::factory()->create([
        'email' => 'products@example.com',
        'password' => Hash::make('password'),
    ]);
    $this->user->teams()->attach($this->team);
});

function loginAndVisitProducts(object $context): mixed
{
    $page = visit('/admin/login');

    $page->type('#form\\.email', 'products@example.com')
        ->type('#form\\.password', 'password')
        ->submit()
        ->wait(2);

    return visit('/dashboard/'.$context->team->slug.'/products');
}

it('renders the products list page', function (): void {
    $page = loginAndVisitProducts($this);

    $page->assertPathIs('/dashboard/'.$this->team->slug.'/products')
        ->assertSee('Products')
        ->assertSee('New Product')
        ->assertNoJavaScriptErrors()
        ->screenshot(filename: screenshotPath('products/list'), fullPage: true);
});

it('displays seeded products in the table', function (): void {
    Product::factory()->for($this->team)->create([
        'name' => 'Browser Test Widget',
        'sku' => 'PRD-BT-001',
    ]);

    $page = loginAndVisitProducts($this);

    $page->assertSee('Browser Test Widget')
        ->assertSee('PRD-BT-001')
        ->assertNoJavaScriptErrors()
        ->screenshot(filename: screenshotPath('products/with-data'), fullPage: true);
});

it('renders the product view page', function (): void {
    $product = Product::factory()->for($this->team)->create([
        'name' => 'View Test Product',
    ]);

    loginAndVisitProducts($this);

    $page = visit('/dashboard/'.$this->team->slug.'/products/'.$product->id);

    $page->assertSee('View Test Product')
        ->assertSee('Product details')
        ->assertNoJavaScriptErrors()
        ->screenshot(filename: screenshotPath('products/view'), fullPage: true);
});

it('renders the product edit page', function (): void {
    $product = Product::factory()->for($this->team)->create();

    loginAndVisitProducts($this);

    $page = visit('/dashboard/'.$this->team->slug.'/products/'.$product->id.'/edit');

    $page->assertSee('Edit Product')
        ->assertNoJavaScriptErrors()
        ->screenshot(filename: screenshotPath('products/edit'), fullPage: true);
});
