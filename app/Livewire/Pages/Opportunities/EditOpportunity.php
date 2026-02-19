<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Opportunities;

use App\Filament\Resources\LeadOpportunities\LeadOpportunitiesResource;
use App\Filament\Resources\LeadOpportunities\Pages\EditLeadOpportunity;
use App\Filament\Resources\LeadOpportunities\Schemas\LeadOpportunityForm;
use App\Livewire\Concerns\HasCurrentTeam;
use App\Models\Opportunity;
use Filament\Notifications\Notification;
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
final class EditOpportunity extends Component implements HasSchemas
{
    use HasCurrentTeam;
    use InteractsWithSchemas;

    public ?Opportunity $opportunity = null;

    /** @var array<string, mixed> */
    public ?array $data = [];

    #[Url(as: 'relation')]
    public ?string $activeRelationManager = null;

    public function mount(?Opportunity $opportunity = null): void
    {
        $this->opportunity = $opportunity;

        $this->form->fill($opportunity?->attributesToArray() ?? []);
    }

    public function rendering(): void
    {
        if (! $this->opportunity?->exists) {
            return;
        }

        $managers = $this->getRelationManagers();

        if (array_key_exists($this->activeRelationManager, $managers)) {
            return;
        }

        $this->activeRelationManager = ($key = array_key_first($managers)) !== null ? (string) $key : null;
    }

    public function form(Schema $schema): Schema
    {
        return LeadOpportunityForm::configure($schema)
            ->statePath('data')
            ->model($this->opportunity ?? Opportunity::class);
    }

    public function relationManagers(Schema $schema): Schema
    {
        if (! $this->opportunity?->exists) {
            return $schema->components([]);
        }

        $managers = $this->getRelationManagers();

        if ($managers === []) {
            return $schema->components([Group::make()->hidden()]);
        }

        $managerLivewireData = [
            'ownerRecord' => $this->opportunity,
            'pageClass' => EditLeadOpportunity::class,
        ];

        if (count($managers) > 1) {
            $tabs = collect($managers)
                ->map(function (string|RelationGroup|RelationManagerConfiguration $manager) use ($managerLivewireData): Tab {
                    $normalizedManagerClass = $this->normalizeRelationManagerClass($manager);

                    return $normalizedManagerClass::getTabComponent($this->opportunity, EditLeadOpportunity::class)
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

    public function save(): void
    {
        $data = $this->form->getState();

        if ($this->opportunity?->exists) {
            $this->opportunity->update($data);
            $message = __('Opportunity updated successfully.');
        } else {
            $this->opportunity = Opportunity::query()->create(array_merge($data, ['team_id' => $this->team->id]));
            $this->form->model($this->opportunity)->saveRelationships();
            $message = __('Opportunity created successfully.');
        }

        Notification::make()
            ->title($message)
            ->success()
            ->send();

        $this->redirect(route('dashboard.opportunities', ['team' => $this->team]), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.pages.opportunities.edit-opportunity');
    }

    /**
     * @return array<class-string<RelationManager> | RelationGroup | RelationManagerConfiguration>
     */
    private function getRelationManagers(): array
    {
        if (! $this->opportunity?->exists) {
            return [];
        }

        $managers = LeadOpportunitiesResource::getRelations();

        return array_filter(
            $managers,
            fn (string|RelationGroup|RelationManagerConfiguration $manager): bool => $this->normalizeRelationManagerClass($manager)::canViewForRecord($this->opportunity, EditLeadOpportunity::class),
        );
    }

    /**
     * @param  class-string<RelationManager> | RelationManagerConfiguration  $manager
     * @return class-string<RelationManager>
     */
    private function normalizeRelationManagerClass(string|RelationManagerConfiguration $manager): string
    {
        if ($manager instanceof RelationManagerConfiguration) {
            return $manager->relationManager;
        }

        return $manager;
    }
}
