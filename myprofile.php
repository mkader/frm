<?php
$sname = "";
$editable =1;
$susername = "";
$spassword = "";
?>
<table border=1 align="left">
	<tr>
			<td  colspan="2" align="left" class="titreSection">Change Password</td>
	</tr>
	<tr>
			<td align="right" class="NormalTextBold">Name:<font color=red>*</font></td>
			<td nowrap  align="left" >
				<input class="NormalTextBoxGray" type="text" name="sname" id="sname" value="<?php echo $sname ?>" <?php echo $editable; ?>>
				<span id="reqsname" style="display:none;color:Red">required</span>
				<span id="validsname" style="display:none;color:Red">invalid format</span>
			</td>
	</tr>
	<tr>
		<td align="right" class="NormalTextBold">Username:<font color=red>*</font></td>
		<td nowrap  align="left" ><input class="NormalTextBoxGray" type="text" name="susername" id="susername" value="<?php echo $susername ?>"  <?php echo $editable; ?>></td>
	</tr>
	<tr>
		<td align="right" class="NormalTextBold">Password:<font color=red>*</font></td>
		<td  align="left"><input class="NormalTextBox" type="password" name="spassword" id="spassword" value="<?php echo $spassword ?>" >
			<span id="reqspassword" style="display:none;color:Red">required</span>
		</td>
	</tr>
	<tr>
		<td nowrap align="right" class="NormalTextBold">Confirm Password:<font color=red>*</font></td>
		<td  align="left"><input class="NormalTextBox" type="password" name="scpassword" id="scpassword" value="<?php echo $spassword ?>" >
			<span id="reqscpassword" style="display:none;color:Red">required</span>
			<span id="matchspassword" style="display:none;color:Red">password not matched</span>
		</td>
	</tr>
	<tr><td></td><td  align="left"><input class="DefaultButton" type="submit" name="btnSubmit" id="btnSubmit" value="Modify" onclick="return myprofilevalidation(); userSignup();"></td></tr>
</table>
