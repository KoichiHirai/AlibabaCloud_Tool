<html>
<head>
<!-- Latest compiled and minified CSS --> <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous"> <!-- Optional theme --> <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous"> <!-- Latest compiled and minified JavaScript --> <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.2.2/Chart.js"></script>
</head>
<body>
<?php $ini = parse_ini_file("config.ini"); ?>

<h1>The state of each instance</h1>

<h2>The informaiton of the instance</h2>

<?php
$filepath = "instanceInfo.csv";
$file = new SplFileObject($filepath);
$file->setFlags(SplFileObject::READ_CSV);
                                                                                                                       
foreach ($file as $str){
        $info = $str ;
        }
?>

<style>
table{
width: 50px;
}
td{
text-align: center;
}
</style> 
<table border="1" class="table table-borderd">
	<tr>
		<td>Region ID</td>
		<td>Zone ID</td>
		<td>Image ID</td>
		<td>The numeber of cores</td>
		<td>Memory</td>
	</tr>
	<tr>
		<td><?php echo $info[0]; ?></td>
		<td><?php echo $info[1]; ?></td>
		<td><?php echo $info[2]; ?></td>
		<td><?php echo $info[3]; ?></td>
		<td><?php echo $info[4]; ?></td>
	</tr>
</table>
                                                                                                                       
<?php
$filepath = "task.csv";
#$fp = fopen($filename, "r");
$file = new SplFileObject($filepath);
$file->setFlags(SplFileObject::READ_CSV);

$count = -1;

foreach ($file as $key => $line){
	$count++;
	foreach( $line as $str ){
		$records[ $key ][] = $str;
	}
}

//echo print_r($records);

echo "<h2>";
echo "the number of creating instances: " . $count;
echo"</h2>";
                                                                                                                      
?>

<h2>
The latest instance states
</h2>
<?php 
if($count != 0){
	echo "<h3>";
	echo "The date created the instance: " . $records[$count-1] [0];
	echo "</h3>";
}

?>

<style>
table{
width: 350px;
}
td{
text-align: center;
}
</style>
<table border = 0>
	<tr>
		<td>
		<?php if($count == 0): ?>
		<table border = 0>
			<tr>
			<td><h3>No instance</h3></td>
			</tr>		
		</table>
		<?php else: ?>
		
		<table border = 1 class="table table-hover" >
			<td></td>
			<td>OK/NG</td><td>Time</td>
		</tr>
		<tr>
			<td>Creating</td>
			<td><?php echo $records[$count-1][2]; ?></td>
			<td><?php echo $records[$count-1][3]; ?></td>
		</tr>
		<tr>
			<td>Starting</td>
			<td><?php echo $records[$count-1][4]; ?></td>
			<td><?php echo $records[$count-1][5]; ?></td>
		</tr>
		<tr>
			<td>Stopping</td>
			<td><?php echo $records[$count-1][6]; ?></td>
			<td><?php echo $records[$count-1][7]; ?></td>
		</tr>
		<tr>
			<td>Removing</td>
			<td><?php echo $records[$count-1][8]; ?></td>
			<td><?php echo $records[$count-1][9]; ?></td>
		</tr>
		<tr>
			<td>Total Time</td>
			<td></td>
			<td><?php echo $records[$count-1][10]; ?></td>
		</tr>
		</table>
		<?php endif; ?>
		</td>
	</tr>
</table>

<br>

<h2>
The state of other 9 latest instances
</h2>

<?php /*
echo "<h3>";
echo "The date created the instance: " . $records[$count-1] [0];
echo "</h3>";
*/
?>


