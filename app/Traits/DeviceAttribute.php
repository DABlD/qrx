<?php

namespace App\Traits;

use App\Models\Ad;

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
			$action .= 	"<a class='btn btn-primary' data-toggle='tooltip' title='Ledger' onClick='ledger(`$this->device_id`)'>" .
					        "Ledger <i class='fa-light fa-table'></i>" .
					    "</a>&nbsp;";
			$action .= 	"<a class='btn btn-danger' data-toggle='tooltip' title='Delete' onClick='del($id)'>" .
					        "<i class='fas fa-trash'></i>" .
					    "</a>&nbsp;";

		return $action;
	}

	public function getAdsAttribute(){
		$ids = json_decode($this->ad_id);

		$ads = $ids ? Ad::whereIn('id', $ids)->get() : null;

		return $ads;
	}
}