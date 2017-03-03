<?php

/**
 * Get BitBucket OAuth token.
 *
 * @param array $bitbucketSecrets
 * @param $bitbucketSecrets['client_id']
 * @param $bitbucketSecrets['client_secret']
 * @param $bitbucketSecrets['refresh_token']
 */
function bitbucket_get_token($secrets) {
  $requiredKeys = array('client_id', 'client_secret', 'refresh_token');
  $missing = array_diff($requiredKeys, array_keys($secrets));
  if (!empty($missing)) {
    die('Missing required keys in json secrets file: ' . implode(',', $missing) . '. Aborting!');
  }

  $url = "https://bitbucket.org/site/oauth2/access_token";
  $post = array(
    'grant_type' => 'refresh_token',
    'client_id' => $secrets['client_id'],
    'client_secret' => $secrets['client_secret'],
    'refresh_token' => $secrets['refresh_token'],
  );

  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

  $resp = curl_exec($ch);
  curl_close($ch);

  return json_decode($resp)->access_token;
}
