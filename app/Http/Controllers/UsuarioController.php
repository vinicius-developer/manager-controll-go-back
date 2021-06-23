<?php

namespace App\Http\Controllers;

use App\Http\Requests\Usuario\AuthenticateUsuarioRequest;
use App\Http\Requests\Usuario\CreateUsuarioAdminRequest;
use App\Http\Requests\Usuario\CreateUsuarioRequest;
use App\Http\Requests\Usuario\ListUsuariosRequest;
use App\Models\Empresa;
use App\Models\RelacaoUsuarioEmpresa;
use App\Models\TelefoneUsuario;
use App\Models\Usuario;
use App\Traits\Authenticate;
use App\Traits\FormatData;
use App\Traits\ResponsaMessage;
use Exception;
use JetBrains\PhpStorm\NoReturn;

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

    public function storeUser(CreateUsuarioRequest $request)
    {

        $userToken = $this->decodeToken($request);

        $data = $this->checkSintaxeWithReference($request->all(), $this->formatInsertUser);

        if ($userToken->id_tipo_usuario == 1) {

            if (!isset($data['empresa'])) {

                return $this->formateMessageError("Informe a empresa do usuario que deseja cadastrar", 500);

            }

        } else {

            $data['empresa'] = $this->usuarioEmpresa->getUserEmpre($userToken->id_usuario);

        }

        $empreSituation = $this->empresa->checkEmpreIsActive($data['empresa']);

        if ($empreSituation == 0) {

            return $this->formateMessageError("Não será possivel efetuar o cadastro, empresa inativa", 500);

        }

        $telRep = $this->checkTelReq($data['telefone_usuario']);

        if ($telRep == 1) {

            return $this->formateMessageError("Telefone já cadastrado no banco de dados", 500);

        }


        $data['password'] = $this->generatePassword($data['password']);
        $data['id_tipo_usuario'] = 2;


        try {

            $this->usuario->create($data);

            $idUser = $this->usuario->getUserWithEmail($data['email'])->id_usuario;
            $reqTels = array_unique($data['telefone_usuario']);

            $userEmpreData = [
                'id_empresa' => $data['empresa'],
                'id_usuario' => $idUser,
            ];

            $this->usuarioEmpresa->create($userEmpreData);

            foreach ($reqTels as $value) {

                $telData = [
                    'id_usuario' => $idUser,
                    'telefone' => $value,
                ];

                $this->telefone->create($telData);
            }

        } catch (Exception $e) {

            return $this->formateMessageError("Não foi possível fazer a inserção de dados", 500);

        }

        return $this->formateMessageSuccess("Usuário cadastrado com sucesso");

    }

    // Cadastra usuarios admins

    public function StoreUserAdmin(CreateUsuarioAdminRequest $request)
    {

        $userToken = $this->decodeToken($request);

        $data = $this->checkSintaxeWithReference($request->all(), $this->formatInsertUser);

        if ($userToken->id_tipo_usuario != 1) {

            return $this->formateMessageError("O usuario não tem autorização para cadastrar administradores", 500);

        }

        $telRep = $this->checkTelReq($data['telefone_usuario']);

        if ($telRep == 1) {

            return $this->formateMessageError("Telefone já cadastrado no banco de dados", 500);

        }

        $data['password'] = $this->generatePassword($data['password']);
        $data['id_tipo_usuario'] = 1;

        try {

            $this->usuario->create($data);

            $idUser = $this->usuario->getUserWithEmail($data['email'])->id_usuario;
            $reqTels = array_unique($data['telefone_usuario']);

            foreach ($reqTels as $value) {

                $telData = [
                    'id_usuario' => $idUser,
                    'telefone' => $value,
                ];

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

        if ($data->id_tipo_usuario == 2) {

            $userEmpre = $this->usuarioEmpresa->getUserEmpre($data->id_usuario);

            $empreSituation = $this->empresa->checkEmpreIsActive($userEmpre);

            if ($empreSituation == 0) {

                return $this->formateMessageError("Não será possivel efetuar o login, empresa inativa", 500);

            }

        }

        if ($usersExists) {

            $id = $data->id_usuario;

            $aud = $request->url();

            $token = $this->generateToken($id, $aud);

        } else {

            return $this->formateMessageError('Senha está incorreta', 401);

        }

        return response()->json([
            'status' => true,
            'message' => [
                'token' => $token,
                'exp' => '5 horas',
            ],
        ]);
    }

    public function listUser(ListUsuariosRequest $request)
    {

        $empreSituation = isset($request['empresa']) ? $this->empresa->checkEmpreIsActive($request['empresa']) : 1;

        if ($empreSituation == 0) {

            return $this->formateMessageError("Não será possivel efetuar a consulta, empresa inativa", 500);

        }

        $consult = $this->usuarioEmpresa->consultUser($request['empresa']);

        foreach ($consult as $value) {

            $consultUser[] = $this->usuario->getUserWithId($value['id_usuario']);

        }

        return $consultUser;

    }

    private function checkTelReq($data)
    {

        $reqTels = array_unique($data);
        $cadTels = $this->telefone->getAllTel();

        foreach ($cadTels as $cadTel) {

            foreach ($reqTels as $tel) {

                if ($tel === $cadTel['telefone']) {

                    return true;

                }

            }

        }

    }

    private function checkUsersExists($password, $data)
    {

        if ($data->exists()) {

            return $this->checkPassword($password, $data->password);

        } else {

            return false;

        }

    }

}
