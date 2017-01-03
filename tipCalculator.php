<html>
 <head>
  <title>Tip Calculator</title>
 </head>
 <body>
 <?php
	//initialization
	$subTotal = "";
	$percentage = 15;
	$customPercentage = "";
	$splitPerson = 1;
	$subTotalErr = $percentageErr = $splitErr = false;
	$subTotalErrMessage = $percentageErrMessage = $splitErrMessage = "";

	if ($_SERVER["REQUEST_METHOD"] == "POST") {//only validate the form input with POST method
	  	//validate subtotal input
	  	if (empty($_POST["subTotal"])) {//check if empty
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
	  	//validate percentage input
	   	if (empty($_POST["percentage"])) {//check if empty
	    	$percentageErr = true;
	    	$percentage = 15;
	    	$percentageErrMessage = " You must select a tip percentage";
	  	} else {
	    	$percentage = $_POST["percentage"];
		    if($percentage == "custom"){//check if the user selected the custom percentage
		    	$customPercentage = process_input($_POST["custom"]);
		    	if (!is_numeric($customPercentage) || $customPercentage <= 0) {//validate custom percentage
		      		$percentageErr = true; 
		      		$percentageErrMessage = " Custom percentage must be a numeric value greater than 0";
		    	}
	    	}
	  	}
	    //validate split person input
	    if (empty($_POST["splitPerson"])) {//check if empty
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

	function process_input($data) {//helper function for preprocess text input
	  $data = trim($data);
	  $data = stripslashes($data);
	  $data = htmlspecialchars($data);
	  return $data;
	}
	?>
 	<h2>Tip Calculator</h2>
 	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
 		<p <?php if($subTotalErr){ echo 'style="color:red;"';} //check error ?> >
 		Bill subtotal: $<input type="text" name="subTotal" value="<?php echo $subTotal;?>" />
 		<?php if($subTotalErr){ echo $subTotalErrMessage;} //output error message ?>
 		</p>

 		<p <?php if($percentageErr){ echo 'style="color:red;"';} //check error ?> >
 		Tip percentage:<br>
 		<?php
 		for ($i = 10; $i <= 20; $i += 5) {//using loop to generate 3 radio buttons
    		echo '<input type="radio" name="percentage" value="',$i,'"';
    		if($percentage == $i) echo 'checked';
    		echo '> ',$i,'%';
		}
		?>
		<br>
		<input type="radio" name="percentage" value="custom" <?php if($percentage == "custom") echo 'checked'; ?> >
		Custom: 
		<input type="text" name="custom" value="<?php echo $customPercentage;?>" />%.
		<?php if($percentageErr){ echo $percentageErrMessage;} //output error message ?>
  		<br>
  		</p>

  		<p <?php if($splitErr){ echo 'style="color:red;"';} //check error ?> >
 		Split: <input type="text" name="splitPerson" value="<?php echo $splitPerson;?>" /> person(s).
 		<?php if($splitErr){ echo $splitErrMessage;} //output error message ?>
 		</p>
  		
 		<p><input type="submit" /></p>
	</form>

	<?php
		$tip = $total = 0;
		//only output results when using POST and without errors
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
			if($splitPerson > 1){//only output split result when split person number greater than 1
				echo 'Tip each: ', sprintf($format, $tip / $splitPerson);
				echo '<br>';
				echo 'Total each: ', sprintf($format, $total / $splitPerson);
				echo '<br>';
			}
		}
	?>
 </body>
</html>