<?php
require('include/global_data.php');
$html_title = 'Contact Information';
require('top.php');
?>

<div id="content" style="background-image:url(images/bg_contact.jpg);">

<h1>Frequently Asked Questions</h1>

<dl class="faq">
	<dt>How do I create an account?</dt>
	<dd>
		Just use your favorite email address when creating your first posting and make up a password.
		Your account will be created automatically if it doesn't already exist. Use the same email/password
		for all subsequent postings and to manage your account.
	</dd>
	
	<dt>My email/password does not work. What should I do?</dt>
	<dd>
		Try to <a href="<?php echo $_SERVER['PHP_SELF']?>?content=my&type=pass">reset your password</a>.
	</dd>
	
	<dt>An email that I received looks suspicious, what should I do?</dt>
	<dd>
		Don't do anything, just ignore it. Please <strong>do not</strong> notify us about the problem.
	</dd>
</dl>


<h1 style="padding-top:20px;">Contact Information</h1>

Please use the email below if:
<ul>
	<li>you need help</li>
	<li>want to give feedback</li>
	<li>wish to advertize on our site</li>
	<li>want to help with the site</li>
</ul>

The email is:
<table>
<tr><td>info</td><td>at</td><td>uwsube</td><td>dot</td><td>com</td></tr>
</table>

<p style="padding-top:10px;">NOTE: If you don't receive a response within 1-2 days, it means we have read your email, and decided not to act on it.</p>


<h1 style="padding-top:20px;">Banners</h1>
<p>If you or your organization would like to help, please inform your friends and members. There are a few banners below which you can use to link to our website.</p>
<img src="images/banners/uwsube1.gif" style="float:left;margin:0 0 10px 50px;width:204px;height:166px" alt="Visit UW Students Underground Book Exchange Now!" />
<img src="images/banners/uwsube2w.jpg" style="float:right;margin:25px 80px 0 0;width:150px;height:40px" alt="UW Students Underground Book Exchange" />
<img src="images/banners/uwsube2b.jpg" style="float:right;margin:30px 80px 0 0;width:150px;height:40px" alt="UW Students Underground Book Exchange" />
<div class="clear"></div>


</div>

<?php
require('bottom.php');
?>
