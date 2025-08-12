<?php

class Utility
{

	public static function number_to_letter($nombre)
	{
		$cel = new ChiffreEnLettre();
		return $cel->getString($nombre);
	}
}