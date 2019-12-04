<?php // Include the pear mail package 
require_once "Mail.php"; 

require_once "recaptchalib.php";  //ADDING NUMBER 1

/** START VARIABLE ASSIGNMENT **/

// URL to redirect to. Leave blank to stay on current page
$redirect = 'http://www.swordfightinginternational.com/email_us_thankyou.html';

$enquiry = "";
$setError= "";         

// Message that should be displayed upon successful form submission
$confirmMessage = 'Thank you for completing our form, we will be in touch with you shortly';

// email address to send form submission details to
switch(strtolower($enquiry)){
        case 'PRISM':
            $to = 'sam@acuitymarketingservices.co.uk, nick.payne@swordfightinginternational.com';
            break;
        case 'standard':
        default:
            $to = 'nick.payne@swordfightinginternational.com';
            break;
    }

// email subject
$subject = 'UK website email';

// default values for form fields
$formDefaults['enquiry'] = 'Nature of enquiry:';
$formDefaults['name'] = 'Name:';
$formDefaults['email'] = 'Email address:';
$formDefaults['phone'] = 'Telephone:';
$formDefaults['group'] = 'Approximate group size:';
$formDefaults['date'] = 'Approximate day/date in mind:';
$formDefaults['comments'] = 'Comments:';
$formDefaults['g-recaptcha-response'] = '';

// name required fields and error messages
$required['name'] = 'Please enter your name.';
$required['email'] = 'Please enter your email address.';
$required['phone'] = 'Please enter a contact number.';
$required['g-recaptcha-response'] = 'Please enter a recaptcha.';

// name of email fields and error messages
$emailFields['email'] = 'Please enter a valid email address for your email address';

/** END VARIABLE ASSIGNMENT **/


$confirm = '';
$errors = array();
$submitName = 'submit';

// prevent XSS
function clean($dirty){
    return htmlentities(strip_tags($dirty));
}

// very simple email validation - just checks for an '@' that is not the first or last character
function isEmailAddress($email){
    return !in_array(strpos($email, '@'), array(false, 0, strlen($email)));
}

function sendEmail($form, $submitName, $to, $subject){
    $body = '';
    $body .= 'UK site contact form submitted with the following details: ' . PHP_EOL . PHP_EOL;
    foreach($form as $k=>$v){
        if($k != $submitName){
            $body .= $k . ' : ' . $v . PHP_EOL;
        }
    }
    $body .= PHP_EOL;

    $from = $to = $replyTo = $to;
    $extra = "From: $from\r\nReply-To: $replyTo\r\n";   
    
	$hardcodefrom = "events@swordfightinginternational.com";
	
	
	// Set your script mail details here
$host = "uk.scriptmail.yourwebservers.com"; 
$username = "swordfightinginternational.com@script.mail"; 
$password = "eX#84_xN"; 

// Set the headers of the message, you to not need to modify this section
$headers = array ('From' => $hardcodefrom, 
'To' => $to, 
'Subject' => $subject); 

// Set the auth details, you do not need to modify this section
$smtp = Mail::factory('smtp', 
array ('host' => $host, 
'auth' => true, 
'username' => $username, 
'password' => $password)); 
	


	/* new script */
// Send the message, you do not need to modify this section
$mail = $smtp->send($to, $headers, $body); 

// Handle the result, you may wish to change this to your needs
//if (PEAR::isError($mail)) { 
//echo("Error: " . $mail->getMessage()); 
//} else { 
//echo("Message Sent"); 
//} 


}




