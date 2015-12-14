<?php

require_once __DIR__  . '/../vendor/autoload.php';

session_start();

class GoogleAutoToken {

	/** 		   	          CONSTANTS 			     	       **/
	/****************************************************/
	const AUTH_CONFIG_FILE = "client_secrets.json";

	const REDIRECT_URI = "http://localhost:8080/";

	const SCOPE = 'https://spreadsheets.google.com/feeds';
	/****************************************************/

  /**
   *  Returns an access token needed to connect to the Google SpreadSheets Service
   *  through OAuth 2.0.
   *
   *  It will refresh the token if needed, and always return a valid token.
   *
   *  @return string
   */
	public static function getAccessToken()
  {
		// Get current token.
		$tokenToCheck = self::getCurrentToken();

		// Check if it's valid and refresh if not.
		self::refreshTokenIfExpired($tokenToCheck);

		// Get token again.
		$token = self::getCurrentToken();

		// Return access token.
		return json_decode($token)->access_token;
	}

  /**
   *  Retrieves the last working token.
   *  @return string
   */
	private static function getCurrentToken()
  {
		// Get current token.
		$handle = fopen(__DIR__ . '/' . self::$tokenFile, "r");
		$token = fgets($handle); // assuming there's somthing already there
		fclose($handle);

		return $token;
	}

  /**
   *  Changes the last working token.
   */
	private static function changeCurrentToken($newToken)
  {
		file_put_contents(__DIR__ . '/' . self::$tokenFile, $newToken);
	}

  /**
   *  Refreshes the token only if it has already expired.
   */
	private static function refreshTokenIfExpired($token)
  {
		// Set up the Google Client.
		$client = new Google_Client();
		$client->setAuthConfigFile( __DIR__ . '/' . self::AUTH_CONFIG_FILE );
		$client->setRedirectUri( self::REDIRECT_URI );
		$client->setScopes( array(self::SCOPE) );
		$client->setAccessType("offline");
		$client->setApprovalPrompt("force");
		$client->setAccessToken($token);

		$tokenObj = json_decode($token);

		// Check if token expired.
		if ($client->isAccessTokenExpired()) {
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
	 *  Token files that contain the current tokens.
   *  @var string - path of the last working token
	 */
	private static $tokenFile = "token/token.txt";

}
