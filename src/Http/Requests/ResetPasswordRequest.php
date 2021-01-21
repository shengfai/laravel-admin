<?php
namespace Shengfai\LaravelAdmin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * 重置密码验证
 * Class ResetPasswordRequest
 *
 * @package \App\Http\Requests
 * @author ShengFai <shengfai@qq.com>
 */
class ResetPasswordRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'password' => 'sometimes|required',
            'password_confirmation' => [
                'required_with:password',
                'same:password'
            ]
        ];
    }
}
