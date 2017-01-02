<html>
 <head>
  <title>Tip Calculator</title>
 </head>
 <body>
 <?php
	
	$subTotal = "";
	$percentage = 15;
	$customPercentage = "";
	$splitPerson = 1;
	$subTotalErr = $percentageErr = $splitErr = false;
	$subTotalErrMessage = $percentageErrMessage = $splitErrMessage = "";

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
	  	if (empty($_POST["subTotal"])) {
	    	$subTotalErr = true;
	    	$subTotal = "";
	    	$subTotalErrMessage = " Bill subtotal can NOT be empty";
	  	} else {
		    $subTotal = process_input($_POST["subTotal"]);
		    // check if subTotal only contains numbers and great than 0
		    if (!is_numeric($subTotal) || $subTotal <= 0) {
		      $subTotalErr = true; 
		      $subTotalErrMessage = " Bill subtotal must be a numeric value greater than 0";
		    }
	  	}
	  
	   	if (empty($_POST["percentage"])) {
	    	$percentageErr = true;
	    	$percentage = 15;
	    	$percentageErrMessage = " You must select a tip percentage";
	  	} else {
	    	$percentage = $_POST["percentage"];
		    if($percentage == "custom"){
		    	$customPercentage = process_input($_POST["custom"]);
		    	if (!is_numeric($customPercentage) || $customPercentage <= 0) {
		      		$percentageErr = true; 
		      		$percentageErrMessage = " Custom percentage must be a numeric value greater than 0";
		    	}
	    	}
	  	}
	    
	    if (empty($_POST["splitPerson"])) {
		    $splitErr = true;
		    $splitPerson = "";
		    $splitErrMessage = " Split person can NOT be empty";
		  } else {
		    $splitPerson = process_input($_POST["splitPerson"]);
	    	if (!ctype_digit($splitPerson)) {
	      		$splitErr = true; 
		    	$splitErrMessage = " Split person must be a integer value greater than 0";
	    	}
		  }
	}

	function process_input($data) {
	  $data = trim($data);
	  $data = stripslashes($data);
	  $data = htmlspecialchars($data);
	  return $data;
	}
	?>
 	<h2>Tip Calculator</h2>
 	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
 		<p <?php if($subTotalErr){ echo 'style="color:red;"';} ?> >
 		Bill subtotal: $<input type="text" name="subTotal" value="<?php echo $subTotal;?>" />
 		<?php if($subTotalErr){ echo $subTotalErrMessage;} ?>
 		</p>

 		<p <?php if($percentageErr){ echo 'style="color:red;"';} ?> >
 		Tip percentage:<br>
 		<?php
 		for ($i = 10; $i <= 20; $i += 5) {
    		echo '<input type="radio" name="percentage" value="',$i,'"';
    		if($percentage == $i) echo 'checked';
    		echo '> ',$i,'%';
		}
		?>
		<br>
		<input type="radio" name="percentage" value="custom" <?php if($percentage == "custom") echo 'checked'; ?> >
		Custom: 
		<input type="text" name="custom" value="<?php echo $customPercentage;?>" />%.
		<?php if($percentageErr){ echo $percentageErrMessage;} ?>
  		<br>
  		</p>

  		<p <?php if($splitErr){ echo 'style="color:red;"';} ?> >
 		Split: <input type="text" name="splitPerson" value="<?php echo $splitPerson;?>" /> person(s).
 		<?php if($splitErr){ echo $splitErrMessage;} ?>
 		</p>
  		
 		<p><input type="submit" /></p>
	</form>

	<?php
		$tip = $total = 0;
		if($_SERVER["REQUEST_METHOD"] == "POST" && !$subTotalErr && !$percentageErr && !$splitErr){
			if($percentage == "custom"){
				$tip = $subTotal * $customPercentage / 100;
			}else{
				$tip = $subTotal * $percentage / 100;
			}
			$total = $subTotal + $tip;
			$format = '$%.2f';
			echo 'Tip: ', sprintf($format, $tip);
			echo '<br>';
			echo 'Total: ', sprintf($format, $total);
			echo '<br>';
			if($splitPerson > 1){
				echo 'Tip each: ', sprintf($format, $tip / $splitPerson);
				echo '<br>';
				echo 'Total each: ', sprintf($format, $total / $splitPerson);
				echo '<br>';
			}
		}
	?>
 </body>
</html>