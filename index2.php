<?php

require __DIR__ . '/vendor/autoload.php';

$apikey = "913b3dfab6680b539ce15c5e5899a71bef76f96b";
$branch = "master";
$repo = "homecatering";
$filename="index.html";
$targetPath="index.php";
$owner = "fabioaraujopt";

$url = "https://api.github.com/repos/".$owner.'/'.$repo."/contents/".$filename.'?ref='.$branch;

$base64content = base64_encode(file_get_contents($targetPath));

$client = new GuzzleHttp\Client(['verify' => false]);

$header = ['Authorization'=>"token ".$apikey];

$resp = $client->get($url, ['headers' => $header]);

$resp = json_decode($resp->getBody()->getContents(),true);

$sha = $resp['sha'];

if($base64content != $resp['content']){
    $commitObject = json_encode([
        'message'=>"system at ".date('Y-m-d H:i:s'),
        'committer'=>[
            "name"=> "WebEditor",
            "email"=>"fabioaraujoorg@gmail.com"
        ],
        'sha'=>$sha,
        'content'=>$base64content
    ]);
    
    $resp = $client->put(
        $url, 
        [   
            'headers' => $header,
            'body'=>$commitObject
        ]);

    $resp = $resp->getBody()->getContents();

    print_r($resp);
}

exit;


/*
data = requests.get(url+'?ref='+branch, headers = {"Authorization": "token "+token}).json()

$apikey = "913b3dfab6680b539ce15c5e5899a71bef76f96b";

def push_to_github(filename, repo, branch, token):
    url="https://api.github.com/repos/"+repo+"/contents/"+filename

    base64content=base64.b64encode(open(filename,"rb").read())

    data = requests.get(url+'?ref='+branch, headers = {"Authorization": "token "+token}).json()
    sha = data['sha']

    if base64content.decode('utf-8')+"\n" != data['content']:
        message = json.dumps({"message":"update",
                            "branch": branch,
                            "content": base64content.decode("utf-8") ,
                            "sha": sha
                            })

        resp=requests.put(url, data = message, headers = {"Content-Type": "application/json", "Authorization": "token "+token})

        print(resp)
    else:
        print("nothing to update")

token = "lskdlfszezeirzoherkzjehrkzjrzerzer"
filename="foo.txt"
repo = "you/test"
branch="master"

push_to_github(filename, repo, branch, token)
*/