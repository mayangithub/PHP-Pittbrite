<?php 
	require_once("functions.php");
	include_once("header.php");
	
	if ($_GET['cat']) {
		$cat = htmlspecialchars_decode($_GET['cat']);
	}
	
	if ($_POST['filter']) {
		$catarray = $_POST['cat'];
		$tagarray = $_POST['topic'];
	}
	

	
 ?>
 	<p class="title">Discover Events</p><br />
 	<div id="filter">
 		<form method="post" action="discover.php">
 		<table class="table" id="selectfilter">
 			<thead><th>CATEGORY</th></thead>
 			<tbody><tr><td> 
 					<?php  
 						if ($_POST['filter']) {
 							listAllCatPost($catarray);
 							
 						}else{
 							listAllCatGet($cat);
 						}
 				  ?>
 				</td>
 			</tr></tbody>
 			<thead><th>TOPIC</th></thead>
 			<tbody><tr><td>
					<?php 
						listAllTopic($tagarray);
					 ?>
				</td>	
 			</tr></tbody>
 			
 			<tr>
 				<td><input type="submit" name="filter" value="Filter Result" /></td>
 			</tr>
 		
 		</table>
 		</form>
 	
 	
 	
 	
 	
 	</div>
 	<div id="list">
 		<?php if ($_GET['cat']) {
 			listGetEvents($cat);
 		} else {
 			listPostEvents($catarray, $tagarray);
 		}?>
 		 	
 	</div>
 
 
 
 <?php 
 	include_once("footer.php");
  ?>