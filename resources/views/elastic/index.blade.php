@extends('layout.main')

@section('content')
    <div>
        @if(!empty($items))
            @foreach($items as $item)
                <div>
                    <p> name: {{ $item['name'] ?? '-' }}</p>
                    <p> age: {{ $item['age'] ?? '-' }}</p>
                    <p> description: {{ $item['description'] ?? '-' }}</p>
                    <p> birthday: {{ $item['birthday'] ?? '-' }}</p>
                    @isset($item['id'])
                        <form action="{{ route('elastic.delete') }}" method="POST">
                            @method('DELETE')
                            @csrf
                            <input type="hidden" name="id" value="{{ $item['id'] }}">
                            <input type="submit" value="remove">
                        </form>
                    @endisset
                </div>
                <hr />
            @endforeach
        @endif
    </div>
    <form action="{{ route('elastic.store') }}" method="POST">
        @csrf
        <fieldset>
            <label for="name">name</label>
            <input type="text" name="name" id="name">
        </fieldset>
        <fieldset>
            <label for="name">age</label>
            <input type="number" name="age" id="age">
        </fieldset>
        <fieldset>
            <label for="name">description</label>
            <input type="text" name="description" id="description">
        </fieldset>
        <fieldset>
            <label for="name">birthday</label>
            <input type="date" name="birthday" id="birthday">
        </fieldset>
        <input type="submit" value="submit">
    </form>
@endsection
