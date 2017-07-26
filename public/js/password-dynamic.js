$( document ).ready(function() {

	function init()
	{
		$('#pwdLength').attr('class', 'default');
		$('#pwdLower').attr('class', 'default');
		$('#pwdUpper').attr('class', 'default');
		$('#pwdNumber').attr('class', 'default');
		$('#pwdSpecial').attr('class', 'default');
		$('#pwdHacker').attr('class', 'default');
		$('#pwdDict').attr('class', 'default');	

		//reset entropy content
		$('.password-entropy').hide();
		$('.password-entropy-suggestion').hide();	

		$('#entropy-rating').addClass('loading');
		$('#entropy-duration').addClass('loading');

		$('#entropy-rating').html("");
		$('#entropy-duration').html("");
		$('.password-entropy-suggestion').html('<p>Follow the above simple recommendations will help making your password take at least <span id="entropy-duration-suggestion" class="loading"></span> to guess.</p>');
	}

	function loadingDynamicList()
	{
		$('#pwdLength').attr('class', 'loading');
		$('#pwdLower').attr('class', 'loading');
		$('#pwdUpper').attr('class', 'loading');
		$('#pwdNumber').attr('class', 'loading');
		$('#pwdSpecial').attr('class', 'loading');
		$('#pwdHacker').attr('class', 'loading');
		$('#pwdDict').attr('class', 'loading');

		//show entropy content
		$('.password-entropy').show();
		$('.password-entropy-suggestion').show();
	}

	function checkPassword(password)
	{
		//jquery way of checking password criteria

		//password length is more than 8 letters
		$('#pwdLength').attr('class', 'success');

		//check if password has lowercase
		if(password.toUpperCase() != password)
		{
			$('#pwdLower').attr('class', 'success');

		}
		else
		{
			$('#pwdLower').attr('class', 'fail');
		}

		//check if password has uppercase
		if(password.toLowerCase() != password)
		{
			$('#pwdUpper').attr('class', 'success');

		}
		else
		{
			$('#pwdUpper').attr('class', 'success');
		}	
	}

	$( "body" ).on( "keyup", "input#password", function(event) {
		$("#password-rating").hide();
		$("#password-bar").hide();


		var password = $(this).val();
		if(password.length >= 8){

			loadingDynamicList();	
			checkPassword(password);

			//ajax
			$.ajax({
			    type: 'POST',
			    url: url,
			    data: { 
			        '_token': token,
			        'password': password
			    },
			    success: function(results){
			    	pwd_errors = results['errors'];
			    	pwd_entropy = results['entropy'];
			    	/*if there is any errors, set password meter to weak.
			    	if(Object.keys(pwd_errors).length > 0)
			    	{
	    		        $("#password-rating").html("Weak");
		        		$("#password-bar").attr('class', 'weak');
			    	} */

			    	//if password length less than 8 letters
	        		if(pwd_errors['pwdLength'])
	        		{
	        			$('#pwdLength').attr('class', 'fail');
	        		}
	        		else
	        		{
	        			$('#pwdLength').attr('class', 'success');
        			}	

			    	//if password dont have uppercase letter
	        		if(pwd_errors['pwdUpper'])
	        		{
	        			$('#pwdUpper').attr('class', 'fail');
	        		}
	        		else
	        		{
	        			$('#pwdUpper').attr('class', 'success');
        			}	

			    	//if password dont have lowercase letter
	        		if(pwd_errors['pwdLower'])
	        		{
	        			$('#pwdLower').attr('class', 'fail');
	        		}
	        		else
	        		{
	        			$('#pwdLower').attr('class', 'success');
        			}	

			    	//if password dont have number
	        		if(pwd_errors['pwdNumber'])
	        		{
	        			$('#pwdNumber').attr('class', 'fail');
	        		}
	        		else
	        		{
	        			$('#pwdNumber').attr('class', 'success');
        			} 

			    	//if password dont have special character
	        		if(pwd_errors['pwdSpecial'])
	        		{
	        			$('#pwdSpecial').attr('class', 'fail');
	        		}
	        		else
	        		{
	        			$('#pwdSpecial').attr('class', 'success');
        			}        			        				        		   

			    	//if is in hacker list
	        		if(pwd_errors['pwdHacker'])
	        		{
	        			$('#pwdHacker').attr('class', 'fail');
						$("#password-rating").html("Weak");
		        		$("#password-bar").attr('class', 'weak');	        			
	        		}
	        		else
	        		{
	        			$('#pwdHacker').attr('class', 'success');
	        		}
        			//if is in dictionary list
	        		if(pwd_errors['pwdDict'])
	        		{
	        			$('#pwdDict').attr('class', 'fail');
						$("#password-rating").html("Weak");
		        		$("#password-bar").attr('class', 'weak');		        			
	        		}
	        		else
	        		{
	        			$('#pwdDict').attr('class', 'success');
	        		}	

        			$("#password-rating").show();
	        		$("#password-bar").show();


	        		//check if there is any entropy
	        		if(pwd_entropy)
	        		{
	        			$('#entropy-rating').removeClass('loading');
	        			$('#entropy-duration').removeClass('loading');

	        			$("#password-rating").html(pwd_entropy['rating']);
	        			$("#password-bar").attr('class', pwd_entropy['rating'].toLowerCase());

	        			$('#entropy-rating').html(pwd_entropy['rating']);
	        			$('#entropy-duration').html(pwd_entropy['duration']);
	        			if(pwd_entropy['durationSuggestion']){
	        				$('.password-entropy-suggestion').html('<p>Follow the above simple recommendations will help making your password take at least <span id="entropy-duration-suggestion">'+pwd_entropy['durationSuggestion']+'</span> to guess.</p>');
	        			}
	        			else
	        			{
        					$('.password-entropy-suggestion').html('<p>Excellent, you have followed all the above suggestion.</p>');
	        			}	        			
	        		}
			    }
			});  
		}
		else
		{
			//reset list to default
			init();
			$("#password-rating").show();
			$("#password-bar").show();

			//password length shorter than 8
			if (password.length > 0){
				$('#pwdLength').attr('class', 'fail');
			}
		}
	});
});