
		<div class="graveyard" >
		<button type="button" id="closeGraveyard">X</button>
			<?php if(isset($result_set)){
					
					$stop = 0;
					$display ='<table>';
					confirm_result_set($result_set);
					while($result = mysqli_fetch_assoc($result_set)){
						//Paying the price for having reordered my columns in a dumb way.
						$a = array_pop($result);	
						$b = array_pop($result);
						$c = array_pop($result);							
						$result['Purchase_Date'] = $a;
						$result['Origin_Table'] = $b;
						$result['Recallable'] = $c;
							
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
					$portJSONmain = '{ "Ports" : '  . json_encode($mainFloor, JSON_NUMERIC_CHECK | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_HEX_APOS) . "}" ; 
					$portJSONthird = '{ "Ports" : '  . json_encode($thirdFloor, JSON_NUMERIC_CHECK | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_HEX_APOS) . "}" ; 
				};
				
				if(isset($lenoxUpper) && isset($lenoxLower)){
					$portJSONupper = '{ "Ports" : '  . json_encode($lenoxUpper, JSON_NUMERIC_CHECK | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_HEX_APOS) . "}" ;
					$portJSONlower = '{ "Ports" : '  . json_encode($lenoxLower, JSON_NUMERIC_CHECK | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_HEX_APOS) . "}" ;
				};
				
				if(isset($staff)){
					$staffJSON = '{ "Staff" : ' . json_encode($staff, JSON_NUMERIC_CHECK | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_HEX_APOS) . "}" ;
				};
			
			?>
			
				
			</table>
		</div>