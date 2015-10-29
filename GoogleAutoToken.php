<?php

require 'vendor/autoload.php';

session_start();

class GoogleAutoToken {

	/** 			    CONSTANTS 				   */
	/***********************************************/

	const AUTH_CONFIG_FILE = "client_secrets.json";

	const REDIRECT_URI = "http://localhost/sms";

	const SCOPES = array(
		'https://spreadsheets.google.com/feeds'
	);

	/**********************************************/

	public static function getAccessToken() {
		// Get current token.
		$tokenToCheck = self::getCurrentToken();

		// Check if it's valid and refresh if not.
		self::refreshTokenIfExpired($tokenToCheck);

		// Get token again.
		$token = self::getCurrentToken();

		// Return access token.
		return json_decode($token)->access_token;
	}

	private static function getCurrentToken() {
		// Get current token.
		$handle = fopen(self::$tokenFile, "r");
		$token = fgets($handle); // assuming there's somthing already there
		fclose($handle);

		return $token;
	}

	private static function changeCurrentToken($newToken) {
		file_put_contents(self::$tokenFile, $newToken);
	}

	private static function refreshTokenIfExpired($token) {
		// Set up the Google Client.
		$client = new Google_Client();
		$client->setAuthConfigFile( self::AUTH_CONFIG_FILE );
		$client->setRedirectUri( self::REDIRECT_URI );
		$client->setScopes( self::SCOPES );
		$client->setAccessType("offline");
		$client->setApprovalPrompt("force");
		$client->setAccessToken( $token );

		$tokenObj = json_decode($token);

		// Check if token expired.
		if ( $client->isAccessTokenExpired() ) {
			// Refresh the token.
			$refreshTkn = $tokenObj->refresh_token;
			$client->refreshToken($refreshTkn);

			// Retrieve the new token.
			$newToken = $client->getAccessToken();
			
			// Change old token to new one.
			self::changeCurrentToken($newToken);			
		} else {
			// Do nothing.
		}
	}

	/**
	*	Token files that contain the current tokens.
	*/

	private static $tokenFile = "token/token.txt";
}

?>