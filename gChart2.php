<?php
require_once('util.php');
	class gChart{
		private $baseUrl = "http://chart.apis.google.com/chart?";
		protected $scalar = 1;
		
		public $types = array ("lc","lxy","bhs","bvs","bhg","bvg","p","p3","v","s");
		public $type = 1;
		public $dataEncodingType = "t";
		public $values = Array();
		protected $scaledValues = Array();
		public $valueLabels;
		public $dataColors;
		public $width = 200; //default
		public $height = 200; //default
		private $title;
		
		
		public function setTitle($newTitle){
			$this->title = str_replace("\r\n", "|", $newTitle);
			$this->title = str_replace(" ", "+", $this->title);
		}
		
		
		protected function encodeData($data, $encoding, $separator){
			switch ($this->dataEncodingType){
				case "s":
					return $this->simpleEncodeData();
				case "e":
					return $this->extendedEncodeData();
				default:{
					$retStr = $this->textEncodeData($data, $separator, "|");
					$retStr = trim($retStr, "|");
					return $retStr;					
					}
			}
		}
		
		private function textEncodeData($data, $separator, $datasetSeparator){
			$retStr = "";
			if(!is_array($data))
				return $data;
			foreach($data as $currValue){
				if(is_array($currValue))
					$retStr .= $this->textEncodeData($currValue, $separator, $datasetSeparator);
				else
					$retStr .= $currValue.$separator;
			}
				
			$retStr = trim($retStr, $separator);
			$retStr .= $datasetSeparator;
			return $retStr;
		}
		
		public function addDataSet($dataArray){
			array_push($this->values, $dataArray);
		}
		public function clearDataSets(){
			$this->values = Array();
		}
		
		private function simpleEncodeData(){
			return "";
		}
		
		private function extendedEncodeData(){
			return "";
		}
		
		protected function prepForUrl(){
			$this->scaleValues();
		}
		protected function getDataSetString(){
			return "&chd=".$this->dataEncodingType.":".$this->encodeData($this->scaledValues,"" ,",");
		}
		
		protected function concatUrl(){
			$fullUrl .= $this->baseUrl;
			$fullUrl .= "cht=".$this->types[$this->type];
			$fullUrl .= "&chs=".$this->width."x".$this->height;
			
			$fullUrl .= $this->getDataSetString();
			if(isset($this->valueLabels))
				$fullUrl .= "&chdl=".$this->encodeData($this->getApplicableLabels($this->valueLabels),"", "|");
			$fullUrl .= "&chco=".$this->encodeData($this->dataColors,"", ",");
			if(isset($this->title))
				$fullUrl .= "&chtt=".$this->title;
			
			return $fullUrl;
		}
		protected function getApplicableLabels($labels){
			$trimmedValueLabels = $labels;
			return array_splice($trimmedValueLabels, 0, count($this->values));
		}
		public function getUrl(){
			$this->prepForUrl();
			return $this->concatUrl();
		}
		
		public function printIt(){
			print "<br>Scalar:$this->scalar <br>";
			print "<br>Values:".print_r($this->values) ."<br>";
			print "<br>Values:".print_r($this->scaledValues) ."<br>";
		}
		
		protected function scaleValues(){
			$this->setScalar();
			$this->scaledValues = utility::getScaledArray($this->values, $this->scalar);
		}


		function setScalar(){
			$maxValue = 100;
			$maxValue = max($maxValue, utility::getMaxOfArray($this->values));
			if($maxValue <100)
				$this->scalar = 1;
			else
				$this->scalar = 100/$maxValue;
		}
	}

	class gPieChart extends gChart{
		function __construct(){
			$this->type = 6;
			$this->width = $this->height * 1.5;
		}
		function setScalar(){
			return 1;
		}
		
		public function getUrl(){
			$retStr = parent::getUrl();
			$retStr .= "&chl=".$this->encodeData($this->valueLabels,"", "|");
			return $retStr;
		}
		private function getScaledArray($unscaledArray, $scalar){
			return $unscaledArray;		
		}
		public function set3D($is3d){
			if($is3d){
				$this->type = 7;
				$this->width = $this->height * 2;
			}
			else{
				$this->type = 6;
				$this->width = $this->height * 1.5;
			}
		}
	}

	class gLineChart extends gChart{
		function __construct(){
			$this->type = 0;
		}
	}
	
	class gBarChart extends gChart{
		public $barWidth;
		public $groupSpacerWidth = 1;
		protected $totalBars = 1;
		private $isHoriz = false;
		public function getUrl(){
			$this->scaleValues();
			$retStr = parent::concatUrl();
			$this->setBarWidth();
			$retStr .= "&chbh=$this->barWidth,$this->groupSpacerWidth";
			return $retStr;
		}
		
		function setBarCount(){
			$this->totalBars = utility::count_r($this->values);
		}
		
		private function setBarWidth(){
			if(isset($this->barWidth))
				return;
			$this->setBarCount();
			$totalGroups = utility::getMaxCountOfArray($this->values);
			$chartSize = $this->width - 50;
			if($this->isHoriz)
				$chartSize = $this->height - 50;
			$chartSize -= $totalGroups * $this->groupSpacerWidth;
			$this->barWidth = round($chartSize/$this->totalBars);
		}
		
	}
	class gGroupedBarChart extends gBarChart{
		function __construct(){
			$this->type = 5;
		}
		
		public function setHorizontal($isHorizontal){
			if($isHorizontal){
				$this->type = 4;
			}
			else{
				$this->type = 5;
			}
			$this->isHoriz = $isHorizontal;
		}

	}
	class gStackedBarChart extends gBarChart{
		function __construct(){
			$this->type = 3;
		}

		function setBarCount(){
			$this->totalBars = utility::getMaxCountOfArray($this->values);
		}
		
		public function setHorizontal($isHorizontal){
			if($isHorizontal){
				$this->type = 2;
			}
			else{
				$this->type = 3;
			}
			$this->isHoriz = $isHorizontal;
		}

		protected function scaleValues(){
			$this->setScalar();
			$this->scaledValues = utility::getScaledArray($this->values, $this->scalar);
		}
		
		function setScalar(){
			$maxValue = 100;
			$maxValue = max($maxValue, utility::getMaxOfArray(utility::addArrays($this->values)));
			if($maxValue <100)
				$this->scalar = 1;
			else
				$this->scalar = 100/$maxValue;
		}
		
	}
	
	class gVennDiagram extends gChart{
		private $intersections = array(0,0,0,0);
		public function addIntersections($mixed){
			$this->intersections = $mixed;
		}
		function __construct(){
			$this->type = 8;
		}
		
		public function getUrl(){
			$retStr = parent::getUrl();
			$retStr .= "&chl=".$this->encodeData($this->valueLabels,"", "|");
			return $retStr;
		}
		protected function getDataSetString(){
			$fullDataSet = array_splice($this->scaledValues[0], 0, 3);
			$scaledIntersections = utility::getScaledArray($this->intersections, $this->scalar);
			foreach($scaledIntersections as $temp){
				array_push($fullDataSet, $temp);
			}
			$fullDataSet = array_splice($fullDataSet, 0, 7);
			while(count($fullDataSet)<7){
				array_push($fullDataSet, 0);
			}
			
			return "&chd=".$this->dataEncodingType.":".$this->encodeData($fullDataSet,"" ,",");
		}
	}

	
?>
