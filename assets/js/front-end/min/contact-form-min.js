$(document).ready(function(){var a=$("#subscribe-submit"),c=$("#contact-submit");a.click(function(c){c.preventDefault();var e=$("#subscribe-form :input"),s=$("#subscribe-check-spam").val(),t=$("#subscribe-email").val(),i=$("#subscribe-alert-message");a.html('<i class="fa fa-spinner fa-spin"></i>'),e.prop("disabled",!0);var p={form:"subscribeForm",subscribeSpamChecking:s,subscribeEmail:t};$.post("./php/contact.php",p,function(c){"error"==c.type?(i.html('<p><i class="fa fa-times-circle"></i> '+c.text+"</p>"),a.html('<i class="fa fa-paper-plane first"></i><i class="fa fa-paper-plane second"></i>'),e.prop("disabled",!1)):(i.html('<p><i class="fa fa-check-circle-o"></i> '+c.text+"</p>"),a.html('<i class="fa fa-paper-plane first"></i><i class="fa fa-paper-plane second"></i>'),e.prop("disabled",!1),$("#subscribe-email").val(""))},"json")}),c.click(function(a){a.preventDefault();var c=$("#contact-form :input"),e=$("#contact-check-spam").val(),s=$("#contact-name").val(),t=$("#contact-email").val(),i=$("#contact-message").val(),p=$("#contact-alert-message");p.html('<p><i class="fa fa-spinner fa-spin"></i> Sending Message..</p>'),c.prop("disabled",!0);var l={form:"contactForm",contactSpamChecking:e,contactName:s,contactEmail:t,contactMessage:i};$.post("./php/contact.php",l,function(a){"error"==a.type?(p.html('<p><i class="fa fa-times-circle"></i> '+a.text+"</p>"),c.prop("disabled",!1)):(p.html('<p><i class="fa fa-check-circle-o"></i> '+a.text+"</p>"),c.prop("disabled",!1),$("#contact-name").val(""),$("#contact-email").val(""),$("#contact-message").val(""))},"json")})});