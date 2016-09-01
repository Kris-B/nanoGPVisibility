$new_access= filter_var($_POST['new_access'], FILTER_SANITIZE_STRING);
	
	if (!in_array($new_access,array('public','private','protected'))){
		die('permission not supported.');
	}
	
	$userid = filter_var($_POST['userid'], FILTER_SANITIZE_STRING);
	$albumid = filter_var($_POST['albumid'], FILTER_SANITIZE_STRING);
	$access_token = filter_var($_POST['access_token'], FILTER_SANITIZE_STRING);
	
	if (empty($access_token)){
		die();
	}

	$url = 'https://picasaweb.google.com/data/entry/api/user/' . $userid . '/albumid/' . $albumid .'?alt=json';

	$xml="<entry xmlns='http://www.w3.org/2005/Atom' xmlns:gphoto='http://schemas.google.com/photos/2007'>
	  <gphoto:access>".$new_access."</gphoto:access>
	</entry>";

	$url=$url."&".http_build_query(array('access_token'=>$access_token));

	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PATCH");
	curl_setopt($curl, CURLOPT_HEADER, false);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/xml',"GData-Version: 2","If-Match: *"));
	curl_setopt($curl, CURLOPT_POSTFIELDS, $xml);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_VERBOSE, true);

	// Make the REST call, returning the result
	$response = curl_exec($curl);

	$resp=json_decode($response,true);

	echo json_encode(array("response"=>$resp));	
