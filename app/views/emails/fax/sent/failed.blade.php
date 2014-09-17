There was an error while trying to send your fax:


{{ $fax['number']['number'] }}

{{ $fax['message'] }}


{{ action('FaxController@show', ['id' => $fax['id']]) }}