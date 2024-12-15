<x-mail::message>
# Hallo {{ $formData['name'] }},

Bedankt voor het invullen van ons contactformulier. Wij zullen zo spoedig mogelijk contact met u opnemen.

Bedankt,<br>
{{ config('app.name') }}
</x-mail::message>
