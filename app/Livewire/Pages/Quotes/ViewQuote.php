<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Quotes;

use App\Filament\Resources\Quotes\Pages\ViewQuote as FilamentViewQuote;
use App\Filament\Resources\Quotes\QuoteResource;
use App\Filament\Resources\Quotes\Schemas\QuoteInfolist;
use App\Livewire\Concerns\HasCurrentTeam;
use App\Models\Quote;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Resources\RelationManagers\RelationGroup;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\RelationManagers\RelationManagerConfiguration;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Livewire as LivewireComponent;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class ViewQuote extends Component implements HasActions, HasSchemas
{
    use HasCurrentTeam;
    use InteractsWithActions;
    use InteractsWithSchemas;

    public Quote $quote;

    #[Url(as: 'relation')]
    public ?string $activeRelationManager = null;

    public function mount(Quote $quote): void
    {
        $this->quote = $quote->load(['customer', 'opportunity']);
    }

    public function infolist(Schema $schema): Schema
    {
        return QuoteInfolist::configure($schema)
            ->record($this->quote)
            ->columns(2);
    }

    public function rendering(): void
    {
        $managers = $this->getRelationManagers();

        if (array_key_exists($this->activeRelationManager, $managers)) {
            return;
        }

        $this->activeRelationManager = ($key = array_key_first($managers)) !== null ? (string) $key : null;
    }

    public function relationManagers(Schema $schema): Schema
    {
        $managers = $this->getRelationManagers();

        if ($managers === []) {
            return $schema->components([Group::make()->hidden()]);
        }

        $managerLivewireData = [
            'ownerRecord' => $this->quote,
            'pageClass' => FilamentViewQuote::class,
        ];

        if (count($managers) > 1) {
            $tabs = collect($managers)
                ->map(function (string|RelationGroup|RelationManagerConfiguration $manager) use ($managerLivewireData): Tab {
                    $normalizedManagerClass = $this->normalizeRelationManagerClass($manager);

                    return $normalizedManagerClass::getTabComponent($this->quote, FilamentViewQuote::class)
                        ->schema(fn (): array => [
                            LivewireComponent::make(
                                $normalizedManagerClass,
                                [
                                    ...$managerLivewireData,
                                    ...(($manager instanceof RelationManagerConfiguration)
                                        ? [...$manager->relationManager::getDefaultProperties(), ...$manager->getProperties()]
                                        : $normalizedManagerClass::getDefaultProperties()),
                                ],
                            )->key($normalizedManagerClass),
                        ]);
                })
                ->all();

            return $schema->components([
                Tabs::make()
                    ->livewireProperty('activeRelationManager')
                    ->contained(false)
                    ->tabs($tabs),
            ]);
        }

        $manager = array_values($managers)[0];
        $normalizedManagerClass = $this->normalizeRelationManagerClass($manager);

        return $schema->components([
            LivewireComponent::make(
                $normalizedManagerClass,
                [
                    ...$managerLivewireData,
                    ...(($manager instanceof RelationManagerConfiguration)
                        ? [...$manager->relationManager::getDefaultProperties(), ...$manager->getProperties()]
                        : $normalizedManagerClass::getDefaultProperties()),
                ],
            )->key($normalizedManagerClass),
        ]);
    }

    public function render(): View
    {
        return view('livewire.pages.quotes.view-quote');
    }

    /** @return array<class-string<RelationManager> | RelationGroup | RelationManagerConfiguration> */
    private function getRelationManagers(): array
    {
        return array_filter(
            QuoteResource::getRelations(),
            fn (string|RelationGroup|RelationManagerConfiguration $manager): bool => $this->normalizeRelationManagerClass($manager)::canViewForRecord($this->quote, FilamentViewQuote::class),
        );
    }

    /** @return class-string<RelationManager> */
    private function normalizeRelationManagerClass(string|RelationManagerConfiguration $manager): string
    {
        return $manager instanceof RelationManagerConfiguration ? $manager->relationManager : $manager;
    }
}