if(isset($_POST[$submitName])){

    // clean
    foreach($_POST as $k=>$v){
        $form[clean($k)] = clean($v);
    }

    foreach($required as $field =>$errorMessage){
        if(empty($form[$field]) || $form[$field] == $formDefaults[$field]){
            $errors[] = $errorMessage;
        }
    }
    
    // new reCaptcha object with secret key
    $reCaptcha = new ReCaptcha("6LeR9wkTAAAAAFGzbqsPjMT4E5OUiB0qrDulIrbJ");

    if ($_POST["g-recaptcha-response"]) {
        $response = $reCaptcha->verifyResponse(
            $_SERVER["REMOTE_ADDR"],
            $_POST["g-recaptcha-response"]
        );
    }

    if(!count($errors)){
        foreach($emailFields as $emailField => $errorMessage){
            if(!empty($form[$emailField]) && !isEmailAddress($form[$emailField])){   ////ADDING NUMBER 5
                $errors[] = $errorMessage;
            }
        }
    }

    if(!count($errors) && $response != null && $response->success){
        
        sendEmail($form, $submitName, $to, $subject);
        
        if(!empty($redirect)){
            header("Location:  $redirect");
        }
        
        $confirm = $confirmMessage;
				
    }
  

}

$form = $formDefaults;

?>
<!doctype html>
<html>
	<head>
		<title>Corporate Team Building Events | Corporate Fencing Events in London and across the UK</title>
		<meta charset="utf-8" />
		<meta name="description" content="Tired of the same old corporate activities? Try our fencing based corporate team building events." />
		<meta name="keywords" content="Team building events, team building activities, team building, corporate, activities, London, ideas, outdoor, indoor, fun, events" />
		<script type="text/javascript" src="js/swfobject.js"></script>
		<link rel="stylesheet" type="text/css" href="css/reset.css" />
		<link rel="stylesheet" type="text/css" href="css/style.css" />
		<link rel="stylesheet" type="text/css" href="css/home-slider.css" />
		<link rel="shortcut icon" href="favicon.ico">
		<link rel="apple-touch-icon" href="favicon.png">
	<script src="js/jquery-1.7.2-min.js" type="text/javascript"></script>
	<script src="js/jquery.cycle.lite.js" type="text/javascript"></script>

    <script type="text/javascript">
	$(document).ready(function(){
 
	$('.imagerotator').cycle({
		delay:  1000,
		speed:  600
	})
	$('.testimonialtxt').cycle({
		delay:  6000,
		speed:  600
	})

 });

    </script>
		<script src="js/formval.js" type="text/javascript"></script>
		
		
		<script src='https://www.google.com/recaptcha/api.js'></script>
		<script type="text/javascript" src="http://www.eventcapture03.com/js/56183.js" ></script>
