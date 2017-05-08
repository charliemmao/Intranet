#!/usr/bin/perl
use Net::SMTP;
$from = shift;
$to = shift;
$cc = shift;
$subject = shift;
$msg = shift;

$mailserver='mail.rla.com.au';
$smtp=Net::SMTP->new($mailserver); 		# connect to an SMTP server
$smtp->mail($from);     				# use the sender's address here
$smtp->to($to);        					# recipient's address
$smtp->data();                      				# Start the mail
        
# Send the header.
$smtp->datasend("To: $to\n");
$smtp->datasend("From: $from\n");
$smtp->datasend("Subject: $subject\n");
$smtp->datasend("CC: $cc\n");
$smtp->datasend("\n");

# Send the body.
$smtp->datasend("$msg");
$smtp->dataend();                   	# Finish sending the mail
$smtp->quit;                        		# Close the SMTP connection
