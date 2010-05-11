<?php
/**
 * gChartPhp, a php wrapper for  the Google Chart Tools / Image Charts (aka Chart API) {@link http://code.google.com/apis/charttools/}
 *
 * @version 0.3
 */
	/**
	 * Requires utility class in util.php file
	 */
	require_once('util.php');
	/**
	 * gBackground class
	 *
	 */
        class gBackground{
                public $colors = array("ffffff");
                public $fillType = 0;
                protected $fillTypes = array ("s", "lg", "ls");
                public $isChart = false;
                public function toArray(){
                        $retArray = array();
                        if($this->isChart)
                                array_push($retArray, "c");
                        else
                                array_push($retArray, "bg");
                        array_push($retArray,$this->fillTypes[$this->fillType]);
                        array_push($retArray,$this->colors[0]);
                        return $retArray;                      
                }
        }
	/**
	 * Main class
	 *
	 * This is the mainframe of the wrapper
	 *
	 * @version 0.3
	 */
        class gChart{
		/**
		 * @var string
		 * @usedby gChat::concactUrl()
		 */
                private $baseUrl = "chart.apis.google.com/chart?";
		/**
		 * @var integer
		 */
                protected $scalar = 1;
               	/**
		 * @var array
		 */
                public $types = array ("lc","lxy","bhs","bvs","bhg","bvg","p","p3","v","s","tx","qr");
                public $type = 1;
                public $dataEncodingType = "t";
               	/**
		 * @var array
		 */
                public $values = Array();
                protected $scaledValues = Array();
               	/**
		 * @var array
		 */
                public $valueLabels;
               	/**
		 * @var array
		 */
                public $xAxisLabels;
               	/**
		 * @var array
		 */
                public $showAxis;
               	/**
		 * @var array
		 */
                public $dataColors;
               	/**
		 * @var array
		 */
                private $axisRanges = Array();
		/**
                 * Width of the chart. Default value 200
		 * @var integer
		 * @todo Make it private
		 */
                public $width = 200; //default
		/**
                 * Height of the chart. Default value 200
		 * @var integer
		 * @todo Make it private
		 */
                public $height = 200; //default
                /**
                 * @var string
                 */
                private $title;
                /**
                 * @var integer
                 */
		private $serverNum;
               
                public $backgrounds;
                /**
		 * This function sets the title of the chart
		 * @param string Title string
		 */ 
	        public function setTitle($newTitle){
                        $this->title = str_replace("\r\n", "|", $newTitle);
                        $this->title = str_replace(" ", "+", $this->title);
                }
                /**
		 * This function sets the width of the chart
		 * @param integer Width string
                 * @usage Set to 0 in order to use Google API default size
                 * @todo Make it default
                 * @since 0.3
		 */ 
	        public function setWidth($newWidth){
                        $this->width = $newWidth;
                }
                /**
		 * This function sets the heigth of the chart
		 * @param integer Height string
                 * @usage Set to 0 in order to use Google API default size
                 * @todo Make it default
                 * @since 0.3
		 */ 
	        public function setHeight($newHeight){
                        $this->height = $newHeight;
                }
                public function addBackground($gBackground){
                        if(!isset($this->backgrounds)){
                                $this->backgrounds = array($gBackground);
                                return;
                        }
                        array_push($this->backgrounds, $gBackground);
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
                	if(!utility::isArrayEmpty($this->scaledValues))
                        	return "&chd=".$this->dataEncodingType.":".$this->encodeData($this->scaledValues,"" ,",");
                	else
                		return "";
                }
                protected function getBackgroundString(){
                        if(!isset($this->backgrounds))
                                return "";
                        $retStr = "&chf=";
                        foreach($this->backgrounds as $currBg){
                                $retStr .= $this->textEncodeData($currBg->toArray(), ",", "|");
                        }
                        $retStr = trim($retStr, "|");
                        return $retStr;
                }
                /**
                 *  Call this function for every axis to label.
                 * 
                 *  @param $rangeArray An array of four elements: (<axis_index>,<start_val>,<end_val>,<step>)
                 */
                public function addAxisRange($rangeArray){
                        array_push($this->axisRanges, $rangeArray);
                }
                public function clearAxisRange(){
                        $this->axisRanges = Array();
                }
                protected function getAxisLabels(){
                        $retStr = "";
                        if(isset($this->xAxisLabels)){
                                $retStr = "&chxl=0:|".$this->encodeData($this->xAxisLabels,"", "|");
                        }
                        if(isset($this->showAxis)){
                                $retStr .= "&chxt=".$this->encodeData($this->showAxis,"" ,",");
                        }
                        if(!utility::isArrayEmpty($this->axisRanges)) {
                                $retStr .= "&chxr=".$this->encodeData($this->axisRanges,"",",");
                        }
                        return $retStr;
                }
                protected function concatUrl(){
			$fullUrl = "http://";
			if(isset($this->serverNum))
				$fullUrl .= $this->serverNum.".";
                        $fullUrl .= $this->baseUrl;
                        $fullUrl .= "cht=".$this->types[$this->type];
                        if ($this->width || $this->height)
                                $fullUrl .= "&chs=";
                        if ($this->width) 
                        	$fullUrl .= $this->width."x";
                        if ($this->height) 
                        	$fullUrl .= $this->height;
                                          
			$fullUrl .= $this->getDataSetString();
                        //the second check is done on the array so this isn't triggered when there is nothing to write
                        if(isset($this->valueLabels) && !utility::isArrayEmpty($this->getApplicableLabels($this->valueLabels)))
                                $fullUrl .= "&chdl=".$this->encodeData($this->getApplicableLabels($this->valueLabels),"", "|");
                        //the second check is done on the array so this isn't triggered when called by gLatexFormula
                        if(isset($this->dataColors) && !utility::isArrayEmpty($this->getApplicableLabels($this->dataColors)))
                        	$fullUrl .= "&chco=".$this->encodeData($this->getApplicableLabels($this->dataColors),"", ",");
                        if(isset($this->title))
                                $fullUrl .= "&chtt=".$this->title;
                        $fullUrl .= $this->getAxisLabels();
                        //$fullUrl .= $this->getBackgroundString();
			return $fullUrl;
                }
                protected function getApplicableLabels($labels){
                        //$trimmedValueLabels = $labels;
                        return array_splice($labels, 0, count($this->values));
                }
                public function getUrl(){
                        $this->prepForUrl();
                        return $this->concatUrl();
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
		public function setServerNumber($newServerNum){
			$this->serverNum = $newServerNum % 10;
		}
		public function getImgCode(){
                        $code = '<img src="';
                        $code .= $this->getUrl().'"';
                        $code .= 'alt="gChartPhp Chart" width='.$this->width.' height='.$this->height.'>';
                        print($code);
	        }
        }

        class gPieChart extends gChart{
                function __construct(){
                        $this->type = 6;
                        $this->width = $this->width * 1.75;
                }
                function setScalar(){
                        return 1;
                }

                protected function getAxesString(){
                        return "";
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
                                $this->width = $this->width * 2;
                        }
                        else{
                                $this->type = 6;
                                $this->width = $this->width * 1.5;
                        }
                }
		protected function getApplicableLabels($labels){
                        //$trimmedValueLabels = $labels;
                        return array_splice($labels, 0, count($this->values[0]));
                }
	}

        class gLineChart extends gChart{
                function __construct(){
                        $this->type = 0;
                }              
        }
       
        class gBarChart extends gChart{
                public $barWidth;
                private $realBarWidth;
                public $groupSpacerWidth = 1;
                protected $totalBars = 1;
                protected $isHoriz = false;
                public function getUrl(){
                        $this->scaleValues();
                        $this->setBarWidth();
                        $retStr = parent::concatUrl();
                        $retStr .= "&chbh=$this->realBarWidth,$this->groupSpacerWidth";
                        return $retStr;
                }
               
                function setBarCount(){
                        $this->totalBars = utility::count_r($this->values);
                }
               
                protected function getAxisLabels(){
                        $retStr = "";
                        $xAxis = 0;
                        if($this->isHoriz)
                                $xAxis = 1;    
                        $yAxis = 1 - $xAxis;                    
                        if(isset($this->xAxisLabels)){
                                $retStr = "&chxl=$xAxis:|".$this->encodeData($this->xAxisLabels,"", "|");
                                //$retStr = "&$yAxis:|".$this->encodeData($this->yAxisLabels,"", "|");
                        }
                        return $retStr;
                }
                private function setBarWidth(){
                        if(isset($this->barWidth)){
                                $this->realBarWidth = $this->barWidth;
                                return;
                        }
                        $this->setBarCount();
                        $totalGroups = utility::getMaxCountOfArray($this->values);
                        if($this->isHoriz)
                                $chartSize = $this->height - 50;
                        else
                                $chartSize = $this->width - 50;
                               
                        $chartSize -= $totalGroups * $this->groupSpacerWidth;
                        $this->realBarWidth = round($chartSize/$this->totalBars);
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
                protected function getAxesString(){
                        return "";
                }
               
                public function getUrl(){
                        $retStr = parent::getUrl();
//                      $retStr .= "&chl=".$this->encodeData($this->valueLabels,"", "|");
                        return $retStr;
                }
                protected function getDataSetString(){
                        $fullDataSet = array_splice($this->scaledValues[0], 0, 3);
                        while(count($fullDataSet)<3){
                                array_push($fullDataSet, 0);
                        }
                       
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
                protected function getApplicableLabels($labels){
                        //$trimmedValueLabels = $labels;
                        return array_splice($labels, 0, count($this->values[0]));
                }
        }

class gLatexFormula extends gChart{
                function __construct(){
                	//This variables are set to 0 because the API will render the image with the right dimensions
                	//Set only height if you need an exact height for your image
                	$this->width = 0;
                	$this->height = 0; 
                        $this->type = 10;
                }
               
                public function getUrl(){
                        $retStr = parent::getUrl();
                        $retStr .= "&chl=".$this->encodeData($this->valueLabels,"", "|");
                        if(isset($this->dataColors))
                        	$retStr .= "&chco=".$this->encodeData($this->dataColors,"", ",");
                        return $retStr;
                }
                public function latexCode($latexCode){
                        $this->valueLabels = array(htmlspecialchars($latexCode));
                }
                public function setTextColor($textColor){
                        $this->dataColors = array($textColor);
                }
		//No applicable labels for this class
	        protected function getApplicableLabels($labels){
                        return null;
                }	
		public function getImgCode(){
                        $code = '<img src="';
                        $code .= $this->getUrl().'"';
                        $code .= 'alt="gChartPhp Chart"'; 
                        if($this->width)
                                $code .= ' width='.$this->width;
                        if($this->height)
                                $code .= ' height='.$this->height;
                        $code .= '>';
                        print($code);
	        }
        }

class gQRCode extends gChart{
                
                private $outputEncoding;
                private $errorCorrectionLevel = Array();

                function __construct(){
                	$this->width = 150;
                	$this->height = 150; 
                        $this->type = 11;
                }
               
                public function getUrl(){
                        $retStr = parent::getUrl();
                        $retStr .= "&chl=".$this->encodeData($this->valueLabels,"", "|");
                        if(isset($this->outputEncoding))
                                $retStr .="&choe=".$this->outputEncoding;
                        if(!utility::isArrayEmpty($this->errorCorrectionLevel))
                                $retStr .="&chld=".$this->encodeData($this->errorCorrectionLevel,"", "|");
                        return $retStr;
                }
                /** 
                  * This function simplify the concept of the writing of the function
                  */
                public function QRCode($QRCode){
                        $this->valueLabels = array(htmlspecialchars($QRCode));
                }
                //Add checks to this two functions
                public function setOutputEncoding($newOutputEncoding){
                        $this->outputEncoding = $newOutputEncoding;
                }
                public function seterrorCorrectionLevel($newErrorCorrectionLevel,$newMargin){
                        $this->errorCorrectionLevel = array($newErrorCorrectionLevel,$newMargin);
                }
        }
       
?>

