{{-- enumerate the values in an array  --}}
@php
    $value = data_get($entry, $column['name']);

    // the value should be an array wether or not attribute casting is used
    if (!is_array($value)) {
        $value = json_decode($value, true);
    }
@endphp

<span>
    @php
        foreach($value as $info){
            echo $info['name'] . '<br>';
}
    @endphp
</span>
