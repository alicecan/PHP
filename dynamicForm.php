<!DOCTYPE html>

<html>
	<head>
		<meta charset="utf-8">
		<title>Registration</title>
		<style type="text/css">
			.error	{ color: red }
			table 	{ border: 1px solid gray;
					  border-collapse: collapse;
					  width: 80%; }
			th		{ padding: 5px;
					  background-color: lightgreen; }
			td		{ padding: 5px; }
			tr,td	{  border: 1px solid gray; }
		</style>
	</head>
	<body>
		<h3>Leave Your Message Here:</h3>
		<hr>
		
		<!-- html form -->
		<form method="post" action="dynamicForm.php">
			<p><label>Name:
				<input type="text" name="name">
			</label></p>

			<p><label>Subject:
				<input type="text" name="subj">
			</label></p>
			
			<p><label>Message:<br />
				<textarea name="message" rows="5" cols="36"></textarea>
			</label></p>
			
			<p><input type="submit" name="submit" value="Submit"> 
			<input type="reset" name="reset" value="Reset"></p>
		</form>
		<hr>
		
		<!-- message table -->
		<table>
			<caption>Messages received:</caption>
			<tr>
				<th>No.</th>
				<th>Name</th>
				<th>Subject</th>
				<th>Message</th>
			</tr>
		
		<!-- post user form entry into form -->
		<?php
			// variables used in script
			$name = isset($_POST["name"]) ? $_POST["name"] : "";
			$subj = isset($_POST["subj"]) ? $_POST["subj"] : "";
			$msg = isset($_POST["message"]) ? $_POST["message"] : "";
			$iserror = false;
			$formerrors = array("nameerror"=>false, "msgeerror"=>false);
			
			// when submit button is clicked
			if (isset($_POST["submit"])) 
			{
				// check if the fields are empty
				if ($name == "")
				{
					$formerrors["namerror"] = true;
					$iserror = true;
				}
				if ($msg == "")
				{
					$formerrors["msgrror"] = true;
					$iserror = true;
				}
				
				// if no error
				if (!$iserror) {
					// connect to sql
					if (!($database = mysql_connect("localhost", "iw3htp", "password")))
						die("<p>Could not connect to database</p>");
						
					// open database
					if (!mysql_select_db("message", $database))
						die("<p>Could not connect to database</p>");
					
					// execute query in database: insert message
					$insert_query = "INSERT INTO guests (name, subject, msg)" .
						"VALUES ('$name', '$subj', '$msg')";
					$insert_result=mysql_query($insert_query, $database);
					if (!$insert_result)
					{
						print("<p>Could not execute query!</p>");
						die(mysql_error() . "</body></html>");
					} else {
						print("<p>Hi $name. Message sent.</p>");
					}
					
					// execute query in database: show all messages
					$select_query = "SELECT * from guests order by id";
					$select_result=mysql_query($select_query, $database);
					if (!$select_result)
					{
						print("<p>Could not execute query!</p>");
						die(mysql_error() . "</body></html>");
					} 
					
					// display messages in table form
					for ($counter=0; $row=mysql_fetch_row($select_result); ++$counter)
						{ 
							print("<tr>");
							foreach ($row as $key=>$value)
							print("<td>$value</td>");
							print("</tr>");	
						}
	
					mysql_close($database);
					die();
				}				
			}
			header("location: dynamicForm.php");
			exit;
		?>
		</table>
		</body>
</html>