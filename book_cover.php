<?php

require 'include/global_data.php';

$isbn = preg_replace('/[^a-zA-Z0-9]/', '', $_GET['isbn']);
$cached_file = 'cache' . DIRECTORY_SEPARATOR . 'book_covers' . DIRECTORY_SEPARATOR . $isbn . '.jpg';
$no_book_cover_file = 'images' . DIRECTORY_SEPARATOR . 'book_cover_not_found.jpg';

if (!file_exists($cached_file))
{
	$wsdl_url = 'http://webservices.amazon.com/AWSECommerceService/AWSECommerceService.wsdl';
	$client = new SoapClient($wsdl_url);
	
	$params = array(
		'AWSAccessKeyId' => 'AKIAIM2T4FM6QWJDHDZQ',
		'Operation' => 'ItemLookup',
		'Request' => array(
			'SearchIndex' => 'Books',
			'ItemId' => $isbn,
			'IdType' => 'EAN',
			'ResponseGroup' => 'Images',
		),
	);
	
	$books = $client->ItemLookup($params);
	
	if (isset($books->Items->Item->SmallImage->URL))
	{
		$image = file_get_contents($books->Items->Item->SmallImage->URL);
		file_put_contents($cached_file, $image);
		$last_modified = filemtime($cached_file);
	}
	else
	{
		$image = file_get_contents($no_book_cover_file);
		$last_modified = filemtime($no_book_cover_file);
	}
}
else
{
	$image = file_get_contents($cached_file);
	$last_modified = filemtime($cached_file);
}

header('Last-Modified: '.date('r', $last_modified));
header('Accept-Ranges: bytes');
header('Content-Length: '.strlen($image));
header('Content-Type: image/jpeg');

echo $image;
