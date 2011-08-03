<?php

require_once 'Cache/Lite.php';
$options = array(
    'cacheDir' => sys_get_temp_dir().'/',
    'lifeTime' => 3600
);
$Cache_Lite = new Cache_Lite($options);


function getcached( $url )
{
  global $Cache_Lite;
  $id = base64_encode($url);
  if ( ! ($data = $Cache_Lite->get($id)) ) {
    //shell_exec("echo 'cacheing {$url}' >> /tmp/pad.log");
    $data = file_get_contents($url);
    $extra_head =  <<<EOFSTR
      <head>
        <title>PADDAN</title>
        <link rel="stylesheet" href="http://hacknight.se/hacknight.css" content-type="text/css">
        <link href="http://fonts.googleapis.com/css?family=Cabin+Sketch:bold" rel="stylesheet" type="text/css"> 
        <link href="http://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css"> 
        <link href="http://fonts.googleapis.com/css?family=Waiting+for+the+Sunrise" rel="stylesheet" type="text/css">
EOFSTR;
    if ( ! empty($data) ) {
      $data = str_replace('<head>', $extra_head, $data);
      $data = str_replace('<body>','<body><div id="wrapper"><div class="column" style="width:100%"><div class="inner">',$data);
      $data = str_replace('</body>','</div></div></div></body>',$data);
    } else {
      $data = 'unable to fetch url';
    }
    $r = $Cache_Lite->save($data, $id);
  }
  return $data;
}

function on_get( $params = array() )
{
  //@todo fix this as a config variable or something!
  $url = 'http://a.pad.url';
}

  $content = getcached($url);

  return $content;
}

echo on_get($_GET);
