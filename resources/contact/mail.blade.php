@component('mail::message')

Bonjour,

Vous avez reçu un nouveau message en provenance du site [{{{ options_find('nom_site') }}}]({{{ config('app.url') }}}).

**Nom :** {{{ $contact->nom }}}<br />
**Email :** {{{ $contact->email }}}<br />
**Téléphone :** {{{ $contact->telephone }}}<br />
**Sujet :** {{{ $contact->subject }}}<br />
**Message :**<br />

{!!  nl2br(e($contact->msg)) !!}

@endcomponent
