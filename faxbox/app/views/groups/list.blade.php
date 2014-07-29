@section('content')
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">View Groups</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>

    <div class="row">
        <div class="col-lg-12">
            <!-- .panel-heading -->
            <div class="panel-body">
                <div class="panel-group" id="accordion">
                    
                    @foreach($groups as $group)
                    
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse"
                                   data-parent="#accordion" href="#collapse{{ $group['id'] }}">{{ $group['name'] }}</a>
                            </h4>
                        </div>
                        <div id="collapse{{ $group['id'] }}" class="panel-collapse collapse">
                            <div class="panel-body">
                                {{ Form::open(['action' => ['GroupController@update', $group['id']], 'method' => 'PUT']) }}
                                <div class="row">
                                    <div class="col-md-4 ">
                                        <div class="form-group {{ ($errors->has('name')) ? 'has-error' : '' }}">
                                            {{ Form::text('name', $group['name'], array('class' => 'form-control', 'placeholder' => trans('groups.name'))) }}
                                            {{ ($errors->has('name') ? $errors->first('name') : '') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        @include("partials.permissions", ['permissions' => $group['permissions']])
                                    </div>
                                    <div class="col-md-6">
                                        <h5>Users</h5>
                                    </div>
                                </div>
                                {{ Form::hidden('id', $group['id'], array('class' => 'form-control', 'placeholder' => trans('groups.name'))) }}
                                {{ Form::submit(trans('groups.update'), array('class' => 'btn btn-primary pull-left')) }}

                                {{ Form::close() }}
                                
                                {{ Form::open(['action' => ['GroupController@destroy', $group['id']], 'method' => 'DELETE']) }}
                                {{ Form::submit(trans('groups.delete'), array('class' => 'btn btn-sm btn-danger pull-right')) }}
                                {{ Form::close() }}
                            </div>
                        </div>
                    </div>
                    
                    @endforeach
                    
                </div>
            </div>
            <!-- .panel-body -->
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
</div>
@stop