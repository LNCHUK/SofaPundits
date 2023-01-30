<x-mail::message>
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
</x-mail::message>
