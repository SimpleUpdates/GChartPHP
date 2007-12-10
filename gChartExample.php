<?php
require ('gChart.php');
?>
<h1>A php wrapper for the Google Chart API</h1>
<h1>Quick examples.</h1>

<?php
$egChart = new gChart;
$egChart->type = 6;
$egChart->height = 115;
$egChart->values = Array("12,15,66,34");
$egChart->valueLabels = Array("first", "second", "third","fourth");

?>
<h2>Pie Chart</h2>
<img src="<?php print $egChart->getUrl();  ?>" /> <p> awesome!</p>
<code>
$egChart = new gChart;<br>
$egChart->type = 6;<br>
$egChart->height = 115;<br>
$egChart->values = Array("12,15,66,34");<br>
$egChart->valueLabels = Array("first", "second", "third","fourth");
</code>

<?php
$egChart->type = 7;
$egChart->width = 240;
$egChart->height = 100;
?>
<h2>"3-D" Pie Chart</h2>
<img src="<?php print $egChart->getUrl();  ?>" /> <p> awesome-tastic!</p>
<code>
$egChart = new gChart;<br>
$egChart->width = 220;<br>
$egChart->height = 100;<br>
$egChart->type = 7;<br>
$egChart->values = Array("12,15,66,34");<br>
$egChart->valueLabels = Array("first", "second", "third","fourth");
</code>
<?php
$egChart->type = 0;
$egChart->height = 200;
?>
<h2>Line Chart</h2>
<img src="<?php print $egChart->getUrl();  ?>" /> <p> super-mega-awesome!</p>
<code>
$egChart = new gChart;<br>
$egChart->type = 0;<br>
$egChart->values = Array("12,15,66,34");<br>
$egChart->valueLabels = Array("first", "second", "third","fourth");
</code>

<?php
$egChart->type = 2;
?>
<h2>Horizontal Stacked Bar Chart</h2>
<img src="<?php print $egChart->getUrl();  ?>" /> <p> i think we are getting the point...</p>
<code>
$egChart = new gChart;<br>
$egChart->type = 2;<br>
$egChart->values = Array("12,15,66,34");<br>
$egChart->valueLabels = Array("first", "second", "third","fourth");
</code>
<?php
$egChart->type = 3;
?>
<h2>Vertical Stacked Bar Chart</h2>
<img src="<?php print $egChart->getUrl();  ?>" /> <p> well this is getting old...  no one is impressed.</p>
<code>
$egChart = new gChart;<br>
$egChart->type = 3;<br>
$egChart->values = Array("12,15,66,34");<br>
$egChart->valueLabels = Array("first", "second", "third","fourth");
