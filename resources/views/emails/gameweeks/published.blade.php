<x-mail::message>
<x-slot:preheader>
Time to put your predictions in, good luck!
</x-slot:preheader>

# New Gameweek Published

A new gameweek was just published in the group "**{{ $gameweek->group->name }}**", and
is ready for you to enter your predictions.

You have until **{{ $firstFixture->kick_off->format('h:ia') }}** on
**{{ $firstFixture->kick_off->format('jS F Y') }}** to enter your predictions. Please
log in to your Sofa Pundits account, or use the link below to view the fixtures in the Gameweek.

<x-mail::button :url="route('gameweeks.show', [$gameweek->group, $gameweek])">
View Gameweek
</x-mail::button>

Good luck!<br>
{{ config('app.name') }} Team

<x-slot:subcopy>
You are receiving this email because you have a SofaPundits account that is currently part of
an active group. If you do not want to receive these emails, you can manage your notification
settings in your SofaPundits account, or click here to unsubscribe from future notifications.
</x-slot:subcopy>

</x-mail::message>
