<table id="table" style="margin-top: 15px;">
	<thead>
 	<tr>
 		<th>Company</th>
 		<th>Policy Number</th>
 		<th>Amount</th>
 		<th>Expiry Date</th>
 	</tr>
 	</thead>

	@foreach($lists as $lk => $lv)		    		
	<tbody>
		 	<tr>
		 		<td> @if(!empty($lv->addition_info->insurance_company)) {{$lv->addition_info->insurance_company}} @endif</td>
		 		<td> @if(!empty($lv->addition_info->policy_number)) {{$lv->addition_info->policy_number}} @endif</td>
		 		<td> @if(!empty($lv->addition_info->insured_amount)) {{$lv->addition_info->insured_amount}} @endif</td>
		 		<td> @if(!empty($lv->addition_info->expiry_date)) {{date('d-M-Y',strtotime($lv->addition_info->expiry_date))}} @endif</td>
		 	</tr>
		 	</tbody>
    @endforeach
 </table>