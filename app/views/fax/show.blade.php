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
                            @if($fax['direction'] == 'sent')
                                @if($fax['sent'])
                                <td><span class="label label-success">Sent</span> on {{ $fax['completed_at'] }}</td>
                                @elseif($fax['in_progress'])
                                <td><span class="label label-info"><i class="fa fa-spinner fa-spin"></i> In Progress</span></td>
                                @elseif($fax['message'])
								<td><span class="label label-danger">Error</span> {{ $fax['message'] }}</td>
                                @endif
                            @elseif($fax['direction'] == 'received')
								<td><span class="label label-success">Received</span> on {{ $fax['completed_at'] }}</td>
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
                                @if($fax['direction'] == 'sent')
                                <td>Recipient</td>
                                @else
                                <td>From Number</td>
                                @endif
                                <td>{{ $fax['number']['number'] }}</td>
                            </tr>

                            @if($fax['direction'] == 'received')
                            <tr>
                                <td>Incoming Number</td>
                                <td>{{ $fax['phone']['number'] }} - {{ $fax['phone']['description'] }}</td>
                            </tr>
                            @endif
                            
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
            <a href="{{ action('FaxController@download', [$fax['id'], 'p']) }}">
                <img class="faxImage" src="{{ action('FaxController@download', [$fax['id'], 'l']) }}"><br>
                <i class="fa fa-file-pdf-o"></i> Download PDF
            </a>
        </div>
    </div>
    
</div>
@stop