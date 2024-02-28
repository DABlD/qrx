<?php

namespace App\Traits;

trait UserAttribute{
	public function getActionsAttribute(){
		$id = $this->id;
		$action = "";

		if($this->deleted_at){
			$action .= 	"<a class='btn btn-info' data-toggle='tooltip' title='Restore' onClick='res($id)'>" .
					        "<i class='fas fa-undo'></i>" .
					    "</a>&nbsp;";
		}
		else{
			$action = 	"<a class='btn btn-success' data-toggle='tooltip' title='View' onClick='view($id)'>" .
					        "<i class='fas fa-search'></i>" .
					    "</a>&nbsp;";

			if($this->role == "Company"){
				$action .= 	"<a class='btn btn-primary' data-toggle='tooltip' title='Themes' onClick='themes($id)'>" .
						        "<i class='fas fa-palette'></i>" .
						    "</a>&nbsp;";	
			}

			$action .= 	"<a class='btn btn-info' data-toggle='tooltip' title='Update Password' onClick='changePassword($id)'>" .
					        "<i class='fas fa-unlock'></i>" .
					    "</a>&nbsp;";

			$action .= 	"<a class='btn btn-danger' data-toggle='tooltip' title='Delete' onClick='del($id)'>" .
					        "<i class='fas fa-trash'></i>" .
					    "</a>&nbsp;";

		}


		return $action;
	}

	public function getFullNameAttribute(){
		return auth()->user()->fname . ' ' . auth()->user()->lname;
	}
}