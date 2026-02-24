<?php

declare(strict_types=1);

use App\Livewire\SalesDashboard;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Livewire;

beforeEach(function (): void {
    $this->team = Team::factory()->create();
    $this->user = User::factory()->create();
    $this->user->teams()->attach($this->team);
    $this->actingAs($this->user);
});

it('renders the sales dashboard component', function (): void {
    Livewire::test(SalesDashboard::class, ['team' => $this->team])
        ->assertSuccessful()
        ->assertSee('Sales Dashboard');
});

it('loads KPI data on mount', function (): void {
    Livewire::test(SalesDashboard::class, ['team' => $this->team])
        ->assertSet('period', '3')
        ->assertViewHas('kpis');
});

it('updates data when period changes', function (): void {
    Livewire::test(SalesDashboard::class, ['team' => $this->team])
        ->set('period', '6')
        ->assertSet('period', '6');
});

it('requires authentication', function (): void {
    Auth::logout();

    $this->get(route('dashboard.sales-dashboard', ['team' => $this->team]))
        ->assertRedirect();
});
