@section('content')
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">View Users</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="dataTables-fax">
                    <thead>
                    <tr>
                        <th></th>
                        <th>Email</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                    <tr class="odd gradeX">
                        <td class="text-center"><a href="{{ action('UserController@edit', ['id' => $user['id']]) }}"><i class="fa fa-edit"> Edit</i></a></td>
                        <td>{{ $user['email'] }}</td>
                        <td>{{ $user['first_name'] }} {{ $user['last_name'] }}</td>
                        
                            @if($user['activated'])
                        <td><span class='label label-success'>Activated</span></td>
                        <td class="text-center"><a href="{{ action('UserController@deactivate', ['id' => $user['id']]) }}">Deactivate</a></td>
                            @else
                        <td>
                            {{ Form::open(['action' => 'UserController@resend', 'method' => 'post']) }}
                            <span class='label label-danger'>Inactive</span>
                            {{ Form::hidden('email', $user['email']) }}
                            {{ Form::submit(trans('users.resend'), array('class' => 'btn btn-link')) }}
                            {{ Form::close() }}
                        </td>
                        <td class="text-center"></td>
                            @endif
                        
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.table-responsive -->
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
</div>
@stop

@section('scripts')
<script src="{{ asset('js/plugins/dataTables/jquery.dataTables.js') }}"></script>
<script src="{{ asset('js/plugins/dataTables/dataTables.bootstrap.js') }}"></script>

<script>
    $(document).ready(function() {
        $('#dataTables-fax').dataTable({
            "order": [[ 1, "asc" ]],
            "columnDefs": [
                { "orderable": false, "targets": [0, 4] },
                { "searchable": false, "targets": [0, 4] }
            ]
        });
    });
</script>
@stop