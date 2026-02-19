<?php

declare(strict_types=1);

namespace App\Providers;

use App\Filament\Commands\FileGenerators\Resources\ResourceClassGenerator;
use App\Http\Responses\LoginResponse;
use App\Http\Responses\RegistrationResponse;
use App\Models\BugReport;
use App\Models\Campaign;
use App\Models\Complaint;
use App\Models\Customer;
use App\Models\CustomerConsent;
use App\Models\CustomerContact;
use App\Models\EmailTemplate;
use App\Models\Interaction;
use App\Models\Invoice;
use App\Models\LeadScore;
use App\Models\Opportunity;
use App\Models\Order;
use App\Models\Product;
use App\Models\Quote;
use App\Models\Task;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Auth\Http\Responses\Contracts\LoginResponse as LoginResponseContract;
use Filament\Auth\Http\Responses\Contracts\RegistrationResponse as RegistrationResponseContract;
use Filament\Commands\FileGenerators\Resources\ResourceClassGenerator as BaseResourceClassGenerator;
use Filament\Forms\Components\Field;
use Filament\Infolists\Components\Entry;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Support\Facades\FilamentTimezone;
use Filament\Tables\Columns\Column;
use Filament\Tables\Filters\BaseFilter;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(LoginResponseContract::class, LoginResponse::class);
        $this->app->singleton(RegistrationResponseContract::class, RegistrationResponse::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        FilamentTimezone::set('Europe/Budapest');

        Field::configureUsing(static fn (Field $field): Field => $field->translateLabel());
        Column::configureUsing(static fn (Column $column): Column => $column->translateLabel());
        Entry::configureUsing(static fn (Entry $entry): Entry => $entry->translateLabel());
        Action::configureUsing(static fn (Action $action): Action => $action->translateLabel());
        BaseFilter::configureUsing(static fn (BaseFilter $filter): BaseFilter => $filter->translateLabel());
        Tab::configureUsing(static fn (Tab $tab): Tab => $tab->translateLabel());
        Step::configureUsing(static fn (Step $step): Step => $step->translateLabel());

        $this->app->bind(BaseResourceClassGenerator::class, ResourceClassGenerator::class);
        Relation::enforceMorphMap([
            'bug_report' => BugReport::class,
            'campaign' => Campaign::class,
            'complaint' => Complaint::class,
            'customer' => Customer::class,
            'customer_consent' => CustomerConsent::class,
            'customer_contact' => CustomerContact::class,
            'email_template' => EmailTemplate::class,
            'interaction' => Interaction::class,
            'invoice' => Invoice::class,
            'lead_score' => LeadScore::class,
            'opportunity' => Opportunity::class,
            'order' => Order::class,
            'product' => Product::class,
            'quote' => Quote::class,
            'task' => Task::class,
            'user' => User::class,
        ]);
    }
}
