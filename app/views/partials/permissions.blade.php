<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label>General Permissions</label>
        
            @foreach($permissions['static'] as $p)
            <div class="checkbox"> 
                <label>
                    {{ Form::select("permissions[".$p['id']."]", [0 => 'Inherit', 1 => 'Allow', -1 => 'Deny'], -1) }} {{ $p['name'] }} <span style="font-style: italic; color: #b7b7b7; font-weight: 200">{{ $p['description'] }}</span>
                </label>
            </div>
            @endforeach
        </div>
    </div>
</div>

@foreach($permissions['dynamic'] as $resource)
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label>{{ $resource['name'] }} Permissions</label>
            @foreach($resource['permissions'] as $p)
            <div class="checkbox">
                <label>
                    {{ Form::select("permissions[".$p['id']."]", [0 => 'Inherit', 1 => 'Allow', -1 => 'Deny'], -1) }} {{ $p['name'] }}
                </label>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endforeach