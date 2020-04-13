<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name=description content="COVID-19 Estimator, Andela-facebook challenge"/>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>COVID-19 Impact Estimator</title>
<style>
	div.form_content {
		width:100%;
		text-align: right;
	}
	
	div.form_content table {
		margin-left:auto; 
		margin-right:auto;
		margin-top: 100px;
	}
			
	.label {
		text-align: right;
		font-family: Calibri,Candara,Segoe,Segoe UI,Optima,Arial,sans-serif;
		font-size: 16px;
		font-weight: bold;
		width:125px;
	}
	
	input, select {
		border: 1px solid #DDDDDD;
		padding:8px;
		font-size: 16px;
    	color:#333333;
		width:100%;
	}
	
	.error {
		font-size:14px;
		color:#721C24;
		padding:8px;
		background:#F8D7DA;
		width:100%;
		text-align:center;
	}
	
	span.msg {
		font-size:12px;
		color:#721C24;
		width:100%;
		text-align:left;
		font-weight:bold;
	}
	
	button {
		width:200px;
		height:54px;
		font-size:18px;
		margin:10px;	
	}
	
</style>
<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
</head>
<body>
	<div class="form_content" align="center">
    	<table cellpadding="8" cellspacing="0" width="30%">
        	<tr>
            	<td colspan="2">
                	<div id="result"></div>
                </td>
            </tr>
        	<tr>
            	<td class="label">
                	<label for="population">Population</label>
                </td>
                <td><input type="text" id="population" class="input number" data-population name="population" /></td>
            </tr>
            <tr>
            	<td class="label">
                	<label for="time-to-elapse">Time to elapse</label>
               	</td>
                <td><input type="text" id="time-to-elapse" class="input number" data-time-to-elapse name="timeToElapse" /></td>
            </tr>
            <tr>
            	<td class="label">
                	<label for="reported-cases">Reported cases</label>
               	</td>
                <td><input type="text" id="reported-cases" class="input number" name="reportedCases" data-reported-cases /></td>
            </tr>
            <tr>
            	<td class="label">
                	<label for="total-hospital-beds">Total hospital beds</label>
               	</td>
                <td><input type="text" id="total-hospital-beds" class="input number" name="totalHospitalBeds" data-total-hospital-beds /></td>
            </tr>
            <tr>
            	<td class="label">
                	<label for="period-type">Period type</label>
              	</td>
                <td>
                	<select name="periodType" id="period-type" class="input" data-period-type>
                    	<option value="">-Select period type-</option>
                    	<option value="days">Days</option>
                    	<option value="weeks">Weeks</option>
                    	<option value="months">Months</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                	<button type="submit" id="go-estimate" name="goestimate" data-go-estimate>Estimate</button>
                </td>
            </tr>
        </table>
    </div>
</body>
<script type="application/ecmascript">
	$('#go-estimate').on('click', function(){
			var formdata = {};
			// validate fields
			var passedCheck = true;
			$('.input').each(function() {
                if($.trim($(this).val()) === ''){
					$(this).css('border', 'solid 3px #FFE79B');
					passedCheck = false;
					msg = '<i>Please enter the '+$(this).attr('id').replace(/\-/g, ' ')+'</i>';
					$(this).parent('td').children('span.msg').length? $(this).parent('td').children('span.msg').html(msg) : $(this).parent('td').append('<span class="msg">'+msg+'</span>');					
				}else if($(this).hasClass('number') && (/[^\d.]/g.test(this.value))){
					$(this).css('border', 'solid 3px #FFE79B');
					passedCheck = false;
					msg = '<i>The '+$(this).attr('id').replace(/\-/g, ' ')+' should be entered as a number</i>';
					$(this).parent('td').children('span.msg').length? $(this).parent('td').children('span.msg').html(msg) : $(this).parent('td').append('<span class="msg">'+msg+'</span>');
				} else {
					$(this).css('border', '1px solid #DDDDDD');
					$(this).parent('td').children('span.msg').remove();
					formdata[$(this).attr('name')] = $(this).val();
				}
            });
			
			if(!passedCheck){				
				$('#result').html('Please correct highlighted fields');
				if(!$('#result').hasClass('error')) $('#result').addClass('error');				
			} else {
				
				formdata['goestimate'] = 'goestimate';
				
				$.ajax({
					type: "POST",
					url: 'src/estimator.php',
					data: formdata,
					beforeSend: function() {
						$('#result').html('Calculating estimates...');
						if($('#result').hasClass('error')) $('#result').removeClass('error');	
					},
					error: function(xhr, status, error) {
						$('#result').html('An error occured. Please try again');
						if(!$('#result').hasClass('error')) $('#result').addClass('error');	
					},
					success: function(data) {
						$('#result').html('<div style="text-align: left">'+data)+'</div>';
					}
				});
			}
		});
</script>
</html>