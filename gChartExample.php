<?php
ini_set('display_errors','1');
?>
<html>
<head>
<title>PHP Wrapper for Google Chart API Examples - 0.4</title>

<style type="text/css">
img { display:block; }
</style>
</head>
<body>
<h1>PHP Wrapper for Google Chart API Examples - 0.4</h1>
<h1>Quick examples.</h1>

<?php
require ('gChart.php');
?>
<h2>Pie Chart</h2>
<?php
$piChart = new gPieChart();
$piChart->addDataSet(array(112,315,66,40));
$piChart->setLegend(array("first", "second", "third","fourth"));
$piChart->setLabels(array("first", "second", "third","fourth"));
$piChart->setColors(array("ff3344", "11ff11", "22aacc", "3333aa"));
?>
<img src="<?php print $piChart->getUrl();  ?>" /> <br> pie chart using the gPieChart class.
<p>
<em>code:</em><br>
<code>
$piChart = new gPieChar();<br>
$piChart->addDataSet(array(112,315,66,40));<br>
$piChart->setLegend(array("first", "second", "third","fourth"));<br>
$piChart->setLabels(array("first", "second", "third","fourth"));<br>
$piChart->setColors(array("ff3344", "11ff11", "22aacc", "3333aa"));<br>
</code>
</p>
<h2>3D Pie Chart</h2>
<?php
$piChart->set3D(true);
?>
<img src="<?php print $piChart->getUrl();  ?>" /> <br> 3D pi chart using the gPieChart class.
<p>
<em>code:</em><br>
<code>
$piChart->set3D(true);<br>
</code>
</p>
<h2>Line Chart</h2>
<?php
$lineChart = new gLineChart(300,300);
$lineChart->addDataSet(array(112,315,66,40));
$lineChart->addDataSet(array(212,115,366,140));
$lineChart->addDataSet(array(112,95,116,140));
$lineChart->setLegend(array("first", "second", "third","fourth"));
$lineChart->setColors(array("ff3344", "11ff11", "22aacc", "3333aa"));
$lineChart->setVisibleAxes(array('x','y'));
$lineChart->setDataRange(30,400);
$lineChart->addAxisRange(0, 1, 4, 1);
$lineChart->addAxisRange(1, 30, 400);
?>
<img src="<?php print $lineChart->getUrl();  ?>" /> <br> line chart using the gLineChart class.
<?php print $lineChart->getUrl();  ?>
<p>
<em>code:</em><br>
<code>
$lineChart = new gLineChart(300,300);<br>
$lineChart->addDataSet(array(112,315,66,40));<br>
$lineChart->addDataSet(array(212,115,366,140));<br>
$lineChart->addDataSet(array(112,95,116,140));<br>
$lineChart->setLegend(array("first", "second", "third","fourth"));<br>
$lineChart->setColors(array("ff3344", "11ff11", "22aacc", "3333aa"));<br>
$lineChart->setVisibleAxes(array('x','y'));<br>
$lineChart->setDataRange(30,400);<br>
$lineChart->addAxisRange(0, 1, 4, 1);<br>
$lineChart->addAxisRange(1, 30, 400);<br>
</code>
</p>
<h2>Grouped Bar Chart</h2>
<?php
$barChart = new gGroupedBarChart();
$barChart->setDimensions(800,150);
$barChart->addDataSet(array(112,315,66,40));
$barChart->addDataSet(array(212,115,366,140));
$barChart->addDataSet(array(112,95,116,140));
$barChart->setColors(array("ff3344", "11ff11", "22aacc", "3333aa"));
$barChart->setLegend(array("first", "second", "third","fourth"));
?>
<img src="<?php print $barChart->getUrl();  ?>" /> <br> grouped bar chart using the gGroupedBarChart class.
<p>
<em>code:</em><br>
<code>
$barChart = new gGroupedBarChart();<br>
$barChart->setDimensions(800,150);<br>
$barChart->addDataSet(array(112,315,66,40));<br>
$barChart->addDataSet(array(212,115,366,140));<br>
$barChart->addDataSet(array(112,95,116,140));<br>
$barChart->setColors(array("ff3344", "11ff11", "22aacc", "3333aa"));<br>
$barChart->setLegend(array("first", "second", "third","fourth"));<br>
</code>
</p>
<h2>Horizontal Grouped Bar Chart</h2>
<?php
$barChart->setHorizontal(true);
$barChart->setDimensions(150, 400);
$barChart->setLegend(array("first", "second", "third","fourth"));
?>
<img src="<?php print $barChart->getUrl();  ?>" /> <br> horizontal grouped bar chart using the gGroupedBarChart class.
<p>
<em>code:</em><br>
<code>
$barChart->setHorizontal(true);<br>
$barChart->setDimensions(150, 400);<br>
$barChart->setLegend(array("first", "second", "third","fourth"));<br>
</code>
</p>
<h2>Stacked Bar Chart</h2>
<?php
$barChart = new gStackedBarChart(450,350);
$barChart->addDataSet(array(112,315,66,40));
$barChart->addDataSet(array(212,115,366,140));
$barChart->addDataSet(array(112,95,116,140));
$barChart->setLegend(array("first", "second", "third","fourth"));
$barChart->setColors(array("ff3344", "11ff11", "22aacc", "3333aa"));
$barChart->setTitle("A multiline\r\nA Title");
?>
<img src="<?php print $barChart->getUrl();  ?>" /> <br> stacked bar chart using the gStackedBarChart class.
<p>
<em>code:</em><br>
<code>
$barChart = new gStackedBarChart(450,350);<br>
$barChart->addDataSet(array(112,315,66,40));<br>
$barChart->addDataSet(array(212,115,366,140));<br>
$barChart->addDataSet(array(112,95,116,140));<br>
$barChart->setLegend(array("first", "second", "third","fourth"));<br>
$barChart->setColors(array("ff3344", "11ff11", "22aacc", "3333aa"));<br>
$barChart->setTitle("A multiline\r\nA Title");<br>
</code>
</p>
<h2>Horizontal Stacked Bar Chart</h2>
<?php
$barChart->setHorizontal(true);
$barChart->groupSpacerWidth = 10;
?>
<img src="<?php print $barChart->getUrl();  ?>" /> <br> horizontal stacked bar chart using the gStackedBarChart class.
<p>
<em>code:</em><br>
<code>
$barChart->setHorizontal(true);<br>
$barChart->groupSpacerWidth = 10;
</code>
</p>
<h2>Venn Diagram</h2>
<?php
$vennDiagram = new gVennDiagram();
$vennDiagram->addDataSet(array(1120,3150,660));
$vennDiagram->addIntersections(array(220, 320, 400, 200));
$vennDiagram->setEncodingType('t');
$vennDiagram->setColors(array("ff3344", "11ff11", "22aacc", "3333aa"));
?>
<img src="<?php print $vennDiagram->getUrl();  ?>" /> <br> venn diagram using the gVennDiagram class.
<p>
<em>code:</em><br>
<code>
$vennDiagram = new gVennDiagram;<br>
$vennDiagram->addDataSet(array(1120,3150,660));<br>
$vennDiagram->addIntersections(array(220, 320, 400, 200));<br>
$vennDiagram->setColors(array("ff3344", "11ff11", "22aacc", "3333aa"));<br>
</code>
</p>
<h2>Latex Formula</h2>
<?php
$latex = new gFormula();
$latex -> setLatexCode('\cos(x)^2+\sin(x)^2=1');
?>
<img src="<?php print $latex->getUrl();  ?>" /> <br> latex formula using the gFormula class.
<p>
<em>code:</em><br>
<code>
$latex = new gFormula();<br>
$latex -> setLatexCode('\cos(x)^2+\sin(x)^2=1');<br>
</code>
</p>
<h2>QR Code</h2>
<?php
$qr = new gQRCode();
$qr -> setQRCode('gChartPhp is awesome!');
?>
<img src="<?php print $qr->getUrl();  ?>" /> <br> QR Code using the gVennDiagram class.
<p>
<em>code:</em><br>
<code>
$qr = new gQRCode();<br>
$qr -> setQRCode('gChartPhp is awesome!');<br>
</code>
</p>
</body>
</html>