<style>
table{
width: 350px;
}
td{
text-align: center;
}
</style>
<table border = 0 cellpadding = 5 class = "table table-bordered">
<?php for($i=0; $i<3; $i++): ?>
        <tr>
	<?php for($j=0; $j<3; $j++): ?>
		<td>
		<?php if($count-(($i*3)+($j+2)) < 0): ?>
                	<table border = 0>
                        <tr>
                        <td><h3>No Entry</h3></td>
                        </tr>
                	</table>
		<?php else: ?>
			<table border = 1>
			<tr>
                	<td><?php echo $records[$count-(($i*3)+($j+2))][0]; ?></td>
                	<td>OK/NG</td>
			<td>Time</td>
        		</tr>
        		<tr>
                	<td>Creating</td>
                	<td><?php echo $records[$count-(($i*3)+($j+2))][2]; ?></td>
                	<td><?php echo $records[$count-(($i*3)+($j+2))][3]; ?></td>
        		</tr>
        		<tr>
                	<td>Starting</td>
                	<td><?php echo $records[$count-(($i*3)+($j+2))][4]; ?></td>
                	<td><?php echo $records[$count-(($i*3)+($j+2))][5]; ?></td>
        		</tr>
        		<tr>
                	<td>Stopping</td>
                	<td><?php echo $records[$count-(($i*3)+($j+2))][6]; ?></td>
                	<td><?php echo $records[$count-(($i*3)+($j+2))][7]; ?></td>
        		</tr>
        		<tr>
                	<td>Removing</td>
                	<td><?php echo $records[$count-(($i*3)+($j+2))][8]; ?></td>
                	<td><?php echo $records[$count-(($i*3)+($j+2))][9]; ?></td>
        		</tr>
			<tr>
                        <td>Total Time</td>
                        <td></td>
                        <td><?php echo $records[$count-(($i*3)+($j+2))][10]; ?></td>
			</tr>
			</table>
		<?php endif; ?>
		</td>
	<?php endfor; ?>
        </tr>
<?php endfor; ?>
</table>

<canvas id = "canvas"  height = "10 "width = "20"></canvas>

<script>

//折れ線グラフ2
var number = <?php echo json_encode($ini["number"]); ?>;

var ctx = document.getElementById("canvas");

var array = <?php echo json_encode($records); ?>;
var count_js = <?php echo json_encode($count); ?>;

var array_date = [], array_time = [];

for(var i=0; i < number; i++){
    array_date[i] = array[(count_js-number)+i][0];
    array_time[i] = array[(count_js-number)+i][10];
  }


var myLine2Chart = new Chart(ctx, {
  //グラフの種類
  type: 'line',
  //データの設定
  data: {
      //データ項目のラベル
      labels: array_date, 
      //labels: [array[count_js-1] [0], array[count_js-2][0], array[count_js-3] [0],array[count_js-4] [0], array[count_js-5] [0], array[count_js-6] [0], array[count_js-7] [0],array[count_js-8] [0],array[count_js-9] [0],array[count_js-10] [0]],
      //データセット
      datasets: [
          {
              //凡例
              label: "Total Time",
              //面の表示
              fill: false,
              //線のカーブ
              lineTension: 0,
              //背景色
              backgroundColor: "rgba(179,181,198,0.2)",
              //枠線の色
              borderColor: "rgba(179,181,198,1)",
              //結合点の枠線の色
              pointBorderColor: "rgba(179,181,198,1)",
              //結合点の背景色
              pointBackgroundColor: "#fff",
              //結合点のサイズ
              pointRadius: 5,
              //結合点のサイズ（ホバーしたとき）
              pointHoverRadius: 8,
              //結合点の背景色（ホバーしたとき）
              pointHoverBackgroundColor: "rgba(179,181,198,1)",
              //結合点の枠線の色（ホバーしたとき）
              pointHoverBorderColor: "rgba(220,220,220,1)",
              //結合点より外でマウスホバーを認識する範囲（ピクセル単位）
              pointHitRadius: 15,
              //グラフのデータ
              data: array_time 
              //data: [array[count_js-1] [10], array[count_js-2][10], array[count_js-3] [10],array[count_js-4] [10], array[count_js-5] [10], array[count_js-6] [10], array[count_js-7] [10],array[count_js-8] [10],array[count_js-9] [10],array[count_js-10] [10]]
          }
      ]
  },
  //オプションの設定
  options: {
      //軸の設定
      scales: {
          //縦軸の設定
          yAxes: [{
              //目盛りの設定
              ticks: {
                  //最小値を0にする
                  beginAtZero: false
              }
          }]
      },
      //ホバーの設定
      hover: {
          //ホバー時の動作（single, label, dataset）
          mode: 'single'
      }
  }
});
</script>                                                                                                                      
</body>


</html>
