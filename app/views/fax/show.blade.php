@section('content')
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <small><a href="{{ action('FaxController@index') }}"><< Back to List Faxes</a></small>
            <h1 class="page-header">Fax Details #{{ $fax['id'] }}</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>

    @if(!$fax['in_progress'] && $fax['status'])
    <div class="row">
        <div class="alert alert-danger">
            {{ $fax['message'] }}
        </div>
    </div>
    @endif
    
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Details
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                            <tr>
                                <td>Status</td>
                                @if($fax['sent'])
                                <td><span class="label label-success">Sent</span></tr>
                                @elseif($fax['in_progress'])
                                <td><span class="label label-info"><i class="fa fa-spinner fa-spin"></i> In Progress</span></td>
                                @endif
                            </tr>

                            <tr>
                                <td>Direction</td>
                                <td>{{ ucwords($fax['direction']) }}</td>
                            </tr>
                            
                            <tr>
                                <td>Pages</td>
                                <td>{{ $fax['pages'] }}</td>
                            </tr>
                            
                            <tr>
                                <td>Number</td>
                                <td>{{ $fax['recipient']['number'] ?: $fax['phone']['number'] }}</td>
                            </tr>
                            
                            </tbody>
                        </table>
                    </div>
                    <!-- /.table-responsive -->
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
        <div class="col-md-6 text-right">
            <img src="{{ action('FaxController@download', [$fax['id'], 'l']) }}"><br>
            <a href="{{ action('FaxController@download', [$fax['id'], 'p']) }}" target="_blank"><i class="fa fa-file-pdf-o"></i> Download PDF</a>
        </div>
    </div>
    
</div>
@stop