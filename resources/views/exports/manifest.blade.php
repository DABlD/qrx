<table>
	<tr>
		<td style="font-weight: bold;">Name</td>
		<td style="font-weight: bold;">Ticket</td>
		<td style="font-weight: bold;">No</td>
		<td style="font-weight: bold;">Gender</td>
		<td style="font-weight: bold;">Mobile</td>
		<td style="font-weight: bold;">Origin</td>
		<td style="font-weight: bold;">Destination</td>
		<td style="font-weight: bold;">Vehicle</td>
		<td style="font-weight: bold;">Status</td>
		<td style="font-weight: bold;">Date Ticket Generated</td>
		<td style="font-weight: bold;">Date Embarked</td>
		<td style="font-weight: bold;">Date Disembarked</td>
	</tr>

	@foreach($datas as $data)
		<tr>
			<td>{{ $data->user->name }}</td>
			<td>{{ $data->ticket }}</td>
			<td>{{ $data->ticket_no }}</td>
			<td>{{ $data->user->gender }}</td>
			<td>{{ $data->user->mobile_number }}</td>
			<td>{{ $data->origin->name }}</td>
			<td>{{ $data->destination->name }}</td>
			<td>{{ $data->vehicle ? $data->vehicle->vehicle_id : "---" }}</td>
			<td>{{ $data->status }}</td>
			<td>{{ now()->parse($data->created_at)->format("Y-m-d h:i A") }}</td>
			<td>{{ $data->embarked_date ? now()->parse($data->embarked_date)->format("Y-m-d h:i:s A") : "---" }}</td>
			<td>{{ $data->status == "Disembarked" ? now()->parse($data->updated_at)->format("Y-m-d h:i:s A") : "---" }}</td>
		</tr>
	@endforeach
</table>