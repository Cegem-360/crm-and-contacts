# Filament Importer - Fejlesztesi utmutato

Ez a dokumentum osszefoglalja a Filament v5 importerek fejlesztese soran felmerult problemakat es megoldasokat. Hasznald referenciaul hasonlo projektek importereinek elkeszitesehez.

---

## 1. Tenant (team) scope problema queue workerben

### Problema

A Filament import a queue workerben fut, ahol nincs Filament tenant kontextus. Ha a modell `BelongsToTeam` trait-et hasznal (ami global scope-ot es creating eventet alkalmaz), a queue-ban a `team_id` nem lesz beallitva, mert a container binding (`current_team`) nem letezik.

**Eredmeny:** A rekordok `team_id = null`-lal jonnek letre, es a `TeamScope` kiszuri oket a listakbol.

### Megoldas

1. Az `ImportAction`-bol add at a `teamId`-t options-kent (a web requestben, ahol a tenant elerheto):

```php
use Filament\Actions\ImportAction;
use Filament\Facades\Filament;

ImportAction::make()
    ->importer(CustomerImporter::class)
    ->options(['teamId' => Filament::getTenant()?->getKey()]),
```

2. Az importerben a `beforeCreate()` hookban allitsd be a `team_id`-t:

```php
protected function beforeCreate(): void
{
    $this->record->team_id = $this->options['teamId'] ?? null;
}
```

> **Fontos:** Ez minden importerre vonatkozik, ahol a modell tenant-scoped. Ne feledkezz meg rola uj importer letrehozasakor!

---

## 2. Alapertelmezett ertekek nem mappelt oszlopokhoz

### Problema

Ha egy CSV oszlop nincs mappelve (a felhasznalo nem parositotta oszlophoz), a Filament nem tolti be a `$this->data`-bol a modellbe. Hiaba allitod be a `resolveRecord()`-ban a `$this->data['field']` erteket, az nem kerul a rekordra.

**Peldaul:** `unique_identifier` auto-generalasa, `type` auto-detektalasa, `is_active` alapertelmezett `true`.

### Megoldas

Hasznald a `beforeCreate()` hookot, es kozvetlenul a `$this->record`-ra irj:

```php
public function resolveRecord(): Customer
{
    // Keszitsd elo a data-t (ez kell a firstOrNew-hoz is)
    if (empty($this->data['unique_identifier'])) {
        $this->data['unique_identifier'] = 'CUST-' . Str::upper(Str::random(8));
    }

    if (empty($this->data['type'])) {
        $this->data['type'] = empty($this->data['eu_tax_number'])
            ? CustomerType::Individual->value
            : CustomerType::Company->value;
    }

    if ($this->options['updateExisting'] ?? false) {
        return Customer::query()->firstOrNew([
            'unique_identifier' => $this->data['unique_identifier'],
        ]);
    }

    return new Customer();
}

protected function beforeCreate(): void
{
    // Ezek akkor kellenek, ha az oszlop nincs mappelve a CSV-ben
    if (blank($this->record->unique_identifier)) {
        $this->record->unique_identifier = $this->data['unique_identifier'];
    }

    if (blank($this->record->type)) {
        $this->record->type = $this->data['type'];
    }

    if ($this->record->is_active === null) {
        $this->record->is_active = true;
    }
}
```

> **Miert `beforeCreate()` es nem `beforeSave()`?** Mert az alapertelmezett ertekek csak uj rekord letrehozasakor kellenek. Frissitesnel ezek mar leteznek.

---

## 3. Lokalizalt boolean mezo (magyar: igen/nem)

### Problema

A Filament `boolean()` cast csak ezeket ismeri fel:
- Truthy: `1`, `true`, `yes`, `y`, `on`
- Falsy: `0`, `false`, `no`, `n`, `off`

Magyar ertekek (`igen`, `nem`, `i`) nem mukodnek.

### Megoldas

Hozz letre egy custom `ImportColumn` osztalyt, ami kiterjeszti a Filament-eset:

**`app/Filament/Imports/Columns/ImportColumn.php`**

```php
<?php

declare(strict_types=1);

namespace App\Filament\Imports\Columns;

use Filament\Actions\Imports\ImportColumn as BaseImportColumn;

final class ImportColumn extends BaseImportColumn
{
    public function localizedBoolean(?bool $default = null): static
    {
        $this->boolean();

        $this->castStateUsing(function (?bool $state, mixed $originalState) use ($default): ?bool {
            if ($originalState !== null) {
                return match (mb_strtolower((string) $originalState)) {
                    'igen', 'i' => true,
                    'nem' => false,
                    default => $state,
                };
            }

            return $state ?? $default;
        });

        return $this;
    }
}
```

