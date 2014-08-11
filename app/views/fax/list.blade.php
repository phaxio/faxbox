@section('content')
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">View Faxes</h1>
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
                            <th>Direction</th>
                            <th>Recipient/From</th>
                            <th>Pages</th>
                            <th>Incoming Phone</th>
                            <th>Status</th>
                            <th>Completed</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($faxes as $fax)
                        <tr class="odd gradeX">
                            <td><a href="{{ action('FaxController@show', $fax['id'])}}"><i class="fa fa-file-text-o"></i> {{ $fax['id'] }}</a></td>
                            <td>{{ $fax['direction'] == 'received' ? '<span class="label label-info"><i class="fa fa-arrow-circle-down"></i>' : '<span class="label label-primary"><i class="fa fa-arrow-circle-up"></i>'}} {{ $fax['direction'] }}</span></td>
                            <td>{{ $fax['number']['number'] }}</td>
                            <td>{{ $fax['pages'] }}</td>
                            <td>{{ $fax['phone']['number'] ?: '-----'  }}</td>
                            <td>
                            @if($fax['in_progress'])
                            <span class="label label-info"><i class="fa fa-spinner fa-spin"></i> In Progress</span>
                            @elseif($fax['sent'])
                            <span class="label label-success">Completed</span>
                            @elseif(!$fax['in_progress'] && !$fax['sent'])
                            <span class="label label-danger">Error</span>
                            <small>{{ $fax['message'] }}</small>
                            @endif
                            </td>
                            <td>{{ $fax['completed_at'] ?: '-----' }}</td>
                            <td class="text-center"><a href="{{ action('FaxController@download', [$fax['id'], 'p'])}}"><i class="fa fa-file-pdf-o"></i></a></td>
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
            "order": [[ 0, "desc" ]],
            "columnDefs": [
                { "orderable": false, "targets": 7 },
                { "searchable": false, "targets": 7 }
            ],
            "iDisplayLength": 25,
            "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
        });
    });
</script>
@stop