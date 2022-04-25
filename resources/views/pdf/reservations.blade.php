Rezervaije za {{$today}}
@forelse ($reservations as $row)
    $row->name    
@empty
    Više sreće drugi put
@endforelse
{{ $timestamp }}