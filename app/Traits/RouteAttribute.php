<?php

namespace App\Traits;

trait RouteAttribute{
	public function getActionsAttribute(){
		$id = $this->id;

		$action = "";

			$action .= 	"<a class='btn btn-info' data-toggle='tooltip' title='View Stations' onClick='stations($id)'>" .
					        "<i class='fa-duotone fa-route'></i> Stations" .
					    "</a>&nbsp;";
			$action .= 	"<a class='btn btn-warning' data-toggle='tooltip' title='View Fare Matrix' onClick='matrix($id)'>" .
					        "<i class='fa-light fa-grid'></i> Fare Matrix" .
					    "</a>&nbsp;";
			$action .= 	"<a class='btn btn-success' data-toggle='tooltip' title='View' onClick='view($id)'>" .
					        "<i class='fas fa-search'></i>" .
					    "</a>&nbsp;";
			$action .= 	"<a class='btn btn-danger' data-toggle='tooltip' title='Delete' onClick='del($id)'>" .
					        "<i class='fas fa-trash'></i>" .
					    "</a>&nbsp;";

		return $action;
	}
}