<?php

namespace App\Http\Controllers;

use App\Http\Requests\Usuario\AuthenticateUsuarioRequest;
use App\Http\Requests\Usuario\SetCompanyUsuarioRequest;
use App\Http\Requests\Usuario\CreateUsuarioRequest;
use App\Models\RelacaoUsuarioEmpresa;
use Illuminate\Support\Collection;
use Illuminate\Http\JsonResponse;
use App\Models\TelefoneUsuario;
use App\Traits\ResponseMessage;
use Illuminate\Http\Request; 
use App\Traits\Authenticate;
use App\Models\Empresa;
use App\Models\Usuario;
use Exception;

class UsuarioController extends Controller
{
    use Authenticate, ResponseMessage;

    private $usuario_empresa;
    private $telefone;
    private $usuario;
    private $empresa;
   

    public function __construct()
    {
        $this->usuario_empresa = new RelacaoUsuarioEmpresa();
        $this->telefone = new TelefoneUsuario();
        $this->usuario = new Usuario();
        $this->empresa = new Empresa();
    }

    
    /**
     * Responsável por criar um usuário comum
     * 
     * @param CreateUsuarioRequest $request
     * @return JsonResponse
     */
    public function storeUser(CreateUsuarioRequest $request): JsonResponse
    {
        $token = $this->decodeToken($request);

        try {

            $id_usuario = $this->usuario->create([
                'nome' => $request->nome,
                'id_tipo_usuario' => 2,
                'email' => $request->email,
                'password' => $this->generatePassword($request->password)
            ])->id_usuario;

            $this->usuario_empresa->create([
                'id_empresa' => $token->com,
                'id_usuario' => $id_usuario
            ]);

        } catch (Exception $e) {

            return $this->formateMenssageError("Não foi possível fazer a inserção de dados", 500);

        }

        return $this->formateMenssageSuccess("Usuário cadastrado com sucesso");
    }

    /**
     * Responsável por criar um usuário admin
     *  
     * @param CreateUsuarioRequest $request
     * @return JsonResponse
     */    
    public function StoreUserAdmin(CreateUsuarioRequest $request): JsonResponse
    {
        try {

            $this->usuario->create([
                'nome' => $request->nome,
                'email' => $request->email,
                'password' => $this->generatePassword($request->password),
                'id_tipo_usuario' => 1
            ]);

        } catch (Exception $e) {

            return $this->formateMenssageError("Não foi possível fazer a inserção de dados", 500);

        }

        return $this->formateMenssageSuccess("Usuário cadastrado com sucesso");

    }

    
    /**
     * Autenticação de usuário admin onde não é necessário 
     * inserir a empresa do usuário
     * 
     * @param AuthenticateUsuarioRequest $request
     * @return JsonResponse
     */
    public function authenticate(AuthenticateUsuarioRequest $request): JsonResponse
    {
        $data = $this->verifyUser($request);

        if(!$data) {

            return $this->formateMenssageError('Senha está incorreta', 401);

        } else if($data['id_tipo_usuario'] != 1) {

            return $this->formateMenssageError('Você não pode acessar essa ação', 403);

        }

        return response()->json($this->configureResponseToken($data['id_usuario'], $request->url()));
    }

    /**
     * Autenticação de usuário comundo que é feito
     * para retorna a lista de empresas que a pessoa 
     * pode logar
     * 
     * @param AuthenticateUsuarioRequest $request
     * @return JsonResponse
     */
    public function authenticateUser(AuthenticateUsuarioRequest $request): JsonResponse
    {
        $data = $this->verifyUser($request);
        
        if(!$data) {

            return $this->formateMenssageError('Senha está incorreta', 401);

        } else if($data['id_tipo_usuario'] != 2) {

            return $this->formateMenssageError('Você não pode acessar essa ação', 403);

        }

        $message = $this->configureResponseToken($data['id_usuario'], $request->url());

        $message['message']['list'] =  $this->usuario_empresa
            ->getCompanies($data['id_usuario'])
            ->select(
                'e.id_empresa',
                'e.nome_fantasia'
            )
            ->get();

        return response()->json($message);
    }

    /**
     * Inserir o claim da empresa durante a 
     * autenticação
     * 
     * @param SetCompanyUsuarioRequest $request
     * @return JsonResponse
     */
    public function setCompany(SetCompanyUsuarioRequest $request): JsonResponse
    {
        $token = $this->decodeToken($request);

        $existsRelacaoEmpresa = $this->usuario_empresa
            ->getRelationship($token->sub, 
                $request->company
            )
            ->exists();

        if(!$existsRelacaoEmpresa) {
            return $this->formateMenssageError(
                "Dado enviado não faz sentido",
                403
            );
        }

        $message = $this->configureResponseToken($token->sub, 
                $request->url(), 
                $request->company
            );

        return response()->json($message);
    }

    /**
     * Faz a listagem de usuário de uma empresa
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function listUser(Request $request): JsonResponse
    {
        $token = $this->decodeToken($request);

        $consult = $this->usuario_empresa->getUsersCompanies($token->com)
            ->select(
                'u.id_usuario',
                'u.nome'
            )
            ->get();

        return $this->formateMenssageSuccess(['usuarios' => $consult]);
    }

    /**
     * Configura a responta json padrão para ser 
     * retornado ao usuário
     * 
     * @param int $id_usuario
     * @param string $aud
     * @param mixed $com
     * @return array
     */
    private function configureResponseToken(int $id_usuario, string $aud, mixed $com = null): array
    {
        $token = $this->generateToken($id_usuario, $aud, $com);

        return [
            'status' => true,
            'message' => [
                'token' => $token,
                'exp' => '5 horas',
            ],
        ];
    }


    /**
     * Verifica se o usuário existe
     * 
     * @param AuthenticateUsuarioRequest $request
     * @return Collection|bool
     */
    private function verifyUser(AuthenticateUsuarioRequest $request)
    {
        $data = $this->usuario->getUserWithEmail($request->email);

        if(!$this->checkUsersExists($request->password, $data)) {
            return false;
        }

        return collect($data->first()->toArray())
            ->forget([
                'password',
                'created_at',
                'updated_at'
            ]);
    }


    /**
     * Checa se o usuário existe ou não
     * 
     * @param string $password
     * @param Usuario $data  
     * @return mixed
     */
    private function checkUsersExists(string $password, object $data): mixed
    {

        if ($data->exists()) {

            return $this->checkPassword($password, $data->first()->password);

        } 

        return false;

    }

}