<noscript><img src="" style="display:none;" /></noscript>
		
	</head>

	<body>
		<div class="container center">
			<div class="header left">
				<img class="left" src="Images/logo.gif" alt="Swordfighting International logo" />
				<p class="strapline right">
                    <span class="phone">Telephone: 07799 346 829</span><br />
					or email <span class="email"><a href="mailto:info@swordfightinginternational.com">info@swordfightinginternational.com</a></span></p>
				<ul class="navigation right">
					<li><a href="index.html" title="SFI team building events &amp; activities">Home</a></li>
					<li><a href="services.html" title="Team building and party ideas">Services</a>
                    	<ul>
                        	<li><a href="corporate-entertainment.html">Corporate Entertainment</a></li>
                            <li><a href="fun-events.html">Fun Events</a></li>
                            <li><a href="get-the-point.html">Get the Point</a></li>
                            <li><a href="team-building.html">Team Building</a></li>
                            <li><a href="/landing/olympic-team-building.html">Olympic-themed team-building</a></li>
							<li><a href="http://swordfightinginternational.com/movie-themed-team-building-in.html">Movie-themed team-building</a></li>
                        </ul>
                    </li>
					<li><a href="biography.html"title="Meet our team building experts">Biographies</a></li>
					<li><a href="links.html" title="Our media, fencing and team building partners">Links</a></li>

					<li><a href="media.html" title="SFI team building events in the media">Media Coverage</a></li>
					<li id="lastnav"><a href="contact.php" title="For more on our team building activities">Contact Us</a></li>
				</ul>
			</div>
			<div class="content left">
				<div class="leftColumn left">
					<div class="bannerImage">
						<div class="imagerotator">
							<img src="images/banner1.jpg" alt="Swordfighting" />
							<img src="images/banner2.jpg" alt="Swordfighting" style="display:none;" />
							<img src="images/banner3.jpg" alt="Swordfighting" style="display:none;" />
							<img src="images/banner4.jpg" alt="Swordfighting" style="display:none;" />
							<img src="images/banner5.jpg" alt="Swordfighting" style="display:none;" />
							<img src="images/banner6.jpg" alt="Swordfighting" style="display:none;" />
							<img src="images/banner7.jpg" alt="Swordfighting" style="display:none;" />
							<img src="images/banner8.jpg" alt="Swordfighting" style="display:none;" />
							<img src="images/banner9.jpg" alt="Swordfighting" style="display:none;" />
							<img src="images/banner10.jpg" alt="Swordfighting" style="display:none;" />
							<img src="images/banner11.jpg" alt="Swordfighting" style="display:none;" />
						</div>
						<h3><span class="small">We come to you, wherever you are in the UK</span><br />Team Building events that Get the Point</h3>
					</div>
					<div class="mainTextarea">
					
					
					
             <h1><strong>Contact Us Today - Ideal for a Fun Company Activity or Christmas Party</strong></h1>
			 <p>Sword Fighting International offer a range of team building games and activities, which we can personalise to suit your team and the purpose of your event. We have worked with a number of blue chip companies, and all of our <a href="biography.html" title="Our team building experts">instructors</a> are experienced in running team building days &amp; corporate activities.</p>
             <ul>
			  <li>Games are fun and encourage teams to work closely</li>
              <li>Great for company away-days; either a fun and unusual conference break activity or as a demo or mini taster sessions as part of a larger party or fun day where fencing is just one of a number of activities</li>
			  <li>A sport that many have thought about, but few have tried</li>
			  <li>We come to you, wherever your team building day or conference is taking place</li>
			  <li>Games held in outdoor &amp; indoor venues</li>
			  <li>Not just fencing - also other Olympic Themed Events including Archery, Shooting Sailing, and Paralympic Activites including Wheelchair Fencing, Sitting Volleyball and Table Tennis.</li>
              <li>And it doesn't stop there either - we offer team building event ideas such as Chocolate Making, Ice Sculpting, Treasure Hunts, Street Art and even an Indiana Jones style Whip Workshop</li>
				<li><a href="corporate-entertainment.html">Ideal for Christmas Party Activities in London and UK wide</a></li>
			 </ul>
			
             <p><strong>Kids Parties</strong></p>
             <p>They're not just for the corporate world either - kids love playing with swords and our parties let them play safely and have lots of fun.</p>
             <a name="getintouchform"></a>
             <p><strong>Please Get in Touch</strong></p>
			 <p>For more information on how we can provide events for you, please fill in the form below, including details of group size and the location of the event.</p>

