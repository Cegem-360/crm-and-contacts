<?php

declare(strict_types=1);

use App\Enums\CustomFieldModel;
use App\Models\CustomField;
use App\Services\CustomFieldService;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Cache;

beforeEach(function (): void {
    $this->service = resolve(CustomFieldService::class);
});

it('returns active fields for a model type ordered by sort order', function (): void {
    CustomField::factory()->forCustomer()->create(['sort_order' => 2, 'is_active' => true]);
    CustomField::factory()->forCustomer()->create(['sort_order' => 1, 'is_active' => true]);
    CustomField::factory()->forCustomer()->create(['sort_order' => 3, 'is_active' => false]);

    $fields = $this->service->getFieldsForModel(CustomFieldModel::Customer);

    expect($fields)->toHaveCount(2)
        ->and($fields->first()->sort_order)->toBe(1);
});

it('caches the fields for a model type', function (): void {
    CustomField::factory()->forCustomer()->create(['is_active' => true]);

    $this->service->getFieldsForModel(CustomFieldModel::Customer);

    expect(Cache::has('custom_fields.customer'))->toBeTrue();
});

it('clears cache for a specific model type', function (): void {
    CustomField::factory()->forCustomer()->create(['is_active' => true]);
    $this->service->getFieldsForModel(CustomFieldModel::Customer);

    $this->service->clearCache(CustomFieldModel::Customer);

    expect(Cache::has('custom_fields.customer'))->toBeFalse();
});

it('clears cache for all model types', function (): void {
    CustomField::factory()->forCustomer()->create(['is_active' => true]);
    CustomField::factory()->forOrder()->create(['is_active' => true]);
    $this->service->getFieldsForModel(CustomFieldModel::Customer);
    $this->service->getFieldsForModel(CustomFieldModel::Order);

    $this->service->clearCache();

    expect(Cache::has('custom_fields.customer'))->toBeFalse()
        ->and(Cache::has('custom_fields.order'))->toBeFalse();
});

it('generates text input form component for text type', function (): void {
    CustomField::factory()->forCustomer()->text()->create([
        'is_active' => true,
        'is_visible_in_form' => true,
    ]);

    $components = $this->service->getFormComponents(CustomFieldModel::Customer);

    expect($components)->toHaveCount(1)
        ->and($components[0])->toBeInstanceOf(TextInput::class);
});

it('generates checkbox form component for checkbox type', function (): void {
    CustomField::factory()->forCustomer()->checkbox()->create([
        'is_active' => true,
        'is_visible_in_form' => true,
    ]);

    $components = $this->service->getFormComponents(CustomFieldModel::Customer);

    expect($components)->toHaveCount(1)
        ->and($components[0])->toBeInstanceOf(Checkbox::class);
});

it('generates date picker form component for date type', function (): void {
    CustomField::factory()->forCustomer()->date()->create([
        'is_active' => true,
        'is_visible_in_form' => true,
    ]);

    $components = $this->service->getFormComponents(CustomFieldModel::Customer);

    expect($components)->toHaveCount(1)
        ->and($components[0])->toBeInstanceOf(DatePicker::class);
});

it('generates select form component for select type', function (): void {
    CustomField::factory()->forCustomer()->select()->create([
        'is_active' => true,
        'is_visible_in_form' => true,
        'options' => ['A', 'B', 'C'],
    ]);

    $components = $this->service->getFormComponents(CustomFieldModel::Customer);

    expect($components)->toHaveCount(1)
        ->and($components[0])->toBeInstanceOf(Select::class);
});

it('generates table columns for visible fields', function (): void {
    CustomField::factory()->forCustomer()->text()->create([
        'is_active' => true,
        'is_visible_in_table' => true,
    ]);
    CustomField::factory()->forCustomer()->checkbox()->create([
        'is_active' => true,
        'is_visible_in_table' => true,
    ]);
    CustomField::factory()->forCustomer()->text()->create([
        'is_active' => true,
        'is_visible_in_table' => false,
    ]);

    $columns = array_values($this->service->getTableColumns(CustomFieldModel::Customer));

    expect($columns)->toHaveCount(2);

    $types = array_map(fn ($c) => $c::class, $columns);
    expect($types)->toContain(TextColumn::class)
        ->toContain(IconColumn::class);
});

it('generates infolist entries for visible fields', function (): void {
    CustomField::factory()->forCustomer()->text()->create([
        'is_active' => true,
        'is_visible_in_infolist' => true,
    ]);
    CustomField::factory()->forCustomer()->checkbox()->create([
        'is_active' => true,
        'is_visible_in_infolist' => true,
    ]);

    $entries = array_values($this->service->getInfolistEntries(CustomFieldModel::Customer));

    expect($entries)->toHaveCount(2);

    $types = array_map(fn ($e) => $e::class, $entries);
    expect($types)->toContain(TextEntry::class)
        ->toContain(IconEntry::class);
});

it('excludes fields not visible in form', function (): void {
    CustomField::factory()->forCustomer()->create([
        'is_active' => true,
        'is_visible_in_form' => false,
    ]);

    $components = $this->service->getFormComponents(CustomFieldModel::Customer);

    expect($components)->toHaveCount(0);
});
