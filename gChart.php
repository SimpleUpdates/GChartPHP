<?php
/**
 * gChartPhp, a php wrapper for  the Google Chart Tools / Image Charts (aka Chart API) {@link http://code.google.com/apis/charttools/}
 */
 
/**
 * Utility class
 *
 * @version 0.2
 * @since 0.4
 */
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
		$summedArray = array_fill(0,count($mixed)+1,0);
		
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
	
	public static function isArrayEmpty($array) {
		return empty($array);
	}
	
}

/**
 * Main class
 *
 * This is the mainframe of the wrapper
 *
 * @version 0.4
 */
class gChart{
	/**
	 * This variable holds all the chart information.
	 * @var array
	 */
	private $chart;
	/**
	 * @var string
	 * @usedby gChat::getUrl()
	 */
	private $baseUrl = "chart.apis.google.com/chart?";
	/**
	 * @var integer
	 */
	protected $scalar = 1;
	/**
	 * @var array
	 */
	private $dataEncodingType = 't';
	/**
	 * @var array
	 */
	public $values = Array();
	private $width;
	private function setWidth($width) {
		$this->width = $width;
	}
	public function getWidth() {
		return($this->width);
	}
	private $height;
	private function setHeight($height) {
		$this->height = $height;
	}
	public function getHeight() {
		return($this->height);
	}
	/**
	 * Max and Min value for the data set, as used in setDataRange
	 */
	private $minValue;
	private function setMinValue($minValue) {
		$this->minValue = $minValue;
	}
	public function getMinValue() {
		return($this->minValue);
	}
	public function isSetMinValue() {
		return (isset($this->minValue));
	}
	private $maxValue;
	private function setMaxValue($maxValue) {
		$this->maxValue = $maxValue;
	}
	public function getMaxValue() {
		return($this->maxValue);
	}
	public function isSetMaxValue() {
		return (isset($this->maxValue));
	}	
	
	private $serverNum;
	
