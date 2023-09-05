<?php

/**
 * @package   Login Code
 * @version   1.0.0
 * @author    John Rayes <live627@gmail.com>
 * @copyright Copyright (c) 2022, John Rayes
 * @license   http://opensource.org/licenses/0BSD Zero-Clause BSD
 */

$txt['logincode_verification'] = 'Login verification needed';
$txt['logincode_input'] = 'Enter verification code';
$txt['logincode_submit'] = 'Submit';
$txt['logincode_error_title'] = 'Oops, there were errors!';
$txt['logincode_invalid_value'] = 'That code you entered is invalid.';
$txt['logincode_ttl'] = 'Login code time to live';
$txt['logincode_ttl_zero'] = '(Set to 0 to disable)';
$txt['logincode_ttl_minutes'] = 'minutes';
$txt['emails']['logincode']['subject'] = 'Confirm new login request';
$txt['emails']['logincode']['html'] = true;
$txt['emails']['logincode']['body'] = '{NAME},

Please confirm your login request

Use the following code to finish logging in on your new device — it will expire in {TTL} minutes:

{CODE}

{BROWSER} on {OS}
{DEVICE}
{TIME}
{LOCATION}
Location is aproximate based on the login\'s IP address.

That wasn\'t me!

If the above login attempt wasn\'t you, please reset your password as soon as possible to safeguard your account.

{REGARDS}';
$txt['emails']['logincode']['html_body'] = '<table style="-webkit-font-smoothing: antialiased; text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; line-height: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-spacing: 0; margin: 0; padding: 0; width: 100%; border-collapse: collapse;" class="background" width="100%" cellspacing="0" cellpadding="0" border="0" align="center"><tbody><tr><td style="-webkit-font-smoothing: antialiased; text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; line-height: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-spacing: 0; margin: 0; padding: 0; border-collapse: collapse;" valign="top" bgcolor="#F0F0F0" align="center">
<table style="-webkit-font-smoothing: antialiased; text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; line-height: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-spacing: 0; padding: 0; width: inherit; max-width: 560px; border-collapse: collapse;" class="wrapper" width="560" cellspacing="0" cellpadding="0" border="0" align="center">

	<tbody>

	<tr>
		<td style="-webkit-font-smoothing: antialiased; text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%; font-size: 13px; font-weight: 400; line-height: 150%; padding-top: 20px; padding-bottom: 20px; color: #999999; font-family: sans-serif; border-collapse: collapse;" class="footer" width="87.5%" valign="top" align="center">This is an automatically generated email. Please do not reply</td>
	</tr>

