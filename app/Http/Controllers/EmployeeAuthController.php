<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeAuthRequest;
use App\Http\Requests\EmployeeRegisterCodeRequest;
use App\Http\Requests\EmployeeRegisterRequest;
use App\Http\Requests\EmployeeResetPasswordRequest;
use App\Http\Traits\ApiResponse;
use App\Http\Traits\CodeGenerator;
use App\Http\Traits\SendSms;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class EmployeeAuthController extends Controller
{

    use ApiResponse;

    /**
     * @param EmployeeRegisterRequest $request
     * @return JsonResponse
     */
    public function reset_password(EmployeeResetPasswordRequest $request): JsonResponse
    {
        $employee = Employee::where('phone', $request->input('phone'))
            ->first();

        if(!$employee->registered_at) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'phone' => ['Uzyskiwanie nowego hasła jest zablokowane, Twoje konto nie przeszło pełnej rejestracji. Przejdź do rejestracji.']
                ],
            ], 422);
        }

        if ($employee->updated_at > Carbon::now()->subMinutes(10)) {
            $minutes = $employee->updated_at->diffInMinutes(Carbon::now()->subMinutes(10)) + 1;

            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'phone' => ["Zmiana hasła zablokowana, osiągnięto limit zgłoszeń. Prosimy spróbować za $minutes minut"]
                ],
            ], 422);
        }
        $password = CodeGenerator::getAlphanumericCode(8);

        $employee->update($request->merge([
            'password' => \Hash::make($password),
        ])
            ->only([
                'password',
            ]));

        SendSms::dispatch($employee->phone, "Nastapila zmiana hasla do Twojego konta, nowe  haslo to: $password.");

        return response()->json([
            'message' => 'Nowe hasło zostało wysłane na Twój nr telefonu.',
            'errors' => null
        ], 200);
    }


    /**
     * @param EmployeeRegisterCodeRequest $request
     * @return JsonResponse
     */
    public function registration_code(EmployeeRegisterCodeRequest $request): JsonResponse
    {

        $startDate = Carbon::parse(config('app.registration.begin_at'));
        $endDate = Carbon::parse(config('app.registration.end_at'));

        if (Carbon::now()->betweenIncluded($startDate, $endDate)) {

            $employee = Employee::where('registration_code', $request->input('registration_code'))
                ->first();

            if (!$employee) {
                return response()->json([
                    "message" => "The given data was invalid.",
                    'errors' => [
                        'phone' => ['Nieprawidłowy kod rejestracyjny']
                    ],
                ], 422);
            }

            return $this->success(['registration_code' => $employee->registration_code], null);
        }

        return response()->json([
            'message' => 'The given data was invalid.',
            'errors' => [
                'phone' => ['Rejestracja jest możliwa od ' . $startDate->format('d.m.Y') . ' do ' . $endDate->format('d.m.Y')]
            ],
        ]);
    }

    /**
     * @param EmployeeRegisterRequest $request
     * @return JsonResponse
     */
    public function registration(EmployeeRegisterRequest $request): JsonResponse
    {

        $startDate = Carbon::parse(config('app.registration.begin_at'));
        $endDate = Carbon::parse(config('app.registration.end_at'));

        if (Carbon::now()->betweenIncluded($startDate, $endDate)) {
            $employee = Employee::where('phone', $request->input('phone'))
                ->where('registration_code', $request->input('registration_code'))
                ->first();

            if (!$employee) {
                return response()->json([
                    'message' => 'The given data was invalid.',
                    'errors' => [
                        'phone' => ['Nieprawidłowy nr telefonu lub kod rejestracyjny']
                    ],
                ], 422);
            }

            if ($employee->password && $employee->registered_at) {
                return response()->json([
                    'message' => 'The given data was invalid.',
                    'errors' => [
                        'phone' => ['Takie konto już istnieje, proszę przejść do logowania']
                    ],
                ], 422);
            }

            $password = CodeGenerator::getAlphanumericCode(8);

            $employee->update($request->merge([
                'password' => \Hash::make($password),
                'registered_at' => Carbon::now(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ])
                ->only([
                    'first_name', 'last_name', 'registered_at',
                    'ip', 'user_agent', 'password',
                ]));

            SendSms::dispatch($employee->phone, "Dziekujemy za rejestracje na stronie. Twoje haslo to: $password. Twoimi loginem jest numer telefonu, na ktory otrzymales haslo. Loteria startuje 6 wrzesnia. Pamietaj, ze kazdego dnia roboczego masz szanse na obstawienie swoich losow na wybrane przez Ciebie nagrody.");

            return response()->json([
                'message' => 'Konto zostało utworzone. Hasło do logowania wysłaliśmy do Ciebie w wiadomości SMS.',
                'errors' => null
            ], 200);

        }

        return response()->json([
            'message' => 'The given data was invalid.',
            'errors' => [
                'phone' => ['Rejestracja jest możliwa od ' . $startDate->format('d.m.Y') . ' do ' . $endDate->format('d.m.Y')]
            ],
        ]);
    }


    /**
     * @param EmployeeAuthRequest $request
     * @return JsonResponse|void
     */
    public function login(EmployeeAuthRequest $request)
    {

        if (Auth::attempt([
            'phone' => $request->input('phone'),
            'password' => $request->input('password'),
        ])) {
            if(!Auth::user()->registered_at) {
                return $this->error('Konto nieaktywne, wymagana rejestracja. Przejdź na stronę i zarejestruj konto podając KOD REJESTRACYJNY z wiadomości SMS.', 422);
            }

            if (Auth::user()->end_of_work && Carbon::parse(Auth::user()->end_of_work) < now()) {
                return $this->error('Dziękujemy za udział w loterii. W związku z zakończeniem współpracy, nie możesz już uczestniczyć w dalszej grze.', 422);
            };

            return $this->success([
                'token' => auth()->user()->createToken('API Token')->plainTextToken,
                'first_name' => auth()->user()->first_name,
            ]);
        }

        return $this->error('Nieprawidłowe dane do logowania', 422);
    }


    /**
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        auth()->user()->tokens()->delete();

        return response()->json(['message' => 'Pomyślnie wylogowano']);
    }
}
