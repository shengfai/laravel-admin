<?php
namespace Shengfai\LaravelAdmin\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Shengfai\LaravelAdmin\Contracts\Conventions;
use Shengfai\LaravelAdmin\Contracts\ErrorCodes;

/**
 * 用户授权
 * Class AuthorizationController
 *
 * @package \App\Http\Controllers\Api
 * @author ShengFai <shengfai@qq.com>
 */
class AuthorizationController extends Controller
{
    /**
     * 帐号授权
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function authorizeWithAccount(Request $request)
    {
        $credentials = array_merge($request->only('phone', 'password'), [
            'type' => User::TYPE_ADMINISTRATOR,
            'status' => Conventions::STATUS_USABLE
        ]);
        
        if (! $token = Auth::guard('api')->attempt($credentials)) {
            return $this->errorUnprocessable(null, ErrorCodes::BUSINESS_PARAM_ERROR, trans('error.invalid_password'));
        }
        
        return $this->respondWithToken($token)->setStatusCode(ErrorCodes::HTTP_CREATED);
    }

    /**
     * 刷新 Token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update()
    {
        $token = auth('api')->refresh();
        return $this->respondWithToken($token)->setStatusCode(ErrorCodes::HTTP_CREATED);
    }
    
    /**
     * 删除 Token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy()
    {
        auth('api')->logout();
        return $this->noContent();
    }
    
    /**
     * Get the token array structure.
     *
     * @param string $token
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => Auth::guard('api')->factory()
                ->getTTL() * 60
        ]);
    }
}
