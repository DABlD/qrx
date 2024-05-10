<table>
	<tr>
		<td style="font-weight: bold;">ID</td>
		<td style="font-weight: bold;">Contract #</td>
		<td style="font-weight: bold;">Type</td>
		<td style="font-weight: bold;">Amount</td>
		<td style="font-weight: bold;">Ref #</td>
		<td style="font-weight: bold;">Payment Channel</td>
		<td style="font-weight: bold;">Date</td>
	</tr>

	@foreach($datas as $data)
		<tr>
			<td>{{ $data->id }}</td>
			<td>{{ isset($data->loan) ? $data->loan->contract_no : "-" }}</td>
			<td>{{ $data->type }}</td>
			<td>₱{{ number_format($data->amount, 2, '.') }}</td>
			<td>{{ $data->trx_number }}</td>
			<td>{{ $data->payment_channel }}</td>
			<td>{{ now()->parse($data->payment_date)->format('M d, Y') }}</td>
		</tr>
	@endforeach
</table>