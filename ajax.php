<?php
// echo json_encode($response); // Convert response to JSON

$action = $_REQUEST['action'];

if(!empty($action)){
    require_once("partials/user.php");
    $obj = new user();
}



//adding user action
if($action == 'adduser' && !empty($_POST)){
    $pname = $_POST['username'];
    $email = $_POST['email'];
    $number = $_POST['number'];
    $photo = $_FILES['photo'];
    $playerid = (!empty($_POST['userId']) ? $_POST['userId'] : "");

    // Check if file is uploaded
    $imagename='';
    if(!empty($photo['name'])){
        $imagename = $obj->upload_photo($photo);
        $playerData = [
            'name' => $pname,
            'email' => $email,
            'number' => $number,
            'photo' => $imagename,
        ];
    }else{
        $playerData=[
            'name'=>$pname,
            'email'=>$email,
            'number'=>$number,
        ];
    }

    if($playerid){
        $obj->update($playerData,$playerid);
    }else{
        $playerid=$obj->addUser($playerData);
    }


    if(!empty($playerid)){
        $player=$obj->getRow('id',$playerid);
        echo json_encode($player);
        exit();
    }
}


//get count off function and get all users action
if($action == 'getallusers'){
    $page = (!empty($_GET['page'])) ? $_GET['page'] : 1;
    $limit = 4;

    $start=($page-1)*$limit;

    $users = $obj->getRows($start,$limit);

    if(!empty($users)){
        $userlist = $users;
    }else{
        $userlist=[];
    }

    $total = $obj->rowCount();

    $userArr = ['count' => $total,'users'=>$users];
    echo json_encode(($userArr));
    exit();
}



//action to perform editing
if($action == 'editusersdata'){
    $playerid = (!empty($_GET['id']) ? $_GET['id'] : "");
    if(!empty($playerid)){
        $user=$obj->getRow('id',$playerid);
        echo json_encode($user);
        exit();
    }
}



//perform deleting
if($action == 'deleteusersdata'){
    $userId = (!empty($_GET['id']) ? $_GET['id'] : "");
    if(!empty($userId)){
        $isdeleted=$obj->deleteRow($userId);
        if($isdeleted){
            $displayMsg = ['delete' => 1];
        }else{
            $displayMsg = ['delete' => 0];
        }
        echo json_encode($displayMsg);
        exit();
    }
}


//perform searching
if($action =="searchUser"){
    $queryString =(!empty($_GET['searchQuery']))?trim($_GET['searchQuery']):'';
    $results=$obj->searchUser($queryString);
    echo json_encode($results);
    exit();

}
?>