<?php

/**
* Dump and Die
*
* @param 	 Any 	 $var
* @return 	 void
*/
function dd($var) {
  echo '<pre>';
  var_dump($var);
  echo '</pre>';
  die();
}

/**
* html special characters shortcut
*
* @param 	 String 	 $str
* @return 	 String
*/
function h(String $str) {
  return htmlspecialchars($str);
}

/**
* Delete a directory and all files in it
*
* @param 	 String 	 $dir
* @return 	 Boolean
*/
function delTree($dir) { 
  if(empty($dir)) {
    return false;
  }

  $files = array_diff(scandir($dir), array('.', '..')); 

  foreach ($files as $file) { 
    (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file"); 
  }

  return rmdir($dir); 
} 

/**
* Curl http post request
*
* @param 	 String 	 $url
* @param 	 Array 	 $data
* @return 	 String
*/
function httpPost(String $url, Array $data) {
  $curl = curl_init($url);
  curl_setopt($curl, CURLOPT_POST, true);
  curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  $response = curl_exec($curl);
  curl_close($curl);
  return $response;
}

/**
* Parse put form input
*
* @param 	 void 	 
* @return 	 Array
*/
function parsePut() {
  // Fetch content and determine boundary
  $raw_data = file_get_contents('php://input');
  $boundary = substr($raw_data, 0, strpos($raw_data, "\r\n"));

  // Fetch each part
  $parts = array_slice(explode($boundary, $raw_data), 1);
  $data = array();

  foreach ($parts as $part) {
      // If this is the last part, break
      if ($part == "--\r\n") break; 

      // Separate content from headers
      $part = ltrim($part, "\r\n");
      list($raw_headers, $body) = explode("\r\n\r\n", $part, 2);

      // Parse the headers list
      $raw_headers = explode("\r\n", $raw_headers);
      $headers = array();
      foreach ($raw_headers as $header) {
          list($name, $value) = explode(':', $header);
          $headers[strtolower($name)] = ltrim($value, ' '); 
      } 

      // Parse the Content-Disposition to get the field name, etc.
      if (isset($headers['content-disposition'])) {
          $filename = null;
          preg_match(
              '/^(.+); *name="([^"]+)"(; *filename="([^"]+)")?/', 
              $headers['content-disposition'], 
              $matches
          );
          list(, $type, $name) = $matches;
          isset($matches[4]) and $filename = $matches[4]; 

          // handle your fields here
          switch ($name) {
              // this is a file upload
              case 'image':
                  $data["file"] = Array('file_name' => $filename, 'body' => $body);
                  break;

              // default for all other files is to populate $data
              default: 
                  $data[$name] = substr($body, 0, strlen($body) - 2);
                  break;
          } 
      }
  }
  return $data;
}