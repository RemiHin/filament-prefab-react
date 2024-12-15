<x-mail::message>
# Hallo

Iemand heeft het contactformulier ingevuld, hieronder vind u de gegevens:

**Naam**\
{{ $formData['name'] }}

**E-mail**\
{{ $formData['email'] }}

**Bericht**\
{{ $formData['message'] }}

Bedankt,<br>
{{ config('app.name') }}
</x-mail::message>
