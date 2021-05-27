<?php


namespace App\Http\Controllers;

use App\Http\Requests\Usuario\AuthenticateUsuarioRequest;
use App\Http\Requests\Usuario\CreateUsuarioRequest;
use App\Traits\ResponsaMessage;
use App\Traits\Authenticate;
use App\Traits\FormatData;
use App\Models\Usuario;
use Exception;

class UsuarioController extends Controller
{
    use Authenticate, FormatData, ResponsaMessage;

    private $usuario;

    private $formatInsertUser = [
        'tipo_usuario' => 'id_tipo_usuario'
    ];

    public function __construct()
    {
        $this->usuario = new Usuario();
    }

    public function store(CreateUsuarioRequest $request)
    {
        $data = $this->checkSintaxeWithReference($request->all(), $this->formatInsertUser);

        try {

            $this->usuario->create($data);

        } catch (Exception $e) {

            return $this->formateMessageError("Não foi possível fazer a inserção de dados", 500);

        }

        return $this->formateMessageSuccess("Usuário cadastrado com sucesso");
    }

    public function authenticate(AuthenticateUsuarioRequest $request)
    {
        $data = $this->usuario->getUserWithEmail($request->email);

        $usersExists = $this->checkUsersExists($request->password, $data);

        if($usersExists) {

            $id = $data->first()->id_usuario;

            $aud = $request->url();

            $token = $this->generateToken($id, $aud);

        } else {

            return $this->formateMessageError('Senha está incorreta', 422);

        }

        return response()->json([
            'status' => true,
            'message' => [
                'token' => $token,
                'exp' => '5 horas',
            ]
        ]);
    }

    private function checkUsersExists($password, $data)
    {
        if($data->exists()) {

            $user = $data->first();

            return $this->checkPassword($password, $user->password);

        } else {

            return false;

        }
    }


}
