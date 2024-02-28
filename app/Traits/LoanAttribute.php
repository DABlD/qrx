<?php

namespace App\Traits;

trait LoanAttribute{
	public function getActionsAttribute(){
		$id = $this->id;
		$action = "";

		$action = 	"<a class='btn btn-success' data-toggle='tooltip' title='View' onClick='view($id)'>" .
				        "<i class='fas fa-search'></i>" .
				    "</a>&nbsp;" . 
				    "<a class='btn btn-primary' data-toggle='tooltip' title='Matrix' onClick='matrix($id)'>" .
				        "<i class='fas fa-table-list'></i>" .
				    "</a>&nbsp;";
		// $action .= 	"<a class='btn btn-danger' data-toggle='tooltip' title='Delete' onClick='del($id)'>" .
		// 		        "<i class='fas fa-trash'></i>" .
		// 		    "</a>&nbsp;";
		
		if($this->status == "Approved"){
			$action .= 	"<a class='btn btn-info' data-toggle='tooltip' title='Disburse' onClick='disburse($id)'>" .
					        "<i class='fas fa-hand-holding-circle-dollar'></i>" .
					    "</a>&nbsp;";
		}

		if($this->status == "For Payment"){
			$action .= 	"<a class='btn btn-warning' data-toggle='tooltip' title='Pay' onClick='pay($id)'>" .
					        "<i class='fas fa-hands-holding-dollar'></i>" .
					    "</a>&nbsp;";
		}

		if(in_array($this->status, ["For Payment", "Overdue", "Paid"])){
			$action .= 	"<a class='btn btn-info' data-toggle='tooltip' title='Payments' onClick='payments($id)'>" .
					        "<i class='fas fa-file-invoice-dollar'></i>" .
					    "</a>&nbsp;";
		}

		if(in_array($this->status, ['Applied', 'Disapproved', 'Overdue'])){
			$action .= 	"<a class='btn btn-danger' data-toggle='tooltip' title='Payments' onClick='del($id)'>" .
					        "<i class='fas fa-trash'></i>" .
					    "</a>&nbsp;";
		}

		return $action;
	}
}