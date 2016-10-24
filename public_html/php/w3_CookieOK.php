
<script>
  function SetCookieOK() {
    document.cookie="CookieOK=1; expires=Thu, 18 Dec 2053 12:00:00 UTC; path=/";
    document.getElementById('form_CookieOK').style.display='none';	
  }
</script>


<form action='#' method='post' id='form_CookieOK'>
    <style type="text/css" scoped>  
        #CookieOK {position:fixed; width: 100%;  height:30px; margin:0; padding:4px; bottom:0; z-index:99; 
               background-color:rgba(0, 0, 0, 0.8); color:#fff;
               border:1px solid #fff;
              }	  
        @media only screen and (max-width : 580px)  {     
		#CookieOK {height:60px;}
	}	   
        @media only screen and (max-width : 320px)  {     
		#CookieOK {height:90px;}
	}	
    </style>	
    <div id='CookieOK'>
        Ahogy a legtöbb honlap, ez a webhely is használ sütiket a weboldalain.
        <button type="button" onclick="SetCookieOK()" >Elfogadom</button>
    </div>
</form>