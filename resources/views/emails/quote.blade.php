<x-mail::message>
# Árajánlat / Quote: {{ $quote->quote_number }}

Tisztelt {{ $quote->customer?->name }}!

Mellékelten küldjük árajánlatunkat.

**Ajánlat száma / Quote Number:** {{ $quote->quote_number }}
**Összeg / Total:** {{ number_format((float) $quote->total, 0, ',', ' ') }} Ft
**Érvényesség / Valid Until:** {{ $quote->valid_until?->format('Y-m-d') }}

@if($quote->view_token)
<x-mail::button :url="route('quotes.public-view', $quote->view_token)">
Árajánlat megtekintése / View Quote
</x-mail::button>
@endif

Üdvözlettel / Best regards,
{{ config('app.name') }}
</x-mail::message>
