<?php
require('include/global_data.php');
$html_title = 'Contact Information';
require('top.php');
?>

<div id="content" style="background-image:url(images/bg_contact.jpg);">
<h1>Contact Information</h1>

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


<h1 style="padding-top:20px;">Frequently Asked Questions</h1>

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
</dl>


</div>

<?php
require('bottom.php');
?>
