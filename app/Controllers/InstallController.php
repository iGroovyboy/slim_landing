<?php


namespace App\Controllers;


use App\Models\User;
use App\Services\Config;
use App\Services\DB\DB;
use App\Services\Hash;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class InstallController extends BaseController
{
    protected $data;

    public function render($data)
    {
        global $app;
        $routeParser =  $app->getRouteCollector()->getRouteParser();
        $loginUrl = $routeParser->urlFor('login');

        $hasDb = DB::isConnected();
        $hasUser = $hasDb ? User::hasAny() : false;

        $vars = [
            'hasDb' => $hasDb,
            'hasUser' => $hasUser,
            'loginUrl' => $loginUrl,
        ];

        return $this->view->render('install', $vars);
    }

    public function setupDBConnection(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $this->request  = $request;
        $this->response = $response;
        $this->args     = $args;

        $input = $this->request->getParsedBody();

        if (DB::DRIVER_SQLITE === $input['driver']) {
            self::maybeCreateSqliteDB();
        }

        DB::setDriver($input['driver']);
        Config::set('db/driver', $input['driver']);
        Config::save();

        try {
            DB::start($input);
        } catch (\PDOException  $e) {
            return $this->respond('error', $e->getMessage());
        }

        if (DB::isConnected()) {
            Config::set('db', DB::getConfig());
            Config::save();

            DB::migrate(Config::getPath('app/paths/migrations'));
        }

        return $this->respond('success', 'Database connection has been successfully established!');
    }

    public function setupAdminCredentials(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $this->request  = $request;
        $this->response = $response;
        $this->args     = $args;

        if ( ! DB::isConnected()) {
            return $this->respond('error', 'Admin credentials update failed! Database connection config does not exist!');
        }

        $input = $this->request->getParsedBody();
        if (empty($input['admin_email']) || empty($input['admin_password'])) {
            return $this->respond('error', 'Bad credentials!');
        }

        if (User::getByEmail($input['admin_email'])) {
            return $this->respond('error', 'User with specified email already exists!');
        }

        try {
            $user = User::add($input['admin_email'], Hash::make($input['admin_password']), 777);
        } catch (\PDOException $e) {
            return $this->respond('error', $e->getMessage());
        }


        if ( ! $user) {
            return $this->respond('error', 'Something went wrong! Admin credentials were not updated!');
        }

        return $this->respond('success', 'Admin credentials has been successfully updated!');
    }

    protected static function maybeCreateSqliteDB()
    {
        $path = Config::getPath('app/paths/db', Config::get('app/paths/dbfilename'));
        if ( ! file_exists($path)) {
            return file_put_contents($path, '');
        }

        return true;
    }

    protected function respond($status, $message)
    {
        $r = [$status => true, 'message' => $message];
        $this->response->getBody()->write(json_encode($r));

        return $this->response->withHeader('Content-Type', 'application/json');
    }
}
