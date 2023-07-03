<?php

// Contrlodor de la ruta de acceso al micro servicio
class UserController extends BaseController
{
    /** 
* "/user/list" Endpoint - Get list of users 
*/
    // Metodo que valida si el usuario y clave asociada son correctos
	// En caso de exito retorna los datos del usuario.
	// en caso de fallo retorna un mensaje de error.
	public function checkAction()
    {
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        $arrQueryStringParams = $this->getQueryStringParams();
        if (strtoupper($requestMethod) == 'GET') {
            try {
                $userModel = new UserModel();
                $arrUsers = $userModel->getSession($arrQueryStringParams['user'], $arrQueryStringParams['password']);
				if ( count($arrUsers) > 0 ) {
					$responseData = json_encode($arrUsers);
				} else {
					$strErrorDesc = 'Usuario y/o clave invalida.';
					$strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
				}
                
            } catch (Error $e) {
                $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        } else {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }
        // send output 
        if (!$strErrorDesc) {
            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        } else {
            $this->sendOutput(json_encode(array('error' => $strErrorDesc)), 
                array('Content-Type: application/json', $strErrorHeader)
            );
        }
    }
}