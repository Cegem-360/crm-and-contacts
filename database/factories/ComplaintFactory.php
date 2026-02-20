<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\ComplaintSeverity;
use App\Enums\ComplaintStatus;
use App\Enums\ComplaintType;
use App\Models\Complaint;
use App\Models\Customer;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Complaint>
 */
final class ComplaintFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $reportedAt = fake()->dateTimeBetween('-3 months', 'now');
        $status = fake()->randomElement(ComplaintStatus::class);
        $resolvedAt = in_array($status, [ComplaintStatus::Resolved, ComplaintStatus::Closed]) ? fake()->dateTimeBetween($reportedAt, 'now') : null;

        $severity = fake()->randomElement(ComplaintSeverity::class);
        $slaHours = match ($severity) {
            ComplaintSeverity::Critical => 4,
            ComplaintSeverity::High => 24,
            ComplaintSeverity::Medium => 72,
            ComplaintSeverity::Low => 168,
        };

        return [
            'customer_id' => Customer::factory(),
            'order_id' => fake()->boolean(60) ? Order::factory() : null,
            'reported_by' => User::factory(),
            'assigned_to' => fake()->boolean(80) ? User::factory() : null,
            'complaint_number' => 'CMP-'.now()->format('Y').'-'.fake()->unique()->numerify('####'),
            'type' => fake()->randomElement(ComplaintType::class),
            'subject' => fake()->sentence(4),
            'title' => fake()->sentence(),
            'description' => fake()->paragraphs(2, true),
            'severity' => $severity,
            'status' => $status,
            'resolution' => $resolvedAt ? fake()->paragraph() : null,
            'reported_at' => $reportedAt,
            'resolved_at' => $resolvedAt,
            'sla_deadline_at' => (clone $reportedAt)->modify('+'.$slaHours.' hours'),
            'escalation_level' => fake()->numberBetween(0, 3),
        ];
    }
}
