<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CreateUserApiRequest;
use App\Http\Requests\CreateUserRequest;
use App\Mail\VerifyEmail;
use App\Models\Canton;
use App\Models\Image;
use App\Models\Parish;
use App\Models\Pet;
use App\Models\Province;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class UserApiController extends Controller
{

    public function __construct()
    {
    }

    function Login(Request $request)
    {
        try {
            $password = $request->password;

            $user = User::where('email', strtolower($request->email))->first();

            if ($user) {
                if ($user->email_verified_at == null) {

                    $detail = [
                        'title' => 'Clínica veterinaria de la universidad técnica de manabí',
                        'body' => 'Para verificar el correo electrónico da clic en el siguiente link.',
                        'api_token' => $user->api_token,
                        'backurl' => url()->previous()
                    ];

                    Mail::to($user->email)->send(new VerifyEmail($detail));
                    return response()->json(['message' => 'Look your email', 'data' => []], 301);
                } else {
                    $passwordD = Hash::check($password, $user->password);
                    if ($passwordD) {
                        $pet = $user->pets;
                        $canton = $user->canton;
                        $province = $user->province;
                        $parish = $user->parish;

                        $user['pet'] = $pet;
                        $user['canton'] = $canton;
                        $user['province'] = $province;
                        $user['parish '] = $parish;
                        return response()->json(['message' => 'Welcome', 'data' => $user], 200);
                    } else {
                        return response()->json(['message' => 'Password incorrect', 'data' => null], 401);
                    }
                }
            } else {
                if (strpos($request->email, "utm.edu.ec")) {
                    /* usuario utm */
                    try {
                        $response = Http::withHeaders([
                            'X-API-KEY' => '3ecbcb4e62a00d2bc58080218a4376f24a8079e1',
                        ])->withOptions(["verify" => false])->post('https://app.utm.edu.ec/becas/api/publico/IniciaSesion', [
                            'usuario' => $request->email,
                            'clave' => $request->password,
                        ]);
                        $output = $response->json();
                    } catch (\Throwable $th) {
                        return response()->json(['message' => 'Something went error', 'data' => $th], 500);
                    }
                    if ($output["state"] == "success") {

                        $usuario_utm = $output["value"];
                        $nombres_utm = explode(" ", $usuario_utm["nombres"], 3);
                        $PhotoPath = generateProfilePhotoPath($nombres_utm["2"]);

                        $id_province = Province::where('name', 'Manabi')
                            ->orWhere('name', 'Manabí')
                            ->orWhere('name', 'manabí')
                            ->orWhere('name', 'manabi')
                            ->orWhere('name', 'MANABI')
                            ->orWhere('name', 'MANABÍ')
                            ->first()
                            ->id;


                        $new_user = User::create([
                            'user_id' => $usuario_utm["cedula"],
                            'name' => $nombres_utm["2"],
                            'last_name1' => $nombres_utm["0"],
                            'last_name2' => $nombres_utm["1"],
                            'email' => $request->email,
                            'password' => Hash::make($request->password),
                            'email_verified_at' => date('Y-m-d h:i:s'),
                            'id_province' => $id_province ?? 1,
                            'api_token' => Str::random(25),
                            'profile_photo_path' => $PhotoPath,
                        ]);

                        $pet = $new_user->pets;
                        $canton = $new_user->canton;
                        $province = $new_user->province;
                        $parish = $new_user->parish;

                        $new_user['pet'] = $pet;
                        $new_user['canton'] = $canton;
                        $new_user['province'] = $province;
                        $new_user['parish '] = $parish;

                        return response()->json(['message' => 'Welcome', 'data' => $new_user], 200);
                    } else {
                        return response()->json(['message' => 'User not found', 'data' => null], 404);
                    }
                }
                return response()->json(['message' => 'User not found', 'data' => null], 404);
            }
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Something went error', 'data' => $th], 500);
        }
    }


    function Register(CreateUserApiRequest $request)
    {

        $input = $request->all();
        $input['api_token'] = Str::random(25);
        $input['password'] = Hash::make($input['password']);

        try {
            if (isset($input['user_id']))
                validateUserID($input['user_id']);
        } catch (Exception $e) {
            return response()->json(['message' => __('CI/RUC is invalid'), 'data' => []], 401);
        }

        $input['email'] = strtolower($input['email']);

        //Por defecto manabí...
        $id_province = Province::where('name', 'Manabi')
            ->orWhere('name', 'Manabí')
            ->orWhere('name', 'manabí')
            ->orWhere('name', 'manabi')
            ->orWhere('name', 'MANABI')
            ->orWhere('name', 'MANABÍ')
            ->first()
            ->id;

        $input['id_province'] = $id_province;

        try {
            DB::beginTransaction();
            if (isset($input['name'])) {
                $input['name'] = ucwords(strtolower($input['name']));
                $input['profile_photo_path'] = generateProfilePhotoPath($input['name']);
            }
            if (isset($input['last_name1']))  $input['last_name1'] = ucwords(strtolower($input['last_name1']));
            if (isset($input['last_name2']))  $input['last_name2'] = ucwords(strtolower($input['last_name2']));
            User::create($input);

            DB::commit();

            return response()->json(['message' => 'Welcome', 'data' => []], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Something went error', 'data' => $th], 500);
        }
    }

    function getProfile(Request $request)
    {
        $header = $request->header('Authorization');
        if ($header) {
            $user = User::where('api_token', $header)->first();
            if ($user) {
                $pet = $user->pets;
                $canton = $user->canton;
                $province = $user->province;
                $parish = $user->parish;

                for ($i = 0; $i < count($pet); $i++) {
                    if ($pet[$i]->specie)
                        $pet[$i]['image_specie'] = $pet[$i]->specie->image ? $pet[$i]->specie->image->url : null;
                    $pet[$i]['images'] = $pet[$i]->images;
                    $pet[$i]['specie'] = $pet[$i]->specie->name;
                    $pet[$i]['race'] = $pet[$i]->race->name;
                }

                $user['pet'] = $pet;
                $user['canton'] = $canton;
                $user['province'] = $province;
                $user['parish'] = $parish;

                return response()->json(['message' => 'Your data :)', 'data' => $user], 200);
            }
            return response()->json(['message' => 'User not found', 'data' => []], 404);
        } else {
            return response()->json(['message' => 'you are not authorized to view that profile', 'data' => []], 401);
        }

        return response()->json(['message' => 'Welcome', 'data' => []], 200);
    }

    function updateDataUser(Request $request)
    {

        $input = $request->all();
        $header = $request->header('Authorization');

        if ($header) {
            $user = User::where('api_token', $header)->first();
            if ($user) {

                $userFindE = User::where('email', $input['email'])
                    ->where('api_token', '!=', $header)
                    ->first();
                $userFindP = User::where('phone', $input['phone'])
                    ->where('api_token', '!=', $header)
                    ->first();
                if ($userFindE) return response()->json(['message' => 'El correo ya está registrado', 'data' => []], 301);
                try {
                    if (isset($input['user_id']))
                        validateUserID($input['user_id']);
                } catch (Exception $e) {
                    return response()->json(['message' => __('CI/RUC is invalid'), 'data' => []], 401);
                }
                if ($userFindP) return response()->json(['message' => 'El número de teléfono ya está registrado', 'data' => []], 301);
                if (isset($input['name']))  $input['name'] = ucwords(strtolower($input['name']));

                $pet = Pet::where('user_id', $user->user_id)->get();
                $canton = Canton::where('id', $user->id_canton)->first();
                $province = $canton ? Province::where('id', $canton->id_province)->first() : null;

                if ($user->email != $input['email']) {
                    $input['email_verified_at'] = null;
                }

                $input['email'] = strtolower($input['email']);

                try {
                    DB::beginTransaction();
                    $input['updated_at'] = now();
                    $input['id'] = $user->id;


                    $user->update($input);

                    $user['pet'] = $pet;
                    $user['canton'] = $canton;
                    $user['province'] = $province;

                    DB::commit();
                    return response()->json(['message' => 'User updated!', 'data' => $user], 200);
                } catch (\Throwable $th) {
                    return response()->json(['message' => 'Something went error', 'data' => $th], 500);
                }
            } else {
                return response()->json(['message' => 'User not found', 'data' => []], 404);
            }
        } else {
            return response()->json(['message' => 'you are not authorized to update that profile', 'data' => []], 401);
        }
    }

    public function VerifyEmail($api_token)
    {

        try {
            DB::beginTransaction();

            $user = User::where('api_token', $api_token)->first();
            if ($user->email_verified_at) return response()->json(['message' => 'the email has already been verified', 'data' => []], 500);
            $data['email_verified_at'] = now();
            $user->update($data);
            DB::commit();

            return response()->json(['message' => 'Verified email!', 'data' => []], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => 'Something went error', 'data' => $th], 500);
        }
    }


    public function updatedPassword(Request $request)
    {
        $input = $request->all();
        $header = $request->header('Authorization');

        if ($header) {
            $user = User::where('api_token', $header)->first();
            if ($user) {
                $passwordC = Hash::check($input['currentPassword'], $user->password);
                if ($passwordC) {
                    unset($input['currentPassword']);
                    $input['password'] =  Hash::make($input['password']);
                } else {
                    return response()->json(['message' => 'Contraseña actual incorrecta.', 'data' => []], 404);
                }

                try {
                    DB::beginTransaction();
                    $input['updated_at'] = now();
                    $input['id'] = $user->id;

                    $user->update($input);

                    DB::commit();
                    return response()->json(['message' => 'Password Updated!', 'data' => $user], 200);
                } catch (\Throwable $th) {
                    return response()->json(['message' => 'Something went error', 'data' => $th], 500);
                }
            } else {
                return response()->json(['message' => 'User not found', 'data' => []], 404);
            }
        } else {
            return response()->json(['message' => 'you are not authorized to update that profile', 'data' => []], 401);
        }
    }
}