	public function setEncodingType($newEncodeType) {
		$this->dataEncodingType = $newEncodeType;
	}
	public function getEncodingType() {
		return ($this->dataEncodingType);
	}
	protected function encodeData($data, $separator, $encodigData = ''){
		if ($encodigData == 's') {
			$data = $this->simpleEncodeData($data);
			$separator = '';
		} else if ($encodigData == 'e') {
			$data = $this->extendedEncodeData($data);
			$separator = '';
		} else if ($encodigData == 't') {
			$data = $this->textEncodeData($data);
		}
		$retStr = $this->separateData($data, $separator, "|");
		$retStr = trim($retStr, "|");
		return $retStr;                                
	}
	protected function separateData($data, $separator, $datasetSeparator){
		$retStr = "";
		if(!is_array($data))
			return $data;
		foreach($data as $currValue){
			if(is_array($currValue))
				$retStr .= $this->separateData($currValue, $separator, $datasetSeparator);
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
	/**
	 * Encodes the data as Basic Text and Text Format with Custom Scaling. 
	 *
	 * This specifies floating point values from 0Ñ100, inclusive, as numbers. If user sets data range,
	 * with setDataRange(), the function will do nothig and Google API will render the inage in those
	 * boundaries.
	 */
	private function textEncodeData($data) {
		$encodedData = array();
		$max = utility::getMaxOfArray($data);
		if ($this->isSetMaxValue()) {
			return $data;
		}
		if ($max > 100) {
			$rate = $max / 100;
			foreach ($data as $array) {
				if (is_array($array)) {
					$encodedData2 = array();
					foreach ($array as $elem) {
						array_push($encodedData2, $elem / $rate);
					}
					array_push($encodedData, $encodedData2);
				} else {
					array_push($encodedData, $array / $rate);
				}
			}
		} else {
			$encodedData = $data;
		}
		return $encodedData;
	}
	/**
	 * Encodes the data as Simple Text. This specifies integer values from 0Ñ61, inclusive, encoded by a single 
	 * alphanumeric character. This results in the shortest data string URL of all the data formats.
	 */
	private function simpleEncodeData($data){
		$encode_string='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
		$max = utility::getMaxOfArray($data);
		$encodedData = array();
		if ($max > 61){
			$rate = $max / 61.0;
			foreach($data as $array) {
				if (is_array($array)) {
					$encodedData2 = array();
					foreach ($array as $elem){
						$index = (int)$elem/$rate;
						array_push($encodedData2, $encode_string[$index]);
					}
					array_push($encodedData, $encodedData2);
				} else {
					$index = (int)$array/$rate;
					array_push($encodedData, $encode_string[$index]);
				}
			}
		} else {
			foreach($data as $array) {
				if (is_array($array)) {
					$encodedData2 = array();
					foreach ($array as $elem){
						array_push($encodedData2, $encode_string[$elem]);
					}
					array_push($encodedData, $encodedData2);
				} else {
					array_push($encodedData, $encode_string[$array]);
				}
			}
		}
		return $encodedData;
	}
	/**
	 * Encodes the data as Extended Text. This specifies integer values from 0Ñ4095, inclusive, encoded by 
	 * two alphanumeric characters.
	 */
	private function extendedEncodeData($data){
		$encode_string='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-.';
		$max = utility::getMaxOfArray($data);
		$encodedData = array();
		if ($max > 4095){
			$rate = $max/4095.0;
			foreach ($data as $array)
				if (is_array($array)) {
					$encodedData2 = array();
					foreach ($array as $elem){
						$toEncode=(int)$elem/$rate;
						$s='';
						for ($i=0;$i<2;++$i){
							$m = $toEncode%64;
							$toEncode/=64;
							$s = $encode_string[$m].$s;
						}
						array_push($encodedData2, $s);
					}
					array_push($encodedData, $encodedData2);
				} else {
					$toEncode=(int)$array/$rate;
					$s='';
					for ($i=0;$i<2;++$i){
						$m = $toEncode%64;
						$toEncode/=64;
						$s = $encode_string[$m].$s;
					}
					array_push($encodedData, $s);	
				}
		} else {
			foreach ($data as $array)
				if (is_array($array)) {
					foreach ($array as $elem){
						$s='';
						$toEncode = $elem;
						for ($i=0; $i<2; ++$i){
							$m = $toEncode%64;
							$toEncode /= 64;
							$s = $encode_string[$m].$s;
						}
						array_push($encodedData2, $s);
					}
					array_push($encodedData, $encodedData2);
				} else {
					$s='';
					$toEncode = $array;
					for ($i=0; $i<2; ++$i){
						$m = $toEncode%64;
						$toEncode /= 64;
						$s = $encode_string[$m].$s;
					}
					array_push($encodedData, $s);
				}
		}
		return $encodedData;
	}
	public function getApplicableLabels($labels){
		return array_splice($labels, 0, count($this->values));
	}
	public function getUrl(){
		$fullUrl = "http://";
		if(isset($this->serverNum))
			$fullUrl .= $this->serverNum.".";
		$fullUrl .= $this->baseUrl;
		$this -> setDataSetString();
		foreach ($this -> chart as $key => $value) {
			$fullUrl .= '&'.$key.'='.$value;
		}
		return $fullUrl;
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
	/**
	 * Sets the chart property
	 * @param $key String Name of the chart parameter
	 * @param $value String Value of the chart parameter
	 */
	public function setProperty($key, $value) {
		$this -> chart[$key] = $value;
	}
	/**
	 * Sets chart dimensions.
	 *
	 * Sets chart dimension using chs parameter. This checks of $width and $height are 
	 * defined because in gFormula 0s are used as default values to let the server 
	 * autosize the final png image. If only $hegiht is not 0, then the server will use
	 * this value as the height of the png image and will autosize the width.
	 *
	 * @param $width Integer
	 * @param $height Integer
	 */
	public function setDimensions($width, $height) {
		$this -> setWidth($width);
		$this -> setHeight($height);
		if ($width && $height) {
			$this -> setProperty('chs', $width.'x'.$height);
		}
		else if ($height) 
			$this -> setProperty('chs', $height);
	}
	/**
	 * Sets the colors for element of the chart
	 *
	 * @param $colors Array Specify colors using a 6-character string of hexadecimal values, 
	 *						plus two optional transparency values, in the format RRGGBB[AA]. 
	 */
	public function setColors($colors) {
		$this -> setProperty('chco', $this->encodeData($this->getApplicableLabels($colors),","));
	}
	/**
	 * Sets the labels for the legend
	 *
	 * @param $labels Array
	 */
	public function setLegend($labels) {
		$this -> setProperty('chdl', $this->encodeData($this->getApplicableLabels($labels),"|"));
	}
	/**
	 * Sets the position and the order of the legend
	 *
	 * @param $position String Please refer to the documentation for the acceptable values
	 * @param $order String Please refer to the documentation for the acceptable values
	 */
	public function setLegendPosition($position, $order = null) {
		if (isset($order)) {
			$this -> setProperty('chdlp', $position.'|'.$order);
		} else {
			$this -> setProperty('chdlp', $position);
		}
	}
	/**
	 * Sets the title. You cannot specify where this appears.
	 *
	 * @param $title String Title to show for the chart. Use \r\n for a new line.
	 */
	public function setTitle($title) {
		$title = str_replace("\r\n", "|", $title);
		$title = str_replace(" ", "+", $title);
		$this -> setProperty('chtt', $title);
	}
	/**
	 *	Sets font size and color of the title
	 * 
	 * @param $color String The title color, in RRGGBB hexadecimal format. Default color is black.
	 * @param $size Integer Font size of the title, in points.
	 */
	public function setTitleOptions($color, $size) {
		$this -> setProperty('chts', $color.','.$size);
	}
	/**
	 * Specifies the size of the chart's margins, in pixels.
	 *
	 * You can specify the size of the chart's margins, in pixels. Margins are calculated inward from the 
	 * specified chart size (chs); increasing the margin size does not increase the total chart size, but 
	 * rather shrinks the chart area, if necessary.
	 *
	 * @param $chartMargins Array An array of four integers: <left_margin>, <right_margin>, <top_margin>, <bottom_margin>
	 * @param $legendMargins Array An array of two integers: <legend_width>, <legend_height>
	 */
	public function setChartMargins($chartMargins, $legendMargins) {
		$margins = array();
		array_push($margins, $chartMargins);
		array_push($margins, $legendMargins);
		$this -> setProperty('chma', $this -> encodeData($margins, ','));
	}
		/**
	 * Sets visible axes.
	 *
	 * @param $visibleAxes Array Visible axis labels. Please refer to the documentation for the acceptable values
	 */
	public function setVisibleAxes($visibleAxes) {
		$this->setProperty('chxt', $this->encodeData($visibleAxes,','));
	}
	/**
	 * Specifies the range of values that appear on each axis independently.
	 *
	 * @param $axisIndex Integer This is a zero-based index into the axis array specified by setVisibleAxes
	 * @param $startVal Integer A number, defining the low value for this axis.
	 * @param $endVal Integer A number, defining the high value for this axis.
	 * @param $stem Integer The count step between ticks on the axis. There is no default step value; the step 
	 *						is calculated to try to show a set of nicely spaced labels.
	 */
	public function addAxisRange($axisIndex, $startVal, $endVal, $step = null) {
		if (is_null($step))
			array_push($this->axisRange, array($axisIndex, $startVal, $endVal));
		else
			array_push($this->axisRange, array($axisIndex, $startVal, $endVal, $step));
	}
	public function setAxisRange() {
		$this -> setProperty('chxr', $this->encodeData($this->axisRange,','));
	}
	/**
	 * Specifies the labels that appear on each axis independently.
	 *
	 * @param $axisIndex Integer This is a zero-based index into the axis array specified by setVisibleAxes
	 * @param $axisLabel Array One or more labels to place along this axis.
	 */
	public function addAxisLabel($axisIndex, $axisLabel) {
		array_push($this->axisLabel, array($axisIndex.':') + $axisLabel);
	}
	public function setAxisLabel() {
		$this->setProperty('chxl', $this->encodeData($this -> axisLabel, '|'));
	}
	/**
	 * Specifies the data range. Note that this does not change the axis range; to change the axis range, you must 
	 * use the setAxisRange function.
	 *
	 * @param $startVal Integer A number, definig the low value for the data set. Usually, it is the same as $startVal in addAxisRange
	 * @param $endVal Integer A number, definig the high value for the data set. Usually, it is the same as $endVal in addAxisRange
	 */
	public function setDataRange($startVal, $endVal) {
		$this->setMaxValue($endVal);
		$this->setMinValue($startVal);
		$this->setProperty('chds', $startVal.','.$endVal);
	}
	/**
	 * Prepares the Data Set String
	 */
	protected function setDataSetString() {
		if(!utility::isArrayEmpty($this->values)) {
			$this -> setProperty('chd', $this->getEncodingType().':'.$this->encodeData($this->values,',',$this->getEncodingType()));
		}
	}
}

class gPieChart extends gChart{
	function __construct($width = 350, $height = 200) {
		$this -> setProperty('cht', 'p');
		$this -> setDimensions($width, $height);
	}
	public function getApplicableLabels($labels) {
		return array_splice($labels, 0, count($this->values[0]));
	}
	public function set3D($is3d = true, $resize = true){
		if($is3d){
			$this -> setProperty('cht', 'p3');
			if ($resize)
				$this -> setDimensions($this->getWidth() * 1.5, $this->getHeight());
		}
		else {
			$this -> setProperty('cht', 'p');
			if ($resize)
				$this -> setDimensions($this->getWidth() / 1.5, $this->getHeight());
		}
	}
	/**
	 * Sets the labels for Pie Charts
	 *
	 * @param $labels Array
	 */
	public function setLabels($labels) {
		$this -> setProperty('chl', $this->encodeData($this->getApplicableLabels($labels),"|"));
	}
	/**
	 * Rotates the chart.
	 *
	 * By default, the first series is drawn starting at 3:00, continuing clockwise around the chart, but 
	 * you can specify a custom rotation using this function.
	 *
	 * @param $angle Integer A floating point value describing how many radians to rotate the chart clockwise. 
	 * One complete turn is 2¹ (2 piÑabout 6.28) radians.
	 * @param $degree Bool Specifies if $angle is in degrees and not in radians. The function will to the conversion.
	 */
	public function setRotation($angle, $degree = false) {
		if ($degree)
			$angle = ($angle / 360) * 6.2831;
		$this -> setProperty('chp', $angle);
	}
}

class gLineChart extends gChart{
	
	protected $axisRange;
	protected $axisLabel;
	
	function __construct($width = 200, $height = 200){
		$this -> setProperty('cht', 'lc');
		$this -> setDimensions($width, $height);	
		$this -> axisRange = array();
		$this -> axisLabel = array();	
	}
	public function getUrl() {
		$this->setAxisRange();
		$this->setAxisLabel();
		$retStr = parent::getUrl();
		return $retStr;	
	}
}

class gBarChart extends gChart{
	public $barWidth;
	private $realBarWidth;
	public $groupSpacerWidth = 1;
	protected $totalBars = 1;
	protected $isHoriz = false;
	
	protected $axisRange;
	protected $axisLabel;
	
	public function getUrl(){
		$this->setBarWidth();
		$retStr = parent::getUrl();
		return $retStr;
	}
	function setBarCount(){
		$this->totalBars = utility::count_r($this->values);
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
		$this->setProperty('chbh', $this->realBarWidth.','.$this->groupSpacerWidth);
	}
}
class gGroupedBarChart extends gBarChart{
	function __construct($width = 200, $height = 200){
		$this->setProperty('cht','bvg');
		$this -> setDimensions($width, $height);
		$this -> axisRange = array();
		$this -> axisLabel = array();	
	}
	
	public function setHorizontal($isHorizontal = true){
		if($isHorizontal) {
			$this->setProperty('cht','bhg');
		} else {
			$this->type = 5;
		}
		$this->isHoriz = $isHorizontal;
	}
	
	
}
class gStackedBarChart extends gBarChart{
	function __construct($width = 200, $height = 200){
		$this->setProperty('cht','bvs');
		$this->setDimensions($width, $height);
		$this -> axisRange = array();
		$this -> axisLabel = array();			
	}
	function setBarCount(){
		$this->totalBars = utility::getMaxCountOfArray($this->values);
	}
	public function setHorizontal($isHorizontal = true){
		if($isHorizontal){
			$this->setProperty('cht','bhs');
		}
		else{
			$this->setProperty('cht','bvs');
		}
		$this->isHoriz = $isHorizontal;
	}
	function setScalar(){
		$maxValue = 100;
		$maxValue = max($maxValue, utility::getMaxOfArray(utility::addArrays($this->values)));
		if($maxValue < 100)
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
	function __construct($width = 200, $height = 200){
		$this -> setProperty('cht', 'v');
		$this -> setDimensions($width, $height);		
	}
	protected function setDataSetString(){
		$fullDataSet = array_splice($this->values[0], 0, 3);
		while(count($fullDataSet) < 3){
			array_push($fullDataSet, 0);
		}
		
		foreach($this->intersections as $temp){
			array_push($fullDataSet, $temp);
		}
		$fullDataSet = array_splice($fullDataSet, 0, 7);
		while(count($fullDataSet)<7){
			array_push($fullDataSet, 0);
		}
		
		$this -> setProperty('&chd', $this->getEncodingType().":".$this->encodeData($fullDataSet, ',', $this->getEncodingType()));
	}
	public function getApplicableLabels($labels) {
		return array_splice($labels, 0, count($this->values[0]));
	}
}

class gFormula extends gChart{
	/**
	 * @param $widht Integer It is set by default to 0 because the server will size the png automatically
	 * @param $height Integer It is set by default to 0 because the server will size the png automatically
	 */
	function __construct($width = 0, $height = 0){
		$this -> setDimensions($width, $height);
		$this -> setProperty('cht','tx');
	}
	
	public function setLatexCode($latexCode){
		$this -> setProperty('chl', urlencode($latexCode));
	}
	public function setTextColor($textColor){
		$this -> setProperty('chco', $textColor);
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
	
	function __construct($width = 150, $height = 150){
		$this -> setDimensions($width, $height);
		$this -> setProperty('cht','qr');
	}
	
	public function setQRCode($QRCode){
		$this -> setProperty('chl', urlencode($QRCode));
	}
	/**
	 * @param $newOutputEncoding String Please refer to the documentation for the acceptable values
	 */
	public function setOutputEncoding($newOutputEncoding){
		$this -> setProperty('choe', $newOutputEncoding);
	}
	/**
	 * @param $newErrorCorrectionLevel String Please refer to the documentation for the acceptable values
	 * @param $newMargin Integer Please refer to the documentation for the acceptable values
	 */
	public function seterrorCorrectionLevel($newErrorCorrectionLevel, $newMargin){
		$this -> setProperty('chld', $newErrorCorrectionLevel.'|'.$newMargin);
	}
}

?>

