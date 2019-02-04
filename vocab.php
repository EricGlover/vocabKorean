<?php

  // simple vocab selector for learning korean
  // $words = [
  //   "child" => "ㅏㅣ",
  //   "child" => 'ㅐ',
  //   "ear" => "차",
  //   "hippo" => "함ㅏ",
  //   "clock" => "하마",
  //   "nose" => "",
  //   "what" => "",
  //   "milk" => "",
  //   "yes" => "",
  //   "ocean" => "",
  //   "lion" => "",
  //   "store",
  //   "nine",
  //   "snack",
  //   "ear"
  // ];

  // read from json file
  $jsonFile = __DIR__ . "/words.json";

  //check for file
  if ( ! file_exists( $jsonFile ) ) {
    echo "There is no 'words.json' in this directory";
    exit();
  }

  // get json
  $jsonStr = file_get_contents( $jsonFile );
  if(!$jsonStr) {
    echo "error reading the json file";
    die();
  }

  // encoding bullshit 
  $encoding = mb_detect_encoding($jsonStr);
  echo "encoding = $encoding" . PHP_EOL;

  if(!mb_check_encoding($jsonStr, 'UTF-8')) {
    $jsonStr = mb_convert_encoding($jsonStr, 'UTF-8', $encoding);
  }

  // decode json
  $words = json_decode($jsonStr, true);
  if(json_last_error() !== JSON_ERROR_NONE) {
    echo "error decoding the json string";
    die();
  }

  $dictionary = [];
  foreach ($words as $english => $korean) {
    $dictionary[] = [$english, $korean];
  }

  $stdin = fopen("php://stdin", "r");

  echo "Beginning vocab list program" . PHP_EOL;
  $len = count($dictionary);
  echo "Vocab list length = $len" . PHP_EOL;

  while ( count($dictionary) > 0 ) {
    // randomly select an index
    $idx = rand(0, count($dictionary) - 1);
    echo $dictionary[$idx][0] . PHP_EOL;

    // wait for the ready response
    $response = strtolower(trim(fgets($stdin)));
    $korean = $dictionary[$idx][1];
    if($response !== $korean) {
      echo "INCORRECT. Correct answer -   $korean" . PHP_EOL;
    } else {
      echo "CORRECT" . PHP_EOL;
    }

    // remove word from list
    array_splice($dictionary, $idx, 1);
  };
