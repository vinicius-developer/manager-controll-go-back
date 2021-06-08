<?php

namespace App\Http\Controllers;

use App\Http\Requests\Usuario\AuthenticateUsuarioRequest;
use App\Http\Requests\Usuario\CreateUsuarioRequest;
use App\Models\RelacaoUsuarioEmpresa;
use App\Models\TelefoneUsuario;
use App\Models\Usuario;
use App\Traits\Authenticate;
use App\Traits\FormatData;
use App\Traits\ResponsaMessage;
use Exception;

class UsuarioController extends Controller
{
    use Authenticate, FormatData, ResponsaMessage;

    private $usuario;
    private $telefone;
    private $usuarioEmpresa;

    private $formatInsertUser = [
        'tipo_usuario' => 'id_tipo_usuario',
    ];

    public function __construct()
    {
        $this->usuario = new Usuario();
        $this->telefone = new TelefoneUsuario();
        $this->usuarioEmpresa = new RelacaoUsuarioEmpresa();
    }

    // Cria usuario

    public function store(CreateUsuarioRequest $request)
    {

        // Recupera e decodifica o token

        $token = $request->bearerToken();
        $decoded = $this->checkToken($token)->sub;
        $tokenTipoUser = $this->usuario::where('id_usuario', $decoded)->value('id_tipo_usuario');

        $data = $this->checkSintaxeWithReference($request->all(), $this->formatInsertUser);

        
        // Caso o tipo do usuario for 2 é a empresa do token e inserida no request para cadastro
        
        if ($tokenTipoUser == 2) {
            
            $tokenEmpre = $this->usuarioEmpresa::where('id_usuario', $decoded)->value('id_empresa');
            $data['empresa'] = $tokenEmpre;
            
        }

        $data['password'] = $this->generatePassword($data['password']);
        $reqTels = array_unique(explode(', ', $data['telefone_usuario']));

        // Checa se algum dos telefones já foram cadastrados

        $cadTels = $this->telefone::get('telefone');

        foreach ($cadTels as $cadTel) {

            foreach ($reqTels as $tel) {

                if ($tel === $cadTel['telefone']) {

                    return $this->formateMessageError("Numero " . $cadTel["telefone"] . " já cadastrado no banco de dados", 500);

                }

            }

        }

        // Insere as informações nas tabelas de usuarios, telefones e relação_usuario_empresa

        try {

            $this->usuario->create($data);

            $idUser = $this->usuario::where('email', $data['email'])->value('id_usuario');

            $userEmpreData = array(
                'id_empresa' => $data['empresa'],
                'id_usuario' => $idUser,
            );

            $this->usuarioEmpresa->create($userEmpreData);

            foreach ($reqTels as $value) {

                $telData = array(
                    'id_usuario' => $idUser,
                    'telefone' => $value,
                );

                $this->telefone->create($telData);
            }

        } catch (Exception $e) {

            return $this->formateMessageError("Não foi possível fazer a inserção de dados", 500);

        }

        return $this->formateMessageSuccess("Usuário cadastrado com sucesso");

    }

    // Sistema de Login

    public function authenticate(AuthenticateUsuarioRequest $request)
    {
        $data = $this->usuario->getUserWithEmail($request->email);

        $usersExists = $this->checkUsersExists($request->password, $data);

        if ($usersExists) {

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
            ],
        ]);
    }

    private function checkUsersExists($password, $data)
    {
        if ($data->exists()) {

            $user = $data->first();

            return $this->checkPassword($password, $user->password);

        } else {

            return false;

        }
    }

}
