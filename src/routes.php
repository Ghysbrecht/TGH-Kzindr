<?php
// Routes

$app->get('/', function ($request, $response, $args) {
    $this->logger->info("Showing '/' home page");
    $checkin = $this->checkin;
    $values = $checkin->getCurrent();
    return $this->view->render($response, 'index.html', ['checkins' => $values]);
})->setName('home');


$app->get('/sign-up', function($request, $response){
    $this->logger->info("Showing '/sign-up' page");
    return $this->view->render($response, 'sign-up.html');
})->setName('signup');


$app->post('/sign-up', function($request, $response){
    $this->logger->info("Showing '/' POST processing sign-up form");

    $user = $this->user;
    $user->create($request->getParsedBody());
    try{
        $user->save();
        //show homepage
        return $response->withRedirect($this->router->pathFor('login'));
    } catch(\Exception $e) {
        //show reg form again
        $this->logger->debug("Error when saving user:");
        $this->logger->debug("--> " . $e->getMessage());
        return $this->view->render($response, 'sign-up.html', $request->getParsedBody());
    }
});

//Login Page Code
$app->get('/log-in', function($request, $response){
    $this->logger->info("Showing '/log-in' page");
    return $this->view->render($response, 'log-in.html');
})->setName('login');

$app->post('/log-in', function($request, $response){
    $this->logger->info("Showing '/' POST processing sign-in form");

    $user = $this->user;
    $data = $request->getParsedBody();

    try{
        $userinfo = $user->find($data['username'], $data['password']);
        $this->session->set('current_user', $user->getUserName());
        return $response->withRedirect($this->router->pathFor('profile'));
    } catch(\Exception $e) {
        //show reg form again
        $this->logger->debug("Error when finding user:");
        $this->logger->debug("--> " . $e->getMessage());
        return $this->view->render($response, 'log-in.html', $request->getParsedBody());
    }
});

$app->get('/logout', function($request, $response, $args) {
$this->session::destroy();
return $response->withRedirect($this->router->pathFor('home'));
})->setName('logout');

$app->get('/profile', function($request, $response){
    $this->logger->info("Showing '/log-in' page");
    return $this->view->render($response, 'profile.html');
})->setName('profile');

$app->get('/edit', function($request, $response){
    $this->logger->info("Showing '/edit' page");
    return $this->view->render($response, 'edit.html');
})->setName('edit');

//Checkins
$app->post('/api/checkin', function($request, $response){
    $this->logger->info("Showing '/' POST processing checkin");

    $checkin = $this->checkin;
    $user = $this->user;

    //Get the user_id using an access-key
    if(isset($request->getHeader('access-key')[0])){
       $user_id = $user->getIdWithKey($request->getHeader('access-key')[0])['id'];
    }
    //If there is no access-key, use the uid-key (Arduino RFID)
    else if(isset($request->getHeader('uid-key')[0])){
        $user_id = $user->getIdWithUIDKey($request->getHeader('uid-key')[0])['id'];
    }
    else throw new \Exception("No idendity credentials!");

    $checkin->create($request->getParsedBody(),$user_id);

    try{
        $checkin->save();
    } catch(\Exception $e) {
        $this->logger->debug("Error when saving checkin:");
        $this->logger->debug("--> " . $e->getMessage());
    }
});
