<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Campaigns;

use App\Filament\Resources\Campaigns\CampaignResource;
use App\Filament\Resources\Campaigns\Pages\ViewCampaign as FilamentViewCampaign;
use App\Filament\Resources\Campaigns\Schemas\CampaignInfolist;
use App\Livewire\Concerns\HasCurrentTeam;
use App\Models\Campaign;
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
final class ViewCampaign extends Component implements HasActions, HasSchemas
{
    use HasCurrentTeam;
    use InteractsWithActions;
    use InteractsWithSchemas;

    public Campaign $campaign;

    #[Url(as: 'relation')]
    public ?string $activeRelationManager = null;

    public function mount(Campaign $campaign): void
    {
        $this->campaign = $campaign;
    }

    public function infolist(Schema $schema): Schema
    {
        return CampaignInfolist::configure($schema)
            ->record($this->campaign)
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
            'ownerRecord' => $this->campaign,
            'pageClass' => FilamentViewCampaign::class,
        ];

        if (count($managers) > 1) {
            $tabs = collect($managers)
                ->map(function (string|RelationGroup|RelationManagerConfiguration $manager) use ($managerLivewireData): Tab {
                    $normalizedManagerClass = $this->normalizeRelationManagerClass($manager);

                    return $normalizedManagerClass::getTabComponent($this->campaign, FilamentViewCampaign::class)
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
        return view('livewire.pages.campaigns.view-campaign');
    }

    /** @return array<class-string<RelationManager> | RelationGroup | RelationManagerConfiguration> */
    private function getRelationManagers(): array
    {
        return array_filter(
            CampaignResource::getRelations(),
            fn (string|RelationGroup|RelationManagerConfiguration $manager): bool => $this->normalizeRelationManagerClass($manager)::canViewForRecord($this->campaign, FilamentViewCampaign::class),
        );
    }

    /** @return class-string<RelationManager> */
    private function normalizeRelationManagerClass(string|RelationManagerConfiguration $manager): string
    {
        return $manager instanceof RelationManagerConfiguration ? $manager->relationManager : $manager;
    }
}
