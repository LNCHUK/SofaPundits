<?php

namespace App\Http\Livewire;

use App\Models\Group;
use Illuminate\Support\Str;
use LivewireUI\Modal\ModalComponent;

class FindGroupModal extends ModalComponent
{
    public $groupKey = '';
    public $groups = null;

    public function render()
    {
        return view('livewire.find-group-modal');
    }

    public function findGroups()
    {
        // Check key is valid length
        if (Str::length($this->groupKey) !== config('parameters.group_key_length')) {
            return;
        }

        // Search for groups & fill array
        $this->groups = Group::query()
            ->whereNotIn('id', auth()->user()->groups->pluck('id'))
            ->where('key', $this->groupKey)
            ->get();
    }

    /**
     * Supported: 'sm', 'md', 'lg', 'xl', '2xl', '3xl', '4xl', '5xl', '6xl', '7xl'
     */
    public static function modalMaxWidth(): string
    {
        return '2xl';
    }
}
