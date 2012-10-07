<?php

require 'include/global_data.php';
require dirname(__FILE__) . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'libs' . DIRECTORY_SEPARATOR . 'soap-wsse.php';

if (!_AMAZON_ENABLED) {
	header("Status: 403 Forbidden");
	die();
}

// Code from: http://www.cdatazone.org/index.php?/pages/source.html
class mySoap extends SoapClient {

   function __doRequest($request, $location, $saction, $version) {
    $doc = new DOMDocument('1.0');
    $doc->loadXML($request);

    $objWSSE = new WSSESoap($doc);

    /* add Timestamp with no expiration timestamp */
     $objWSSE->addTimestamp();

    /* create new XMLSec Key using RSA SHA-1 and type is private key */
    $objKey = new XMLSecurityKey(XMLSecurityKey::RSA_SHA1, array('type'=>'private'));

    /* load the private key from file - last arg is bool if key in file (TRUE) or is string (FALSE) */
    $objKey->loadKey(_AMAZON_PRIVATE_KEY, TRUE);

    /* Sign the message - also signs appropraite WS-Security items */
    $objWSSE->signSoapDoc($objKey);

    /* Add certificate (BinarySecurityToken) to the message and attach pointer to Signature */
    $token = $objWSSE->addBinaryToken(file_get_contents(_AMAZON_CERT_FILE));
    $objWSSE->attachTokentoSig($token);
    return parent::__doRequest($objWSSE->saveXML(), $location, $saction, $version);
   }
}

$isbn = preg_replace('/[^a-zA-Z0-9]/', '', $_GET['isbn']);
$cached_file = 'cache' . DIRECTORY_SEPARATOR . 'book_covers' . DIRECTORY_SEPARATOR . $isbn . '.jpg';
$no_book_cover_file = 'images' . DIRECTORY_SEPARATOR . 'book_cover_not_found.jpg';

if (!file_exists($cached_file))
{
	$wsdl_url = 'http://webservices.amazon.com/AWSECommerceService/AWSECommerceService.wsdl';
	$client = new mySoap($wsdl_url);
	
	$request = array(
			'ItemId' => $isbn,
			'ResponseGroup' => 'Images',
	);
	
	if (strlen($isbn) == 13)
	{
		$request['SearchIndex'] = 'Books';
		$request['IdType'] = 'EAN';
	}
	
	$params = array(
		'AWSAccessKeyId' => _AMAZON_AWS_ACCESS_KEY_ID,
		'Operation' => 'ItemLookup',
		'Request' => $request,
		'AssociateTag' => _AMAZON_ASSOCIATE_TAG,
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

die();
