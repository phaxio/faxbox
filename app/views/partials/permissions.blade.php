<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label>General Permissions</label>
        
            @foreach($permissions['static'] as $p)
            <div class="checkbox"> 
                <label>
                    {{ Form::select("permissions[".$p['id']."]", [0 => '', 1 => 'Allow', -1 => 'Deny'], ($p['id'] == 'send_fax' || $p['id'] == 'purchase_numbers') ? 1 : -1 ) }} {{ $p['name'] }} <span style="font-style: italic; color: #b7b7b7; font-weight: 200">{{ $p['description'] }}</span>
                </label>
            </div>
            @endforeach
        </div>
    </div>
</div>