**Hasznalat:**

```php
use App\Filament\Imports\Columns\ImportColumn;

ImportColumn::make('is_active')
    ->localizedBoolean(default: true),
```

Ez kezeli:
- Standard ertekek: `true/false`, `yes/no`, `1/0`, stb.
- Magyar ertekek: `igen/nem`, `i`
- Ures mezo: a `default` parametert hasznalja

> **Bovites:** Uj nyelvhez adj hozza tovabbi `match` agakat (pl. `'ja' => true`, `'nein' => false` nemetul).

---

## 4. Kapcsolodo entitasok importalasa (HasMany relaciok)

### Problema

Egy ugyfelnek lehet tobb cime es tobb kapcsolattartoja. Hogyan importaljuk ezeket?

### Megoldas

**Kulon importer** minden entitashoz, ami a `unique_identifier` vagy `name` alapjan linkeli a customert:

```php
ImportColumn::make('customer')
    ->requiredMapping()
    ->relationship(resolveUsing: function (string $state): ?Customer {
        return Customer::query()
            ->where('unique_identifier', $state)
            ->orWhere('name', $state)
            ->first();
    })
    ->rules(['required']),
```

### Miert kulon importer es nem lapitott oszlopok?

| Megoldas | Elony | Hatrany |
|---|---|---|
| Lapitott oszlopok (`billing_city`, `shipping_city`) | Egyszeru CSV | Fix max cimszam, sok oszlop |
| Kulon importer | Korlatlan rekord, tiszta CSV | Ket import lepesben |

A kulon importer a jobb valasztas, mert:
- Egy ugyfelnek lehet 2, 3, akar 5 cime
- A CSV struktura tiszta marad
- A felhasznalo barmelyik CSV formatumot tudja mappelni

### Regisztralas relation manageren

```php
use Filament\Actions\ImportAction;
use Filament\Facades\Filament;

// A relation manager table() metodusaban:
->headerActions([
    CreateAction::make(),
    ImportAction::make()
        ->importer(CustomerAddressImporter::class)
        ->options([
            'customerId' => $this->getOwnerRecord()->getKey(),
            'teamId' => Filament::getTenant()?->getKey(),
        ]),
])
```

---

## 5. Filament Importer hookek hasznalata

A Filament importer eletciklus hookjei fontossagi sorrendben:

| Hook | Mikor fut | Tipikus hasznalat |
|---|---|---|
| `beforeValidate()` | Validacio elott | Adattisztitas |
| `afterValidate()` | Validacio utan | - |
| `beforeFill()` | Modell kitoltese elott | - |
| `afterFill()` | Modell kitoltese utan | - |
| `beforeSave()` | Mentes elott (create + update) | - |
| `beforeCreate()` | Csak uj rekord elott | **team_id, alapertelmezett ertekek** |
| `beforeUpdate()` | Csak frissites elott | - |
| `afterSave()` | Mentes utan (create + update) | Kapcsolodo rekordok letrehozasa |
| `afterCreate()` | Csak uj rekord utan | - |
| `afterUpdate()` | Csak frissites utan | - |

Elerheto valtozok a hookokban:
- `$this->data` — feldolgozott sor adatai
- `$this->originalData` — eredeti CSV sor (cast/map elott)
- `$this->record` — az aktualis Eloquent modell
- `$this->options` — az ImportAction-bol atadott opciok

---

## 6. Checklist uj importer letrehozasahoz

1. `php artisan make:filament-importer ModelImporter --no-interaction`
2. Hasznald a custom `ImportColumn`-ot lokalizalt boolean mezokhaz
3. Allitsd be a `team_id`-t a `beforeCreate()` hookban
4. Add at a `teamId`-t az `ImportAction` options-ben
5. Hasznalj `beforeCreate()`-et (nem `beforeSave()`-et) alapertelmezett ertekekhez
6. Relationship oszlopoknal hasznalj `resolveUsing`-ot hogy `unique_identifier` vagy `name` alapjan kereshessen
7. Add hozza az `updateExisting` opciot ha szukseges
8. Futtasd a `vendor/bin/pint --dirty --format agent`-et
9. Irj tesztet vagy futtasd a meglevo teszteket
