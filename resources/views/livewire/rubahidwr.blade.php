<div>
    rubahID
    <button wire:click="rubah" class="btn btn-primary">Process</button>

    <ul>
        @for ($i = 0; $i < count($idFromArr); $i++)
            <li>{{ $idFromArr[$i] }} to {{ $idToArr[$i] }}</li>
        @endfor
    </ul>
</div>
