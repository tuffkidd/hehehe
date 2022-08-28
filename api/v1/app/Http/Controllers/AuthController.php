<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Created user
     * @param Request $request
     * @return User
     */
    public function register(Request $request)
    {
        try {
            $validate = Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                    'email' => 'required|email|unique:users,email',
                    'password' => 'required'
                ]
            );

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validate->getMessageBag()->toArray(),
                    'errors' => $validate->errors(),
                ], 401);
            }

            $user = User::create([
                'name' => $request->name,
                'nickname' => $request->nickname ? $request->nickname : '',
                'email' => $request->email,
                'sns_id' => '',
                'password' => Hash::make($request->password)
            ]);

            return response()->json([
                'status' => true,
                'message' => '사용자 생성 완료.',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * 사용자 로그인
     */
    public function login(Request $request)
    {
        try {
            $validate = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validate->getMessageBag()->toArray(),
                    'errors' => $validate->errors(),
                ], 401);
            }

            if (!Auth::attempt($request->only(['email', 'password']))) {
                return response()->json([
                    'status' => false,
                    'message' => '이메일 또는 비밀번호가 올바르지 않습니다.'
                ], 401);
            }

            $user = User::where('email', $request->email)->first();

            return response()->json([
                'status' => true,
                'message' => '로그인 했습니다.',
                'token' => $user->createToken("API TOKEN", ['*'], now()->addHours(1))->plainTextToken
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * 로그아웃
     */
    public function logout(Request $request)
    {

        // 현재 사용자 bearer 토큰 가져오기
        $accessToken = $request->bearerToken();

        // DB에서 찾아내기
        $token = PersonalAccessToken::findToken($accessToken);

        // 토큰 해지
        $token->delete();

        return response()->json([
            'status' => true,
            'message' => '로그아웃 했습니다.'
        ], 200);
    }

    /**
     * 유저 정보 가져오기
     */
    public function user(Request $request)
    {
        $plainToken = $request->bearerToken();
        $token = PersonalAccessToken::findToken($plainToken);

        if (!$plainToken || $plainToken == '' || !$token || $token == '' || $token == null) {
            return response()->json([
                'status' => false,
                'message' => '정상적인 방법으로 이용해주세요.'
            ]);
        }

        $user = $token->tokenable->toArray();
        return $user;
    }

    public function get_users()
    {
        return User::all();
    }
}
