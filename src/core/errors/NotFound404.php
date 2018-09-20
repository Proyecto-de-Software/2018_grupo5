<?php
class NotFound404Exception extends Exception {

	public function errorMessage() {
		$errorMsg = "Url dispatcher not found didn't resource.";
	}
	
}
