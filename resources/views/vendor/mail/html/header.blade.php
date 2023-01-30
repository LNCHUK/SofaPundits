@props(['url'])
<tr>
<td class="header">
<a data-pm-no-track href="{{ $url }}" style="display: inline-block;">
{{ $slot }}
</a>
</td>
</tr>
