<?php
// Routes

$app->get('/', function ($request, $response, $args) {
    $this->logger->info("Showing '/' home page");
    return $this->view->render($response, 'index.html');
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
