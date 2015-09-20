<?php
/**
 * BeeFree
 *
 * BreeFree is a PHP plug-in designed to work with BeeFree.io embeddable html editor. Please visit
 * http://www.beefree.io/ for more information.
 *
 * Example:
 *
 * $beefree = new BeeFree([client_id], [client_secret]);
 * $result = $beefree -> getCredentials();
 *
 *
 * The retrieved credentials should be applied to your Javascript application.
 */

include ('BeeFreeAdapter.php');

class BeeFree implements BeeFreeAdapter {

	//Your API Client ID
	private $_client_id = null;

	//Your API Client Secret
	private $_client_secret = null;

	//Url to call when authenicating
	private $_auth_url = 'https://auth.getbee.io/apiauth';

	/**
	 * The constructor
	 *
	 * @param string $key : The key provided by the api
	 * @param string $secret : The secret provided by the api
	 */
	public function __construct($client_id = null, $client_secret = null) {
		$this -> setClientID($client_id);
		$this -> setClientSecret($client_secret);
	}

	/**
	 * Sets the client id that is provided by the API
	 *
	 * @param string $key
	 */
	public function setClientID($client_id) {
		$this -> _client_id = $client_id;
	}

	/**
	 * Set the client secret provided by the API
	 *
	 * @param string string $secret
	 */
	public function setClientSecret($client_secret) {
		$this -> _client_secret = $client_secret;
	}

	/**
	 * Call the API and get the access token, user and other information  required
	 * to access the api
	 *
	 * @param string $grant_type : The grant type used to authenticate the API
	 * @param string $json_decode: Return the result as an object or array. Default is object, to return set type to 'array'
	 *
	 * @return $mixed credentials
	 */
	public function getCredentials($grant_type = 'password', $json_decode = 'object') {
		//set POST variables
		$fields = array('grant_type' => urlencode($grant_type), 'client_id' => urlencode($this -> _client_id), 'client_secret' => urlencode($this -> _client_secret), );

		//url-ify the data for the POST
		$fields_string = '';

		foreach ($fields as $key => $value) {
			$fields_string .= $key . '=' . $value . '&';
		}

		//open connection
		$ch = curl_init();

		//set the url, number of POST vars, POST data
		curl_setopt($ch, CURLOPT_URL, $this -> _auth_url);
		curl_setopt($ch, CURLOPT_POST, count($fields));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		//execute post
		$result = curl_exec($ch);

		//close connection
		curl_close($ch);

		if ($json_decode == 'array') {
			return json_decode($result, true);
		}

		return json_decode($result);

	}

}
