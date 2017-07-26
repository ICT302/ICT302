var strPassword;
var charPassword;
var passwordRating = $("#password-rating");
var passwordBar = $("#password-bar");
var minPasswordLength = 8;
var baseScore = 0, score = 0;
 
var num = {};
num.Excess = 0;
num.Upper = 0;
num.Numbers = 0;
num.Symbols = 0;
 
var bonus = {};
bonus.Excess = 3;
bonus.Upper = 4;
bonus.Numbers = 4;
bonus.Symbols = 5;
bonus.Combo = 0; 
bonus.FlatLower = 0;
bonus.FlatUpper = 0;
bonus.FlatNumber = 0;

$( document ).ready(function() {

	$("input#password").bind("keyup", checkPassword);
	$( "body" ).on( "focus", "input#password", function(event) {
		$('#password-meter').show();
	});
	$( "body" ).on( "blur", "input#password", function(event) {
		$('#password-meter').hide();
	});
	function init()
	{
	    strPassword= $("input#password").val();
	    charPassword = strPassword.split("");
	         
	    num.Excess = 0;
	    num.Upper = 0;
	    num.Numbers = 0;
	    num.Symbols = 0;
	    bonus.Combo = 0; 
	    bonus.FlatLower = 0;
	    bonus.FlatNumber = 0;
	    baseScore = 0;
	    score =0;
	}
	function checkPassword()
	{
		init();		
	    if (charPassword.length >= minPasswordLength)
	    {
	        baseScore = 50; 
	        analyzePassword();    
	        calcComplexity();       
	    }
	    else
	    {
	        baseScore = 0;
	    }
	     
	    outputResult();
	}

	function analyzePassword()
	{
	    for (i=0; i<charPassword.length;i++)
	    {
	        if (charPassword[i].match(/[A-Z]/g)) {num.Upper++;}
	        if (charPassword[i].match(/[0-9]/g)) {num.Numbers++;}
	        if (charPassword[i].match(/^[!@#$%^&*()_+-=\[\]{};':"\\|,.<>\/?]+$/)) {num.Symbols++;}
	    }
	     
	    num.Excess = charPassword.length - minPasswordLength;
	     
	    if (num.Upper && num.Numbers && num.Symbols)
	    {
	        bonus.Combo = 25; 
	    }
	 
	    else if ((num.Upper && num.Numbers) || (num.Upper && num.Symbols) || (num.Numbers && num.Symbols))
	    {
	        bonus.Combo = 15; 
	    }
	     
	    if (strPassword.match(/^[\sa-z]+$/))
	    { 
	        bonus.FlatLower = -15;
	    }
	     
	    if (strPassword.match(/^[\s0-9]+$/))
	    { 
	        bonus.FlatNumber = -50;
	    }

	    if (strPassword.match(/^[\SA-Z]+$/))
	    { 
	        bonus.FlatUpper = -50;
	    }
	}

	function calcComplexity()
	{
	    score = baseScore + (num.Excess*bonus.Excess) + (num.Upper*bonus.Upper) + (num.Numbers*bonus.Numbers) + 
		(num.Symbols*bonus.Symbols) + bonus.Combo + bonus.FlatLower + bonus.FlatNumber + bonus.FlatUpper; 
	}

	function outputResult()
	{

	    if ($("input#password").val()== "")
	    { 
	        passwordRating.html("");
	        passwordBar.attr('class', 'default');
	    }
	    else if (charPassword.length < minPasswordLength)
	    {
	        passwordRating.html("Too short");
	        passwordBar.attr('class', 'weak');
	    }
	    else if (score<50)
	    {
	        passwordRating.html("Weak");
	        passwordBar.attr('class', 'weak');	        
	    }
	    else if (score>=50 && score<75)
	    {
	        passwordRating.html("Fair");
	        passwordBar.attr('class', 'fair');		        
	    }
	    else if (score>=75 && score<100)
	    {
	        passwordRating.html("Good");
	        passwordBar.attr('class', 'good');		        
	    }
	    else if (score>=100)
	    {
	        passwordRating.html("Strong");
	        passwordBar.attr('class', 'strong');		        
	    }
	}

});