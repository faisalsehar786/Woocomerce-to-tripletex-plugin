<?php 

/////////////////////////////////  Update Control of plugin fro git hub //////////////////////////////


$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://github.com/faisalsehar786/Woocomerce-to-tripletex-plugin/',
	__FILE__,
	'trptx_to_woocomerce_andpercent'
);


//Set the branch that contains the stable release.
$myUpdateChecker->setBranch('main');
//Optional: If you're using a private repository, specify the access token like this:
$myUpdateChecker->setAuthentication('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');
print_r($myUpdateChecker);
die();