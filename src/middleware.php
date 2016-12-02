<?php
// Application middleware

$app->add(new \Slim\Middleware\Session([
    'name' => 'user_session',
    'autorefresh' => true,
    'lifetime' => '1 week'
]));

$app->add(function ($request, $response, $next) {
    $user_name = $this->session->get('current_user');
    if($user_name){
        $this->current_user = $this->user->findUser($user_name);

        //add the current_user to be available in our views
        $view = $this->get('view');
        $view->offsetSet('current_user', $this->current_user);
    }
    return $next($request, $response);
});