<br />

								
			<div id="newform" class="myform">
                            <p><?php //print_r($_POST); ?></p>
                            <p><?php //print_r($form); ?></p>
            
            <?php
            if(count($errors)){
                ?><ul class="errors"><?php
                foreach($errors as $error){
                    ?><li><?php echo $error;?></li><?php
                }
                ?></ul><?php
            }
            if(!empty($confirm)){
                ?><p class="confirm"><?php echo $confirm;?></p><?php
            }
        ?>
            
            
                <form id="form" name="emailus" method="post" action="contact.php#form">
			<fieldset>
				<legend>Contact details</legend>
			</fieldset>
            <label for="enquiry"><?php echo $form['enquiry'];?></label>
            <select name="enquiry" id="enquiry">
            	<option>--- Please Select ---</option>
            	<option value="Standard">General Enquiry</option>
            	<option value="PRISM">Get the Point with PRISM Enquiry</option>
           	</select>	
            <label for="name"><?php echo $form['name'];?></label>
            <input name="name" id="name" type="text" />
            
            <label for="email"><?php echo $form['email'];?></label>
            <input name="email" id="email" type="text" />
            
            <label for="phone"><?php echo $form['phone'];?></label>
            <input name="phone" id="phone" type="text" />
            
            <label for="group"><?php echo $form['group'];?></label>
            <input name="group" id="group" type="text"/>
            
            <label for="date"><?php echo $form['date'];?></label>
            <input name="date" id="date" type="text" />
            
            <label for="comments"><?php echo $form['comments'];?></label>
            <textarea id="comments" name="comments"></textarea>
			
			
			<?php if(isset($_GET['c']) || $setError=='c' ){ ?>
				<p style="color: red; font-weight: bold;">Please make sure to check on the "I'm not a robot" box</p>
			<?php } ?>
			
			<div class="g-recaptcha" name="g-recaptcha" data-sitekey="6LeR9wkTAAAAABlFspRxWsVuhwLGcOxbeeIzn3w9"></div>
			
            
            <button type="submit" name="<?php echo $submitName;?>">Submit</button>
			<div class="spacer"></div>
            
			</form>
			</div>
                                   
                                   


									
<br />

	<p><strong>Telephone:</strong></p>
	<p>Alternatively, you can call us to discuss the team building games and events we offer, or for more information on our office and kids' party ideas. Contact <a href="biography.html">Nick</a> on <strong>07799 346 829</strong>. </p>
	<br /><br />
      
