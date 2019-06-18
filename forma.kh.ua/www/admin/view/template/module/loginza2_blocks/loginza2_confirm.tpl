<style>	
#loginzaoverlay {

			height:100%;
			width:100%;
			position:fixed;
			left:0;
			top:0;
			z-index:99999 !important;
			background-color:black;
			
			filter: alpha(opacity=75); /* internet explorer */
			-khtml-opacity: 0.75;      /* khtml, old safari */
			-moz-opacity: 0.75;       /* mozilla, netscape */
			opacity: 0.75;           /* fx, safari, opera */
}

#loginzabox
{
	background: #fff;
	z-index:999990 !important;
	border-radius: 15px;
}

</style>
<div id="loginzaoverlay" style="display: block; opacity: 0.5; cursor: pointer;"></div>
<div id="loginzabox" class="" style="display: block; padding-bottom: 57px; padding: 15px; top: 50px; left: 368px; position: absolute; width: 500px; height: #divframe_height#px;"
>


<iframe src="#frame_url#" style="border: 0px; width: 490px; height: #frame_height#px;"></iframe> 

<a href="#lastlink#"
	style="text-decoration: none; font-size: 30px; position: absolute; top:15px; right: 20px;">X</a>
</div>