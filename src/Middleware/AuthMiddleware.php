<?php
declare(strict_types=1);

namespace App\Middleware;

use DateTime;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Psr7\Factory\ResponseFactory;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use DB;
use Slim\Views\Twig;

class AuthMiddleware implements Middleware
{
    /**
     * {@inheritdoc}
     */
    public function process(Request $request, RequestHandler $handler) : Response
    {

        session_start();

        $user = $this->getUserInCurrentSession(session_id());

        if(!empty($user)){
            DB::update('userSessions',[
                'updated_at' => date('Y-m-d H:i:s',time())
            ],'sessionId=%s',session_id());

            global $twig;
            $twig->getEnvironment()->addGlobal('loginUser', $user);
        }

        return $handler->handle($request);
    }


    public static function mustBeLogin()
    {
        return function (Request $request, RequestHandler $handler) {
            $user = $request->getAttribute('user');
            $response = $handler->handle($request);

            if(empty($user)){
                return $response->withHeader('Location','/error/forbidden');
            }

            return $response;
        };
    }

    public static function mustNotLogin()
    {
        return function (Request $request, RequestHandler $handler){
            
            $user = $request->getAttribute('user');

            if(empty($user)){
                $response = $handler->handle($request);
                return $response;
            }

            $response = (new ResponseFactory())->createResponse(302);
            return $response->withHeader('Location','/');
        };
    }

    private function getUserInCurrentSession($sessionId){
        // 60 mins max
        $expiredTime = date('Y-m-d H:i:s',time() - 60 * 60);

        $user = DB::queryFirstRow(
            "SELECT u.id AS 'id', u.firstname AS 'name'
            FROM users AS u
            JOIN userSessions AS s
            ON s.userId = u.id
            WHERE s.updated_at > %s
            AND s.sessionId = %s", $expiredTime , $sessionId);
        return $user;
    }
}