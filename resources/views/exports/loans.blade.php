<table>
	<tr>
		<td style="font-weight: bold;">ID</td>
		<td style="font-weight: bold;">Name</td>
		<td style="font-weight: bold;">Contract #</td>
		<td style="font-weight: bold;">Type</td>
		<td style="font-weight: bold;">Amount</td>
		<td style="font-weight: bold;">Rate</td>
		<td style="font-weight: bold;">Paid Months</td>
		<td style="font-weight: bold;">Monthly Payment</td>
		<td style="font-weight: bold;">Total Payment</td>
		<td style="font-weight: bold;">Revenue</td>
		<td style="font-weight: bold;">Status</td>
		<td style="font-weight: bold;">Created At</td>
	</tr>

	@foreach($datas as $data)
		<tr>
			<td>{{ $data->id }}</td>
			<td>{{ $data->branch->user->fname }}</td>
			<td>{{ $data->contract_no }}</td>
			<td>{{ $data->type }}</td>

			<td>{{ "₱" . number_format($data->amount, 2, '.') }}</td>
			<td>{{ $data->percent . "%" }}</td>
			<td>{{ $data->paid_months . ' / ' . $data->months }}</td>
			<td>{{ $data->amount }}</td>
			<td>{{ $data->amount }}</td>
			<td>{{ $data->amount }}</td>
			<td>{{ $data->status }}</td>
			<td>{{ now()->parse($data->created_at)->format('M d, Y H:i:s') }}</td>
		</tr>
	@endforeach
</table>