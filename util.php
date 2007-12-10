<?php

class utility{
	public static function count_r($mixed){
		$totalCount = 0;
		
		foreach($mixed as $temp){
			if(is_array($temp)){
				$totalCount += utility::count_r($temp);
			}
			else{
				$totalCount += 1;
			}
		}
		return $totalCount;
	}
	
	public static function addArrays($mixed){
		$summedArray = array();
		
		foreach($mixed as $temp){
			$a=0;
			if(is_array($temp)){
				foreach($temp as $tempSubArray){
					$summedArray[$a] += $tempSubArray;
					$a++;
				}
			}
			else{
				$summedArray[$a] += $temp;
			}
		}
		return $summedArray;
	}
	public static function getScaledArray($unscaledArray, $scalar){
		$scaledArray = array();
		
		foreach($unscaledArray as $temp){
			if(is_array($temp)){
				array_push($scaledArray, utility::getScaledArray($temp, $scalar));
			}
			else{
				array_push($scaledArray, round($temp * $scalar, 2));
			}
		}
		return $scaledArray;
	}
	
	public static function getMaxCountOfArray($ArrayToCheck){
		$maxValue = count($ArrayToCheck);
		
		foreach($ArrayToCheck as $temp){
			if(is_array($temp)){
				$maxValue = max($maxValue, utility::getMaxCountOfArray($temp));
			}
		}
		return $maxValue;
		
	}

	public static function getMaxOfArray($ArrayToCheck){
		$maxValue = 1;
		
		foreach($ArrayToCheck as $temp){
			if(is_array($temp)){
				$maxValue = max($maxValue, utility::getMaxOfArray($temp));
			}
			else{
				$maxValue = max($maxValue, $temp);
			}
		}
		return $maxValue;
	}
	
}

?>