</div>
					

										<div class="clear"></div>
					<div class="boxesBottom">
						<div class="boxesContainer left">
							<div class="media left">
								<img src="images/media.gif" alt="Media" />
								<ul class="bullet">
									<li><a href="videos.html">Video clips</a> - from recent team building events</li>
									<li><a href="media.html">SFI in the press</a></li>
									<li><a href="team-building.html">Team building flyer</a></li>
								</ul>
							</div>
	
							<div class="testimonials fontColor right">
								<img src="images/testimonials.gif" alt="Testimonials" />
								<div class="testimonialtxt">
									<div class="testtext1">
									<h3>Investec</h3>
									<p>"Very enjoyable - good to laugh together"</p>
		
									<h3>Merrill Lynch (now BlackRock)</h3>
									<p>"Fun! Surprising and different."</p>
		
									<h3>BAT</h3>
									<p>"Excellent staff and approach. Highly recommended for team building."</p>
									
									<h3>Trading Partners</h3>
									<p>"I would always recommend you to anyone looking for a fun and different challenge."<br />
									<a href="docs/TradingPartners_Testi_A4.pdf" target="_blank">Click here for pictures</a>
									</p>
		

									</div>
									<div class="testtext2">

									<h3>BT Financial</h3>
									<p>"Something different - great fun"</p>
									
									<h3>Spotless</h3>
									<p>"The Sword Fighting was the highlight of the conference.  It was unique and very well run"
									<a href="docs/corporate_swordfighting_photos.pdf" target="_blank">Click here for pictures</a>
									</p>
									
									<h3>Fortune 100 IT Company</h3>
									<p>"Great fun and well organised"<br />
									<a href="docs/Fortune_Testi_A4.pdf" target="_blank">Click here for pictures</a>
									</p>									
									
									<h3>SeeFilmFirst on behalf of Disney</h3> 
									<p>"The whole event was a resounding success"</p>

									</div>
									<div class="testtext3">
									
									<h3>www.samsales.com.au</h3>
									<p>"Interesting and challenging...  We certainly recommend Sword Fighting International to organizations seeking a professionally managed team building and business coaching event with a difference!" </p>
									
									<h3>Action Focus</h3> 
									<p>"I can now see how fencing can help me keep fit as well as develop my strategic skills." </p>
									

									<h3>Merchant Cash Express</h3>
									<p>Enjoyable and informative great session, great fun thanks! <br />
									Thoroughly enjoyed it Director and Team - <a href="http://www.merchantcashexpress.co.uk">Merchant Cash Express</a></p>									
									</div>	
									
								</div>								
								
							</div>
                            
							<div id="ausButton">
                            <a href="http://www.swordfighting.com.au/" target="_blank"><img src="images/Button-Aus.png" width="288" height="144" alt="Click here to visit our Australian site" /></a>
                            
							</div>
						</div>
						
						
						
						
					</div>
				</div>
				<!-- rightColumn -->
				<div class="rightColumn right">
					<div class="googleVideo right borderBottom">
						<div id='flashcontent'><iframe width="286" height="206" src="//www.youtube.com/embed/dCU_bTiBINU?feature=player_detailpage" frameborder="0" allowfullscreen></iframe>
						</div>
					</div>
                    <div class="sidenav borderBottom right">
						<ul>
							<li><a href="biography.html"><img src="images/biography.jpg" alt="Biography" /></a></li>
							<li><a href="links.html"><img src="images/links.jpg" alt="Links" /></a></li>
							<li><a href="services.html"><img src="images/services.jpg" alt="Services" /></a></li>
							<li><a href="contact.php"><img src="images/contact.jpg" alt="Contact" /></a></li>
						</ul>
					</div>
                    
                    <div id="quoteRight">
	                    <img src="images/logo_quote.png" height="95" width="95" alt="Logo of recent happy customer, BMW" />
    	                <p><strong>Recent quote from olympic sponsor BMW&#58;</strong></p>
        	            <div class="clear"></div>
            	        <img src="images/bg_quoteRight.png" height="284" width="24" alt="Background image indicating quote" />
                	    <p id="bmwQuote">Thanks so much for all your hard work and support in Spain. It was a great pleasure to meet you and I know that the media will remember for a very long time (as will I) those fencing battles in the courtyard. Superb. Your enthusiasm and eloquence ensured that everybody got maximum enjoyment out of the encounter with a new sport. I have since been inspired to watch some of those YouTube fencing clips which you suggested. Amazing stuff.&quot;</p>
                	    </div>
					
						<div class="contact fontColor right">
				                   <form accept-charset="UTF-8" action="https://fs163.infusionsoft.com/app/form/process/73e15df3723fc89fd412f4a039847c0f" class="infusion-form" method="POST" name="frm">
								    <input name="inf_form_xid" type="hidden" value="73e15df3723fc89fd412f4a039847c0f" />
								    <input name="inf_form_name" type="hidden" value="New web form - Home page signup Sep14" />
								    <input name="infusionsoft_version" type="hidden" value="1.32.0.71" />
									<img src="images/top5-header.png" height="74" width="286" alt="Newsletter" />
	                                <div class="text" id="webformErrors" name="errorContent"></div>
									<ul>
										<li>
								        <label class="left" for="inf_field_FirstName">First Name *</label>
								        <input class="infusion-field-input-container right" id="inf_field_FirstName" name="inf_field_FirstName" type="text" />
										</li>
	                                    <li>
								        <label class="left" for="inf_field_LastName">Last Name *</label>
								        <input class="infusion-field-input-container right" id="inf_field_LastName" name="inf_field_LastName" type="text" />
	                                    </li>
	                                    <li>
								        <label class="left" for="inf_field_Email">Email *</label>
								        <input class="infusion-field-input-container right" id="inf_field_Email" name="inf_field_Email" type="text" />
										</li>
										<li>
								        <label class="left" for="inf_field_Phone1">Telephone *</label>
								        <input class="infusion-field-input-container right" id="inf_field_Phone1" name="inf_field_Phone1" type="text" />
										</li>
										<li>
								        <label class="left" for="inf_field_Company">Company</label>
								        <input class="infusion-field-input-container right" id="inf_field_Company" name="inf_field_Company" type="text" />
										</li>
										<li>
	                                        <button type="submit" value="Submit">Submit</button>
										</li>
									</ul>
								</fieldset>
							</form>
	                    </div>
	
						<div class="clear"></div>
	
						<div id="contact">
	                    	<p>Whatever you choose<br />we will make it memorable</p>
	                        	<div id="telephone">
	                            	<p>Call now&#58; 07799 346 829</p>
	                            </div>
							<p>Corporate events that get the point!</p>
	                    </div>
						
						<div class="social fontColor right">
	                    	<a href="http://www.linkedin.com/company/1103823" target="_blank"><img src="images/linkedin-icon.jpg" height="66" width="66" alt="Click here to link with us on LinkedIn" /></a>
	                        <a href="http://www.youtube.com/user/teambuildingfencing?feature=mhee" target="_blank"><img src="images/youtube-icon.jpg" height="66" width="66" alt="Click here to view our YouTube channel" /></a>
	                        <a href="https://www.facebook.com/pages/Sword-Fighting-International-UK/191139314249161" target="_blank"><img src="images/facebook-icon.jpg" height="66" width="66" alt="Click here to join us on Facebook" /></a>
	                        <a href="https://twitter.com/#!/swordfighting1/team-building" target="_blank"><img src="images/twitter-icon.jpg" height="66" width="66" alt="Click here to follow us on Twitter"/></a>
						</div>
	                    
	                    <div class="clear"></div>

						<a href="get-the-point.html"><img class="prism-img" src="images/prism-practitioner.jpg" width="286" height="152" alt="We are a certified practioner of PRISM brain mapping - click to find out more" /></a>
					</div>
    	         </div><!-- end rightColumn -->


                <div class="slider-row">
            		<h4 class="title-hr">Some clients we have worked with</h4>
					<div id="clients-slider" class="flexslider">
						<ul class="slides">
                        	<li><img src="images/client-logos/admiral.gif" alt="Admiral Logo"/></li>
                            <li><img src="images/client-logos/bat.gif" alt="British American Tobacco Logo"/></li>
                            <li><img src="images/client-logos/blackrock.gif" alt="BlackRock Logo"/></li>
                            <li><img src="images/client-logos/bt.gif" alt="BT Logo"/></li>
                            <li><img src="images/client-logos/capgemini.gif" alt="CapGemini Logo"/></li>
                            <li><img src="images/client-logos/celgene.gif" alt=" Logo"/></li>
                            <li><img src="images/client-logos/cisco.gif" alt="Celgene Logo"/></li>
                            <li><img src="images/client-logos/citibank.gif" alt="Cisco Logo"/></li>
                            <li><img src="images/client-logos/dell.gif" alt="Citibank Logo"/></li>
                            <li><img src="images/client-logos/ebay.gif" alt="Ebay Logo"/></li>
                            <li><img src="images/client-logos/goldman-sachs.gif" alt="Goldman Sachs Logo"/></li>
                            <li><img src="images/client-logos/gsk.gif" alt="GSK Logo"/></li>
                            <li><img src="images/client-logos/ibm.gif" alt="IBM Logo"/></li>
                            <li><img src="images/client-logos/pandg.gif" alt="P&amp;G Logo"/></li>
                            <li><img src="images/client-logos/santander.gif" alt="Santander Logo"/></li>
                            <li><img src="images/client-logos/yougov.gif" alt="YouGov Logo"/></li>
                        </ul>
					</div><!--end client-stories-->
				</div><!--end col-4-->

                        <div class="clear"></div>
			<div class="footer left">
				<p>
					Telephone: 07799 346 829&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Email: <a href="mailto:nick.payne@swordfightinginternational.com" class="grey">nick.payne@swordfightinginternational.com</a>
				</p>
				<p class="right australia"></p>
			</div>
		</div></div>
<script src="js/homepage-slider.js" type="text/javascript"></script>

<script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
</script>
<script type="text/javascript">
_uacct = "UA-2220865-1";
urchinTracker();
</script>
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 1057878166;
var google_custom_params = window.google_tag_params;
var google_remarketing_only = true;
/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/1057878166/?value=0&amp;guid=ON&amp;script=0"/>
</div>
</noscript>
</body>
</html>