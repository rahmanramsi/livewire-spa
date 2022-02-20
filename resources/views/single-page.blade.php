<div>
    @if ($alias)
        @livewire($alias, $parameters, key($alias . md5(json_encode($parameters))))
    @endif
</div>
