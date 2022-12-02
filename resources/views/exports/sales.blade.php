<table>
	<tr>
		<td style="font-weight: bold;">ID</td>
		<td style="font-weight: bold;">Ticket</td>
		<td style="font-weight: bold;">No</td>
		<td style="font-weight: bold;">Amount</td>
		<td style="font-weight: bold;">Status</td>
		<td style="font-weight: bold;">Origin</td>
		<td style="font-weight: bold;">Destination</td>
		<td style="font-weight: bold;">Date Created</td>
	</tr>

	@foreach($datas as $data)
		<tr>
			<td>{{ $data->id }}</td>
			<td>{{ $data->ticket }}</td>
			<td>{{ $data->ticket_no }}</td>
			<td>{{ $data->amount }}</td>
			<td>{{ $data->status }}</td>
			<td>{{ $data->origin->name }}</td>
			<td>{{ $data->destination->name }}</td>
			<td>{{ now()->parse($data->created_at)->format("Y-m-d h:i A") }}</td>
		</tr>
	@endforeach
</table>