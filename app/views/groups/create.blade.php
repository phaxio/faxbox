@section('content')
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Create Group</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>

    <div class="row">
        {{ Form::open(array('action' => 'GroupController@store')) }}

        <div class="col-md-4 ">
            <div class="form-group {{ ($errors->has('name')) ? 'has-error' : '' }}">
                {{ Form::text('name', null, array('class' => 'form-control', 'placeholder' => trans('groups.name'))) }}
                {{ ($errors->has('name') ? $errors->first('name') : '') }}
            </div>
        </div>
    </div>

    @include('partials.permissions')

    {{ Form::submit(trans('groups.create'), array('class' => 'btn btn-primary')) }}

    {{ Form::close() }}
</div>
@stop