<div id="updateForms" class="sidebar">
		<button type="button" id="closeForm">X</button>
			<form id="updateDB" method="post" action=" <?php echo($thisFile);?>">
				<h4>Port:</h4>
				<input type="number" id="portID" name="portID" ></input>
				<label for="portName">Name:</label>
				<input type="text" id="portName" name="portName"></input>
				</br>
				<label for="status">Port Status:</label>
				</br>
				<label for="On">On</label>
				<input type="radio" id="On" name="status" value="1" checked="false"></input>
				</br>
				<label for="Off">Off</label>
				<input type="radio" id="Off" name="status" value="0" checked="false"></input>
				</br>
				<label for="broken">Broken:</label>
				<input type="checkbox" id="broken" name="broken" ></input>
				</br>
				<div class="error" id="connectionError">
					<p>Do not connect devices to ports that are turned off or broken.</br> 
					Please double-check the status or move the device.</p>
				</div>
				<div class="error" id="portInUse">
					<p>This port is being used by another device. Please select an available port or cancel this operation and move the device.</p>
				</div>

				<h4>Device:</h4>
				<input type="number" id="deviceID" name="deviceID"></input>
				<label for="deviceName">Name:</label>
				<input type="text" id="deviceName" name="deviceName" readonly></input>
				</br>
				<label for="model">Model:</label>
				<input type="text" id="model" name="model" readonly></input>
				</br>
				<label for="nonCap">NonCap:</label>
				<input type="text" id="nonCap" name="nonCap" readonly></input>
				</br>
				</br>
				<h5>Additional Info</h5>
				<caption>These fields only apply to printers and scanners.</caption>
				</br>
				<label for="vendor">Vendor:</label>
				<input type="text" id="vendor" name="vendor" readonly></input>
				</br>
				<label for="vendorName">Vendor Identifier:</label>
				<input type="text" id="vendorName" name="vendorName" ></input>
				</br>
				<caption for="vendorName"><small>This is how the vendor will refer to the device.</small></caption>
				</br>
				<div  id="morgue" >
					<span>
						<label for="dateRemoved">When was this device removed?</label>
						<input type="date" id="dateRemoved" name="dateRemoved" placeholder="MM/DD/YYYY"></input>
						</br>
						<label for="sentTo">Where did we send it?</label>
						<select id="sentTo" name="sentTo">
							<option value="iCIT">iCIT</option>
							<option value="Surplus">Surplus</option>
							<option value="Other">Other(Please Specify)</option>
						</select>
						
					</span>
					</br>
					<label for="notes">Notes:</label>
					</br>
					<textarea id="notes" name="notes" placeholder="Include other relavant info here.">
					</textarea>
				</div>
				<div class="buttons">
					<button type="submit" id="updateButton">Update</button>
					<button type="button" id="move">Move</button>
					<button type="button" id="delete">Remove</button>
					<button type="button" id="new">New Device</button>
					<input type="checkbox" id="phpDelete" name="phpDelete" checked="false"></input>
				</div>
				
				<div id="instructions">
					
				</div>
					
			</form>
		</div>
