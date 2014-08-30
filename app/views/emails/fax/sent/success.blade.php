Your fax to the number {{ $fax['number']['number'] }} was successfully sent on {{ $fax['completed_at'] }}.
You can view the fax using this link:

{{ action('FaxController@show', ['id' => $fax['id']]) }}