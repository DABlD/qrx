<?php

namespace App\Traits;

trait LoanAttribute{
	public function getActionsAttribute(){
		$id = $this->id;
		$action = "";

		$action = 	"<a class='btn btn-success' data-toggle='tooltip' title='View' onClick='view($id)'>" .
				        "<i class='fas fa-search'></i>" .
				    "</a>&nbsp;";
		// $action .= 	"<a class='btn btn-danger' data-toggle='tooltip' title='Delete' onClick='del($id)'>" .
		// 		        "<i class='fas fa-trash'></i>" .
		// 		    "</a>&nbsp;";
		
		if($this->status == "Approved"){
			$action = 	"<a class='btn btn-info' data-toggle='tooltip' title='Disburse' onClick='disburse($id)'>" .
					        "<i class='fas fa-hand-holding-circle-dollar'></i>" .
					    "</a>&nbsp;";
		}

		return $action;
	}
}