<?php

namespace App\Http\Controllers;

use App\Http\Requests\Usuario\AuthenticateUsuarioRequest;
use App\Http\Requests\Usuario\CreateUsuarioRequest;
use App\Models\Empresa;
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
    private $empresa;

    private $formatInsertUser = [
        'tipo_usuario' => 'id_tipo_usuario',
    ];

    public function __construct()
    {
        $this->usuario = new Usuario();
        $this->telefone = new TelefoneUsuario();
        $this->usuarioEmpresa = new RelacaoUsuarioEmpresa();
        $this->empresa = new Empresa();
    }

    // Cria usuario

    public function store(CreateUsuarioRequest $request)
    {

        // Recupera e decodifica o token

        $token = $request->bearerToken();
        $decoded = $this->checkToken($token)->sub;
        $tokenTipoUser = $this->usuario->getUserWithId($decoded)->first()->id_tipo_usuario;

        $data = $this->checkSintaxeWithReference($request->all(), $this->formatInsertUser);

        // Caso o tipo do usuario for 2 a empresa do token e inserida no request para cadastro e também e bloqueada a criação de um usuario admin
        
        if ($tokenTipoUser == 1) {

            if (!isset($data['empresa']) && $data['id_tipo_usuario'] == 2) {

                return $this->formateMessageError("Informe a empresa do usuario que deseja cadastrar", 500);

            }

        } else {

            if($data['id_tipo_usuario'] == 1){

                return $this->formateMessageError("Você não tem permissão para cadastrar um usuario admin", 500);

            }

            $tokenEmpre = $this->usuarioEmpresa::where('id_usuario', $decoded)->value('id_empresa');
            $data['empresa'] = $tokenEmpre;

        }

        // Checa se a empresa está ativa para cadastro

        $empreSituation = isset($data['empresa']) ? $this->empresa->checkEmpreIsActive($data['empresa']) : 1;

        if ($empreSituation == 0) {

            return $this->formateMessageError("Não será possivel efetuar o cadastro, empresa inativa", 500);

        }


        // Checa se algum dos telefones já foram cadastrados

        $reqTels = array_unique(explode(', ', $data['telefone_usuario']));
        $cadTels = $this->telefone::get('telefone');

        foreach ($cadTels as $cadTel) {

            foreach ($reqTels as $tel) {

                if ($tel === $cadTel['telefone']) {

                    return $this->formateMessageError("Numero " . $cadTel["telefone"] . " já cadastrado no banco de dados", 500);

                }

            }

        }

        // Insere as informações nas tabelas de usuarios, telefones e relação_usuario_empresa

        $data['password'] = $this->generatePassword($data['password']);

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

        // Verifica se a empresa esta ativa para realizar o login

        if ($data->first()->id_tipo_usuario == 2) {

            $userEmpre = $this->usuarioEmpresa->getUserEmpre($data->first()->id_usuario);
            $empreSituation = $this->empresa->checkEmpreIsActive($userEmpre);

            if ($empreSituation == 0) {

                return $this->formateMessageError("Não será possivel efetuar o login, empresa inativa", 500);

            }

        }

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

        $user = $data->first();
        return $this->checkPassword($password, $user->password);

    }

}
