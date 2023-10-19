<?php
$calificationsJSON = '[
  {"id": 1, "student": "Robert Smith", "calification": 5},
  {"id": 2, "student": "Jane Atkinson", "calification": 6},
  {"id": 3, "student": "Mary Johnson", "calification": 7}
]';
$playersJSON = file_get_contents("./players.json");
$playersByCountryJSON = file_get_contents("./porPais.json");

// Instantiate the class responsible for implementing a micro application
$app = new \Phalcon\Mvc\Micro();

// TODO Define routes
$app->get('/player', 'getAllPlayers');
$app->get('/player/{id}', 'getPlayer');
$app->get('/country', 'getAllCountries');
$app->get('/country/{id}', 'getCountry');


// TODO Code route handler function
function sendJSONResponse ($status, $result) {
  header('Content-Type: application/json');
  $jsonResponse = array('status' => $status, 'result' => $result);
  echo json_encode($jsonResponse);

}

function getAllPlayers() {
  global $playersJSON;

  $players = json_decode($playersJSON);
  $result = [];
  
  foreach ($players as $player) {
    $playersData = array(
      'player' => $player->player_slug,
    );
    array_push($result, $playersData);
    
  }

  sort($result);
  
  if (count($result)) {
    sendJSONResponse(200, $result);
    return 1;
  }
  else {
    sendJSONResponse(204, array('message' => 'No data'));
    return (0);
  }

}

function getPlayer($id) {
    global $playersJSON;

    $players = json_decode($playersJSON);
    
    foreach ($players as $player) {
      if (!strcmp($player->player_slug, $id)) {
        sendJSONResponse(200, $player);
        return 1;
      }
    }
    sendJSONResponse(204, array('message' => 'No data'));
    return (0);
  
  }

  function getAllCountries() {
    global $playersByCountryJSON;

    $countries = json_decode($playersByCountryJSON);
    $result = [];
    
    foreach ($countries as $country) {
      $countriesData = array(
        'country' => $country->country,
      );
      array_push($result, $countriesData);
      
    }
    
    if (count($result)) {
      sendJSONResponse(200, $result);
      return 1;
    }
    else {
      sendJSONResponse(204, array('message' => 'No data'));
      return (0);
    }

  }

  function getCountry($id) {
    global $playersByCountryJSON;

    $countries = json_decode($playersByCountryJSON);

    
    foreach ($countries as $country) {
      if (!strcmp($country->country, $id)) {
        sendJSONResponse(200, $country);
        return 1;
      }
      
    }
    
    sendJSONResponse(204, array('message' => 'No data'));
    return (0);

  } 

  $app->notFound('notFound');

function notFound() {
  sendJSONResponse(401, array('message' => 'Not found'));
  return (0);
}

// Handle the request
$app->handle();
?>