<?php
require __DIR__ . "/includes/header.php";
?>
<div style="padding: 0px 5px 0px 5px;">



<div class="highlight">
<br/>
<ul>
	<li><a href="#link_single" style="font-size: 13px; font-weight: bold;">How do I link to a single YoreTude video?</a>
	<br>
	<br>
	<li><a href="#link_list" style="font-size: 13px; font-weight: bold;">How do I link to a list of YoreTude videos?</a>
</ul>
</div>
<br/>
<br/>
<div class="tableSubTitle"><a name="link_single"></a>How to link to a single YoreTude video</div>

<span class="highlight">
</span><span class="emphasisLabel">Go to the video that you want to share, and look for the <span class="standoutLabel">share details</span> section under the video player. There you will see 2 options.</span> <br>
	<br>
<center>
  <table width="760" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="380" valign="top">
        <div align="left"><img src="/img/2OptionsHowTo.jpg"/></div></td>
      <td width="400" valign="top">
        <div align="left">
          <table width="100%" border="0" cellpadding="0" cellspacing="0" class="contentBreaks">
            <tr>
              <td valign="top">                
			  <div align="left"> <span class="standoutLabel">Option 1: Video URL (Permalink)</span>
			  <br/>
                    <img src="/img/PlayerHowTo.gif" style="padding-top:6px; padding-bottom:10px;"/> <br/>
                    <span class="standardCopy"> This URL points directly to the YoreTude page that plays the video. <br/>
                	Copy and paste this URL into an E-mail and send it to your friends.
                	</span> 
				</div>
			  </td>
            </tr>
          </table>
		  <br/>
          <br/> 
          <table width="100%" border="0" cellpadding="0" cellspacing="0" class="contentBreaks">
            <tr>
              <td valign="top">
                <div align="left">
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td>
                        <div align="left"> <span class="standoutLabel">Option 2: Embeddable Player </span></div></td>
                    </tr>
                    <tr>
                      <td valign="top"><img src="/img/EmbeddablePlayer.gif" style="padding-top:6px; padding-bottom:10px;"/></td>
                    </tr>
                    <tr>
                      <td valign="top">
                        <div align="left">Copy and paste this HTML snippet into your website to insert the video player directly into your page. This way visitors will be able to play the video without leaving your site. You may alter the size of the video (by changing the width or height). Example below:<br>
                         
						 <br/>
						 
						  <object width="200" height="164">
                            <param name="movie" value="http://www.yoretude.com/v/TTWAoHeJTPI">
                            <embed src="/v/TTWAoHeJTPI" type="application/x-shockwave-flash" width="200" height="164"></embed>
                          </object>
                        </div>
                   
					  </td>
                    </tr>
                    <tr>
                      <td valign="top">
                        <div align="left"> <span class="standoutLabel">Tip:</span> Put YoreTude videos on <img src="/img/BloggerIcon.gif" width="61" height="21" align="absmiddle"/>&nbsp;&nbsp; <img src="/img/EbayIcon.gif" width="51" height="21"/>&nbsp;&nbsp; <img src="/img/MySpaceIcon.gif" width="81" height="21"/> </div></td>
                    </tr>
                  </table>
              </div></td>
            </tr>
          </table>
          <br/>
          <br/>
      </div></td>
    </tr>
  </table>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
</center>
<a name="link_list"></a>
<div class="tableSubTitle">How do I link to a list of YoreTude videos?</div>

<span class="standoutLabel">
Want to show off more than one of your own YoreTude videos, or specifically tagged videos, on your website? Below are a few options! 
</span>
<br/>
<br/>
<br/>
<span class="standoutLabel">Option 1: Displaying a window of your own YoreTude videos</span> 
<br/>
<br/>
By placing a small snippet of HTML code in your webpage, you can pull up a list of all your YoreTude videos in a neat, little window. Take a look at the example below; on the left is the HTML snippet that you would copy+paste into your webpage.
Remember to replace the part of the code that says &quot;YOUR_USERNAME&quot; with your YoreTude username. 
 As a result, a small box with your videos will be rendered as shown on the right.

	<br>
	<br>

	<table width="790">
	<tr>
		<td valign="top" align="left">
			<br/>
			<img src="/img/MySpaceSnippet.gif" width="183" height="60">
			<br/>

			<textarea cols="55" rows="6" id="snippet_embed" wrap="soft"><embed src="/swf_show/YOUR_USERNAME" quality="high" width="425" height="350" name="myclips" align="middle" allowscriptaccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer"/></textarea>

			<br/>
			<br/>
			<br/>
			<img src="/img/AllOtherSnippet.gif" width="212" height="60">
			<br/>

			<textarea cols="55" rows="6" id="snippet_iframe" wrap="soft"><iframe id="video_list" name="video_list" src="/video_list.php?user=YOUR_USERNAME" scrolling="auto" width="265" height="300" frameborder="0" marginheight="0" marginwidth="0"></iframe></textarea>

	  <br/>      </td>
		<td valign="top" align="left">
			<br/>
			<span class="highlight"><img src="/img/WhatShowsUp.gif" width="124" height="60"></span>
			<br/>
			<iframe id="videos_list" name="videos_list" src="/video_list.php?user=billygoat891" scrolling="auto" width="265" height="300" frameborder="0" marginheight="0" marginwidth="0">
	  </iframe>	  </td>
	</tr>
</table>

	<br/>
	<br/>
	<br/>
	<br/>

	<span class="standoutLabel">Option 2: Creating links</span>
	<br/>
	<br/>
	You can also create links to your profile, your videos, or specifically tagged videos. But as of now you cannot - it is coming soon. 

 Remember to replace the part of the link that says YOUR_USERNAME with your YoreTude username. 
  
 To pull up results for a tag search, simply replace the part of the link that says YOUR_QUERY with a tag term of your choice. For example, if you wanted to pull up results for the tag term wedding the link would be: <a href="//tags/wedding">http://www.yoretude.com/tags/wedding</a></span>
    <br/>
	<br/>

	<table width="790" cellpadding="5" cellspacing="0">
	<tr bgcolor="#999999">
	  <td align="center" valign="top"><div class="brightLabel">Purpose</div></td>
	  <td align="center" valign="top"><div class="brightLabel">Link</div></td>
	</tr>
	<tr bgcolor="#EEEEEE">
		<td align="left" valign="top" style="border: 1px solid #CCCCCC; border-right:none;">To link to your profile page</td>
		<td align="left" valign="top" style="border: 1px solid #CCCCCC;">http://www.yoretude.com/user/YOUR_USERNAME</td>
	</tr>
	<tr>
		<td valign="top" align="left" style="border: 1px solid #CCCCCC; border-right:none; border-top:none; border-bottom:none;">To link to your videos page</td>
		<td valign="top" align="left" style="border: 1px solid #CCCCCC; border-top:none; border-bottom:none;">http://www.yoretude.com/videos/YOUR_USERNAME</td>
	</tr>
	<tr bgcolor="#EEEEEE">
		<td align="left" valign="top" style="border: 1px solid #CCCCCC; border-right:none;">To pull up results for a tag search</td>
		<td align="left" valign="top" style="border: 1px solid #CCCCCC;">http://www.yoretude.com/tags/YOUR_QUERY</td>
	</tr>
</table>

	<br/>
	<br/>
	<br/>
	<br/>


		</div>
		</td>
	</tr>
</table>
