
		<div class="graveyard" >
			<?php if(isset($result_set)){
					$stop = 0;
					$display ='<table>';
					confirm_result_set($result_set);
					while($result = mysqli_fetch_assoc($result_set)){	
						if($stop === 0 ){
							$display .= create_table_head($result);
							$stop++;
						}
						$result = fill_empty_cells($result);
						
						if($result['Date_Removed'] !== 'N/A'){
							$result['Date_Removed'] = date_from_sql($result['Date_Removed']);
						};
						
						$display .= create_table_body($result);
						
					};
					
					$display .= '</table>';
					echo($display);
				};
				
				if(isset($firstFloor) && isset($mainFloor) && isset($thirdFloor)){
					$portJSONfirst = '{ "Ports" : ' . json_encode($firstFloor, JSON_NUMERIC_CHECK | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_HEX_APOS) . "}" ; 
					$portJSONfmain = '{ "Ports" : '  . json_encode($mainFloor, JSON_NUMERIC_CHECK | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_HEX_APOS) . "}" ; 
					$portJSONthird = '{ "Ports" : '  . json_encode($thirdFloor, JSON_NUMERIC_CHECK | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_HEX_APOS) . "}" ; 
				};
				
				if(isset($staff)){
					$staffJSON = '{ "Staff" : ' . json_encode($staff, JSON_NUMERIC_CHECK | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_HEX_APOS) . "}" ;
				};
			?>
				
			</table>
		</div>