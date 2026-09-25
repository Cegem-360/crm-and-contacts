<?php

declare(strict_types=1);

use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Illuminate\Support\Str;
use League\Csv\Bom;
use League\Csv\Writer;
use Symfony\Component\Finder\Finder;

/**
 * Every `Importer` in the application, keyed by class name for readable dataset labels.
 *
 * @return array<string, array{class-string<Importer>}>
 */
function importerClasses(): array
{
    $importers = [];

    $directory = dirname(__DIR__, 3).'/app/Filament/Imports';

    foreach (Finder::create()->files()->in($directory)->name('*Importer.php') as $file) {
        $class = 'App\\Filament\\Imports\\'.Str::before($file->getFilename(), '.php');

        if (! class_exists($class)) {
            continue;
        }

        if (! is_subclass_of($class, Importer::class)) {
            continue;
        }

        $importers[class_basename($class)] = [$class];
    }

    return $importers;
}

/**
 * `ImportAction`'s `downloadExample` modal action passes `getExamples()` straight into
 * `league/csv`, which casts every value to a string. A `BackedEnum` case is an object,
 * so it throws `Object of class ... could not be converted to string` and the download
 * fails with a 500 instead of returning the sample file.
 */
it('writes `getExamples()` values that `league/csv` can cast to string', function (string $importer): void {
    $columns = $importer::getColumns();

    expect($columns)->not->toBeEmpty();

    foreach ($columns as $column) {
        foreach ($column->getExamples() as $example) {
            expect($example)->not->toBeObject(
                sprintf('`%s` column `%s` has an object example; pass the scalar value instead.', $importer, $column->getName()),
            );
        }
    }
})->with(importerClasses());

it('builds the example CSV the way `downloadExample` does, without throwing', function (string $importer): void {
    /** @var array<ImportColumn> $columns */
    $columns = $importer::getColumns();

    $csv = Writer::createFromFileObject(new SplTempFileObject);

    $csv->insertOne(array_map(
        fn (ImportColumn $column): string => $column->getExampleHeader(),
        $columns,
    ));

    $columnExamples = array_map(
        fn (ImportColumn $column): array => $column->getExamples(),
        $columns,
    );

    $exampleRowsCount = array_reduce(
        $columnExamples,
        fn (int $count, array $exampleData): int => max($count, count($exampleData)),
        initial: 0,
    );

    $exampleRows = [];

    foreach ($columnExamples as $exampleData) {
        for ($i = 0; $i < $exampleRowsCount; $i++) {
            $exampleRows[$i][] = $exampleData[$i] ?? '';
        }
    }

    $csv->insertAll($exampleRows);
    $csv->setOutputBOM(Bom::Utf8);

    expect($csv->toString())->toBeString()->not->toBeEmpty();
})->with(importerClasses());
