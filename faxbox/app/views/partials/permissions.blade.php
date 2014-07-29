<div class="row">
    <div class="col-md-12">
        <h5>General Permissions</h5>
        <div class="form-group">
            @foreach($permissions['static'] as $p)
            <label class="checkbox">
                {{ Form::hidden("permissions[".$p['id']."]", 0) }}
                {{ Form::checkbox("permissions[".$p['id']."]", 1, isset($p['checked'])) }} {{ $p['name'] }} <span style="font-style: italic; color: #b7b7b7; font-weight: 200">{{ $p['description'] }}</span>
            </label>
            @endforeach
        </div>
    </div>
</div>

@foreach($permissions['dynamic'] as $resource)
<div class="row">
    <div class="col-md-12">
        <h5>{{ $resource['name'] }} Permissions</h5>
        <div class="form-group">
            @foreach($resource['permissions'] as $p)
            <label class="checkbox">
                {{ Form::hidden("permissions[".$p['id']."]", 0) }}
                {{ Form::checkbox("permissions[".$p['id']."]", 1, isset($p['checked'])) }} {{ $p['name'] }} <span style="font-style: italic; color: #b7b7b7; font-weight: 200">{{ $p['description'] }}</span>
            </label>
            @endforeach
        </div>
    </div>
</div>
@endforeach