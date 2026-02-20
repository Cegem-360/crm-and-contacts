<?php

declare(strict_types=1);

use App\Mail\QuoteEmail;
use App\Models\Customer;
use App\Models\EmailTemplate;
use App\Models\Quote;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Mail\Mailables\Attachment;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->customer = Customer::factory()->create();
    $this->quote = Quote::factory()->sent()->create([
        'customer_id' => $this->customer->id,
    ]);
    $this->pdfPath = tempnam(sys_get_temp_dir(), 'quote_test_');
    file_put_contents($this->pdfPath, 'fake pdf content');
});

afterEach(function (): void {
    if (file_exists($this->pdfPath)) {
        @unlink($this->pdfPath);
    }
});

it('attaches PDF to email', function (): void {
    $mail = new QuoteEmail($this->quote, $this->pdfPath);

    expect($mail->attachments())->toHaveCount(1);

    $attachment = $mail->attachments()[0];
    expect($attachment)->toBeInstanceOf(Attachment::class);
});

it('uses fallback subject when no EmailTemplate', function (): void {
    $mail = new QuoteEmail($this->quote, $this->pdfPath);
    $envelope = $mail->envelope();

    expect($envelope->subject)->toContain($this->quote->quote_number);
});

it('uses EmailTemplate subject when provided', function (): void {
    $emailTemplate = EmailTemplate::factory()->create([
        'subject' => 'Your quote {quote_number} is ready',
    ]);

    $mail = new QuoteEmail($this->quote, $this->pdfPath, $emailTemplate);
    $envelope = $mail->envelope();

    expect($envelope->subject)->toContain($this->quote->quote_number)
        ->and($envelope->subject)->not->toContain('{quote_number}');
});

it('replaces variables in email template', function (): void {
    $emailTemplate = EmailTemplate::factory()->create([
        'subject' => 'Quote {quote_number} for {customer_name}',
        'body' => '<p>Total: {total}</p><p>Valid until: {valid_until}</p>',
    ]);

    $mail = new QuoteEmail($this->quote, $this->pdfPath, $emailTemplate);
    $envelope = $mail->envelope();

    expect($envelope->subject)->toContain($this->quote->quote_number)
        ->and($envelope->subject)->toContain($this->customer->name);
});
