<?php

declare(strict_types=1);

use App\Enums\CampaignResponseType;
use App\Enums\CustomerType;
use App\Filament\Imports\CampaignResponseImporter;
use App\Filament\Imports\CustomerImporter;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Illuminate\Support\Facades\Validator;

/**
 * Every import column that maps onto a backed enum, with the enum behind it.
 *
 * @return array<string, array{class-string<Importer>, string, class-string<BackedEnum>}>
 */
dataset('enum import columns', [
    'Customer type' => [CustomerImporter::class, 'type', CustomerType::class],
    'Campaign response type' => [CampaignResponseImporter::class, 'response_type', CampaignResponseType::class],
]);

/**
 * @param  class-string<Importer>  $importer
 */
function importColumn(string $importer, string $name): ImportColumn
{
    $column = collect($importer::getColumns())
        ->first(fn (ImportColumn $column): bool => $column->getName() === $name);

    expect($column)->not->toBeNull(sprintf('`%s` has no `%s` column.', $importer, $name));

    return $column;
}

/**
 * Without an enum rule an unknown CSV value reaches the model cast, which throws
 * `ValueError: "..." is not a valid backing value for enum`. That surfaced in
 * production as a failed import instead of a per-row validation message.
 */
it('rejects a value that is not a case of the enum', function (string $importer, string $name, string $enum): void {
    $column = importColumn($importer, $name);

    $validator = Validator::make(
        [$name => 'Voluptas aut quia ve'],
        [$name => $column->getDataValidationRules()],
    );

    expect($validator->fails())->toBeTrue(
        sprintf('`%s` column `%s` accepts values outside `%s`.', $importer, $name, $enum),
    );
})->with('enum import columns');

/**
 * `castStateItem()` turns a blank CSV cell into `null` before validation runs, so an
 * optional enum column must still accept an empty cell. `CustomerImporter` relies on
 * this: it infers the type from the tax number when the column is left empty.
 */
it('accepts an empty cell on an optional enum column', function (string $importer, string $name): void {
    $column = importColumn($importer, $name);
    $rules = $column->getDataValidationRules();

    if (! in_array('nullable', $rules, true)) {
        expect($rules)->toContain('required');

        return;
    }

    $validator = Validator::make([$name => null], [$name => $rules]);

    expect($validator->fails())->toBeFalse(
        sprintf('`%s` column `%s` rejects an empty cell even though it is optional.', $importer, $name),
    );
})->with('enum import columns');

it('accepts every case of the enum', function (string $importer, string $name, string $enum): void {
    $column = importColumn($importer, $name);

    foreach ($enum::cases() as $case) {
        $validator = Validator::make(
            [$name => $case->value],
            [$name => $column->getDataValidationRules()],
        );

        expect($validator->fails())->toBeFalse(
            sprintf('`%s` column `%s` rejects the valid case `%s`.', $importer, $name, $case->value),
        );
    }
})->with('enum import columns');
