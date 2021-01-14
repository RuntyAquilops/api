<?php

header('Content-Type: application/json; charset:UTF-8');

class Errors {
	public function err($message)
	{
		$text = array(
			"status" => "error",
			"message" =>  $message
		);
		die(json_encode($text));
	}
}
?>