</tbody></table>
<table style="-webkit-font-smoothing: antialiased; text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; line-height: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-spacing: 0; padding: 0; width: inherit; max-width: 560px; border-collapse: collapse;" class="container" width="560" cellspacing="0" cellpadding="0" border="0" bgcolor="#FFFFFF" align="center">

	<!-- HEADER -->
	<!-- Set text color and font family ("sans-serif" or "Georgia, serif") -->
	<tbody><tr>
		<td style="-webkit-font-smoothing: antialiased; text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%; font-size: 24px; font-weight: bold; line-height: 130%; padding-top: 25px; color: #000000; font-family: sans-serif; border-collapse: collapse;" class="header" width="87.5%" valign="top" align="center">
				Please confirm your login request
		</td>
	</tr>
	
	<!-- SUBHEADER -->
	<!-- Set text color and font family ("sans-serif" or "Georgia, serif") -->
	<tr>
		<td style="-webkit-font-smoothing: antialiased; text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-spacing: 0; margin: 0; padding: 0; padding-bottom: 3px; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%; font-size: 18px; font-weight: 300; line-height: 150%; padding-top: 5px; color: #000000; font-family: sans-serif; border-collapse: collapse;" class="subheader" width="87.5%" valign="top" align="center">
				Use the following code to finish logging in on your new device — it will expire in {TTL} minutes:
		</td>
	</tr>

	<!-- HERO IMAGE -->
	
	

	
	
	<tr>
		<td style="-webkit-font-smoothing: antialiased; text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%; font-size: 17px; font-weight: 400; line-height: 160%; padding-top: 25px; color: #000000; font-family: sans-serif; border-collapse: collapse;" class="paragraph" width="87.5%" valign="top" align="center">{CODE}</td>
	</tr>

	<!-- LINE -->
	<!-- Set line color -->
	<tr>	
		<td style="-webkit-font-smoothing: antialiased; text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; line-height: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%; padding-top: 25px; border-collapse: collapse;" class="line" width="87.5%" valign="top" align="center"><hr style="margin: 0; padding: 0;" width="100%" size="1" noshade="" color="#E0E0E0" align="center">
		</td>
	</tr>

	<!-- LIST -->
	<tr>
		<td style="-webkit-font-smoothing: antialiased; text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%; font-size: 17px; font-weight: 400; line-height: 160%; padding-top: 25px; color: #000000; font-family: sans-serif; border-collapse: collapse;" class="paragraph" width="87.5%" valign="top">
{BROWSER} on {OS}<br />
{DEVICE}<br />
{TIME}<br />
{LOCATION}<br />
Location is aproximate based on the login\'s IP address.
				</td>

			</tr>

			<!-- LIST ITEM -->
			<tr>

		<td style="-webkit-font-smoothing: antialiased; text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%; font-size: 17px; font-weight: 400; line-height: 160%; padding-top: 25px; color: #000000; font-family: sans-serif; border-collapse: collapse;" class="paragraph" width="87.5%" valign="top">
						<b style="color: #333333;">That wasn\'t me!</b><br>
						If the above login attempt wasn\'t you, please reset your password as soon as possible to safeguard your account.
				</td>
	</tr>

	<!-- LINE -->
	<!-- Set line color -->
	<tr>
		<td style="-webkit-font-smoothing: antialiased; text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; line-height: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%; padding-top: 25px; border-collapse: collapse;" class="line" width="87.5%" valign="top" align="center"><hr style="margin: 0; padding: 0;" width="100%" size="1" noshade="" color="#E0E0E0" align="center">
		</td>
	</tr>

	<!-- PARAGRAPH -->
	<!-- Set text color and font family ("sans-serif" or "Georgia, serif"). Duplicate all text styles in links, including line-height -->
	<tr>
		<td style="-webkit-font-smoothing: antialiased; text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%; font-size: 17px; font-weight: 400; line-height: 160%; padding-top: 20px; padding-bottom: 25px; color: #000000; font-family: sans-serif; border-collapse: collapse;" class="paragraph" width="87.5%" valign="top" align="center">
				Have a&nbsp;question? <a href="mailto:{WEBMASTER}" target="_blank" style="-webkit-font-smoothing: antialiased; text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; color: #127DB3; font-family: sans-serif; font-size: 17px; font-weight: 400; line-height: 160%;">{WEBMASTER}</a>
		</td>
	</tr>

<!-- End of WRAPPER -->
</tbody></table>

<!-- WRAPPER -->
<!-- Set wrapper width (twice) -->
<table style="-webkit-font-smoothing: antialiased; text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; line-height: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-spacing: 0; padding: 0; width: inherit; max-width: 560px; border-collapse: collapse;" class="wrapper" width="560" cellspacing="0" cellpadding="0" border="0" align="center">

	<tbody>
	<tr>
		<td style="-webkit-font-smoothing: antialiased; text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%; font-size: 13px; font-weight: 400; line-height: 150%; padding-top: 20px; padding-bottom: 20px; color: #999999; font-family: sans-serif; border-collapse: collapse;" class="footer" width="87.5%" valign="top" align="center"> You are receiving this email because you or something using this email address has tried to log in on our site. You&nbsp;could change your <a href="{PROFILELINK}/" target="_blank" style="-webkit-font-smoothing: antialiased; text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; text-decoration: underline; color: #999999; font-family: sans-serif; font-size: 13px; font-weight: 400; line-height: 150%;">notification preferences</a> anytime.

		</td>
	</tr>

<!-- End of WRAPPER -->
</tbody></table>

<!-- End of SECTION / BACKGROUND -->
</td></tr></tbody></table>

';
