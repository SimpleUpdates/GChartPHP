<html>
<head>
<title>PHP Wrapper for Google Chart API Examples - 0.2</title>

<style type="text/css">
img { display:block; }
</style>
</head>
<body>
<h1>PHP Wrapper for Google Chart API Examples - 0.2</h1>
<h1>Quick examples.</h1>

<?php
require ('gChart2.php');

$piChart = new gPieChart;
$piChart->addDataSet(array(112,315,66,40));
$piChart->valueLabels = array("first", "second", "third","fourth");
$piChart->dataColors = array("ff3344", "11ff11", "22aacc", "3333aa");

?>
<h2>Pie Chart</h2>
<img src="<?php print $piChart->getUrl();  ?>" /> <br> pie chart using the gPieChart class.
<p>
<em>code:</em><br>
<code>
$piChart = new gPieChart;<br>
$piChart->addDataSet(array(112,315,66,40));<br>
$piChart->valueLabels = array("first", "second", "third","fourth");<br>
$piChart->dataColors = array("ff3344", "11ff11", "22aacc", "3333aa");<br>
</code>
</p>
<?php
$piChart->set3D(true);
?>
<h2>3D Pie Chart</h2>
<img src="<?php print $piChart->getUrl();  ?>" /> <br> 3D pi chart using the gPieChart class.
<p>
<em>code:</em><br>
<code>
$piChart->set3D(true);<br>
</code>
</p>
<?php
$lineChart = new gLineChart;
$lineChart->addDataSet(array(112,315,66,40));
$lineChart->addDataSet(array(212,115,366,140));
$lineChart->addDataSet(array(112,95,116,140));
$lineChart->valueLabels = array("first", "second", "third","fourth");
$lineChart->dataColors = array("ff3344", "11ff11", "22aacc", "3333aa");
?>
<h2>Line Chart</h2>
<img src="<?php print $lineChart->getUrl();  ?>" /> <br> line chart using the gLineChart class.
<p>
<em>code:</em><br>
<code>
$lineChart = new gLineChart;<br>
$lineChart->addDataSet(array(112,315,66,40));<br>
$lineChart->addDataSet(array(212,115,366,140));<br>
$lineChart->addDataSet(array(112,95,116,140));<br>
$lineChart->valueLabels = array("first", "second", "third","fourth");<br>
$lineChart->dataColors = array("ff3344", "11ff11", "22aacc", "3333aa");<br>
</code>
</p>
<?php
$barChart = new gGroupedBarChart;
$barChart->width = 150;
$barChart->height = 150;
$barChart->addDataSet(array(112,315,66,40));
$barChart->addDataSet(array(212,115,366,140));
$barChart->addDataSet(array(112,95,116,140));
$barChart->dataColors = array("ff3344", "11ff11", "22aacc", "3333aa");
?>
<h2>Grouped Bar Chart</h2>
<img src="<?php print $barChart->getUrl();  ?>" /> <br> grouped bar chart using the gGroupedBarChart class.
<p>
<em>code:</em><br>
<code>
$barChart = new gGroupedBarChart;<br>
$barChart->width = 150;<br>
$barChart->height = 150;<br>
$barChart->addDataSet(array(112,315,66,40));<br>
$barChart->addDataSet(array(212,115,366,140));<br>
$barChart->addDataSet(array(112,95,116,140));<br>
$barChart->dataColors = array("ff3344", "11ff11", "22aacc", "3333aa");<br>
</code>
</p>
<?php
$barChart->setHorizontal(true);
$barChart->valueLabels = array("first", "second", "third","fourth");
?>
<h2>Horizontal Grouped Bar Chart</h2>
<img src="<?php print $barChart->getUrl();  ?>" /> <br> horizontal grouped bar chart using the gGroupedBarChart class.
<p>
<em>code:</em><br>
<code>
$barChart->setHorizontal(true);<br>
$barChart->valueLabels = array("first", "second", "third","fourth");<br>
</code>
</p>
<?php
$barChart = new gStackedBarChart;
$barChart->width = 350;
$barChart->height = 350;
$barChart->addDataSet(array(112,315,66,40));
$barChart->addDataSet(array(212,115,366,140));
$barChart->addDataSet(array(112,95,116,140));
$barChart->valueLabels = array("first", "second", "third","fourth");
$barChart->dataColors = array("ff3344", "11ff11", "22aacc", "3333aa");
$barChart->setTitle("OMG!\r\nA Title");
?>
<h2>Stacked Bar Chart</h2>
<img src="<?php print $barChart->getUrl();  ?>" /> <br> stacked bar chart using the gStackedBarChart class.
<p>
<em>code:</em><br>
<code>
$barChart = new gStackedBarChart;<br>
$barChart->width = 350;<br>
$barChart->height = 350;<br>
$barChart->addDataSet(array(112,315,66,40));<br>
$barChart->addDataSet(array(212,115,366,140));<br>
$barChart->addDataSet(array(112,95,116,140));<br>
$barChart->valueLabels = array("first", "second", "third","fourth");<br>
$barChart->dataColors = array("ff3344", "11ff11", "22aacc", "3333aa");<br>
$barChart->setTitle("OMG!\r\nA Title");<br>
</code>
</p>
<?php
$barChart->setHorizontal(true);
$barChart->groupSpacerWidth = 10;
?>
<h2>Horizontal Stacked Bar Chart</h2>
<img src="<?php print $barChart->getUrl();  ?>" /> <br> horizontal stacked bar chart using the gStackedBarChart class.
<p>
<em>code:</em><br>
<code>
$barChart->setHorizontal(true);<br>
$barChart->groupSpacerWidth = 10;
</code>
</p>
<?php
$vennDiagram = new gVennDiagram;
$vennDiagram->addDataSet(array(112,315,66,40));
$vennDiagram->addIntersections(array(22, 32, 4, 2));
$vennDiagram->dataColors = array("ff3344", "11ff11", "22aacc", "3333aa");
?>
<h2>Venn Diagram</h2>
<img src="<?php print $vennDiagram->getUrl();  ?>" /> <br> venn diagram using the gVennDiagram class.
<p>
<em>code:</em><br>
<code>
$vennDiagram = new gVennDiagram;<br>
$vennDiagram->addDataSet(array(112,315,66,40));<br>
$vennDiagram->addIntersections(array(22, 32, 4, 2));<br>
$vennDiagram->dataColors = array("ff3344", "11ff11", "22aacc", "3333aa");<br>
</code>
</p>

</body>
</html>