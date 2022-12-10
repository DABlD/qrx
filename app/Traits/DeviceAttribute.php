<?php

namespace App\Traits;

trait DeviceAttribute{
	public function getActionsAttribute(){
		$id = $this->id;
		$ads = $this->ad_id;
		// $rid = $this->route_id;

		$action = "";

			$action .= 	"<a class='btn btn-success' data-toggle='tooltip' title='View' onClick='view($id)'>" .
					        "<i class='fas fa-search'></i>" .
					    "</a>&nbsp;";
			$action .= 	"<a class='btn btn-info' data-toggle='tooltip' title='Ads' onClick='ads($id, $ads)'>" .
					        "<i class='fa-light fa-rectangle-ad'></i>" .
					    "</a>&nbsp;";
			$action .= 	"<a class='btn btn-danger' data-toggle='tooltip' title='Delete' onClick='del($id)'>" .
					        "<i class='fas fa-trash'></i>" .
					    "</a>&nbsp;";

		return $action;
	}
}