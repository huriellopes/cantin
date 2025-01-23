<?php

namespace App\Traits;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Spatie\DiscordAlerts\Facades\DiscordAlert;
use Throwable;
use Exception;

trait Utils
{

    /**
     * Functions to clear scripts
     *
     * @param string $variavel
     * @return string|string[]|null
     */
    public function clear_tags(string $variavel): array|string|null
    {
        return preg_replace('(<(/?[^\>]+)>)', '', $variavel);
    }

    /**
     * @param string $variable
     * @return array|string|null
     */
    public function clearMask(string $variable): array|string|null
    {
        return preg_replace('/[^0-9]/', '', $variable);
    }

    /**
     * @param string $start_date
     * @param string $end_date
     * @param string $format
     * @param string $slep
     * @return array
     * @throws \Exception
     */
    public function intervalDate (string $start_date, string $end_date, string $format = 'Y-m-d', string $slep = '+1day'): array
    {
        $dateStart = new \DateTime($start_date);
        $dateEnd = new \DateTime($end_date);

        $rangeDate = [];
        while($dateStart <= $dateEnd){
            $rangeDate[] = $dateStart->format($format);
            $dateStart = $dateStart->modify($slep);
        }

        return $rangeDate;
    }

    public function maskPhone(string $phone, $type = "cel"): array|string
    {
        $formatedPhone = preg_replace('/[^0-9]/', '', $phone);
        $matches = [];

        if ($type !== "cel") {
            preg_match('/^([0-9]{2})([0-9]{4,5})([0-9]{4})$/', $formatedPhone, $matches);

            if ($matches) {
                return '('.$matches[1].') '.$matches[2].'-'.$matches[3];
            }
        }

        preg_match('/^([0-9]{2})([0-9]{4,5})([0-9]{4})$/', $formatedPhone, $matches);

        if ($matches) {
            return '('.$matches[1].') 9 '.$matches[2].'-'.$matches[3];
        }

        return $phone;
    }

    /**
     * @param $param
     * @return bool
     */
    public function validateInt($param): bool
    {
        if (is_int($param)) {
            return false;
        }

        return true;
    }

    /**
     * Api Return Pattern
     *
     * @param bool $success
     * @param string|null $message
     * @param array|object|null $data
     * @param int $status
     * @param Throwable|null $exception
     * @param int|null $total
     * @return JsonResponse
     */
    public function returnResponse(bool $success, ?string $message, array|object|null $data, int $status, Throwable $exception = null, int $total = null) : JsonResponse
    {
        $response['success'] = $success;
        $response['status'] = $status;
        $total ? $response['total'] = $total : "";
        !empty($data) ? $response['data'] = $data : $response['message'] = $message;

        if ($exception) {
            if (config('app.debug')) {
                $response['line'] = $exception->getLine();
                $response['file'] = $exception->getFile();
                $response['trace'] = $exception->getTrace();
                $response['msg'] = $exception->getMessage();
                $response['code'] = $exception->getCode();
            }
        }

        return response()->json($response, $status);
    }

    /**
     * @param string $email
     * @return bool
     * @throws ValidationException
     */
    public function validateEmail(string $email) : bool
    {
        $user = new User();

        $pattern = "/^[0-9a-z]([-_.]?[0-9a-z])*@[0-9a-z]([-.]?[0-9a-z])*\\.[a-z]{2,3}$/";

        $getUserName = $this->getOFModel($user, 'username', '=', $email)->first();
        $getUserEmail = $this->getOFModel($user, 'email', '=', $email)->first();

        if (!(empty($getUserName) || empty($getUserEmail) || (bool) preg_match($pattern, $email))) {
            throw ValidationException::withMessages([
                'username' => 'Credenciais inválidas, por favor verifique novamente.',
            ]);
        }

        return true;
    }

    /**
     * @param Model $model
     * @param string $field
     * @param string $conditional
     * @param string $param
     * @return Collection
     */
    public function getOFModel(Model $model, string $field, string $conditional, string $param) : Collection
    {
        return $model->where($field, $conditional, $param)->get();
    }

    /**
     * Logging in file
     *
     * @param string $channel
     * @param string $message
     * @param string $type
     * @param array|object|NULL $data
     * @param $exception
     * @return void
     */
    public function logSystem(string $channel, string $message, string $type = 'info', array|object $data = NULL, $exception = NULL): void
    {
        $response['Message'] = $message;
        $response['Type'] = $type;
        $response['data'] = $data;

        if (auth()->user() & ($type === 'info' || $type === 'error')) {
            $response['user'] = "User: ". auth()->user()->id;
        }

        if ($type === 'info') {
            Log::channel($channel)
                ->info(response()->json([$response,
                        'DateTime' => Carbon::now()->format('Y-m-d H:i:s')]).PHP_EOL);
        }

        if ($exception && $type === 'error') {
            if (config('app.debug')) {
                $response['line'] = $exception->getLine();
                $response['file'] = $exception->getFile();
                $response['trace'] = $exception->getTrace();
                $response['msg'] = $exception->getMessage();
                $response['code'] = $exception->getCode();
                $response['dateTime'] = Carbon::now()->format('Y-m-d H:i:s');
            }

            Log::channel($channel)->error(response()->json($response).PHP_EOL);
        }
    }

    /**
     * @param int $length
     * @param string|null $NumberOrString
     * @return string
     */
    public function generateHash(int $length = 10, string $NumberOrString = null): string
    {
        if (empty($NumberOrString)) {
            $string = implode('', range('A', 'Z')); // ABCDEFGHIJKLMNOPQRSTUVWXYZ
            $nums = implode('', range(0, 9)); // 0123456789

            $password = $string.$nums.$string.$nums; // ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789
        }

        if ($NumberOrString === "string") {
            $string = implode('', range('A', 'Z'));

            $password = $string;
        } else if ($NumberOrString === "integer") {
            $nums = implode('', range(0, 9));

            $password = $nums;
        }


        $pass = '';

        for($i = 0; $i < $length; $i++) {
            $pass .= $password[rand(0, strlen($password) - 1)];
        }

        return $pass; // ex: q02TAq3
    }

    /**
     * Function to set username
     *
     * @param string $name
     * @return JsonResponse|string
     */
    public function setUserNameUser (string $name): JsonResponse|string
    {
        $parts = explode(' ', $name);
        $firstName = array_shift($parts);
        $lastName = array_pop($parts);

        return strtolower($firstName.' '.$lastName); // ex.: fulanosilva
    }

    /**
     * @param string $action
     * @param string $type
     * @param $exception
     * @return bool
     */
    public function loggingDatabase(string $action, string $type, $exception = null): bool
    {
        $content = null;

        if ($exception && $type === 'error') {
            if (config('app.debug')) {
                $content['line'] = $exception->getLine();
                $content['file'] = $exception->getFile();
                $content['trace'] = $exception->getTrace();
                $content['msg'] = $exception->getMessage();
                $content['code'] = $exception->getCode();
                $content['dateTime'] = Carbon::now()->format('Y-m-d H:i:s');
            }

            $content['msg'] = $exception->getMessage();
            $content['code'] = $exception->getCode();
            $content['file'] = $exception->getFile();
            $content['dateTime'] = Carbon::now()->format('Y-m-d H:i:s');
        }

        return DB::table('logs')->insert([
            'action' => $action,
            'ip' => request()->ip(),
            'type' => $type,
            'content' => json_encode($content),
            'user_id' => auth()->check() ? auth()->user()->id : null,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
    }

    /**
     * @param string $data
     * @return false|int
     */
    public function verifyDate(string $data): false|int
    {
        return preg_match('/(^\d{4}-\d{2}-\d{2}$)/', $data);
    }

    /**
     * @param string $view
     * @param object|array $data
     * @return \Barryvdh\DomPDF\PDF|\Dompdf\Dompdf
     */
    public function PDFGenerate(string $view, object|array $data)
    {
        $pdf = PDF::loadView($view, $data);

        return $pdf
            ->setPaper('a4', 'landscape')
            ->setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);
    }

    /**
     * @param string $endpoint
     * @param string $restData
     * @param array|null $data
     * @param string $method
     * @return array|mixed
     * @throws \Illuminate\Http\Client\ConnectionException
     */
    public function consultAPI(string $endpoint, string $restData, array $data = null, string $method = 'GET')
    {
        if ($method === 'GET') {
            return Http::acceptJson()->withHeaders([
                'Content-Type' => 'application/json',
            ])->get($endpoint.$restData)->json();
        }

        return Http::post($endpoint.$restData, $data)->json();
    }

    /**
     * @param string $type
     * @param Exception|Throwable|null $e
     * @param string $message
     * @param array|null $data
     * @return void
     */
    public function webhook(string $type = "error", Exception|Throwable $e = null, string $message, array $data = null): void
    {
        if ($type === 'error') {
            DiscordAlert::message("Error: $message \nMensagem: {$e->getMessage()}\nArquivo: {$e->getFile()}\nLinha: {$e->getLine()}!", [
                [
                    'title' => 'Error',
                    'description' => $message,
                    'color' => '#E77625',
                    'user' => auth()->check() ? auth()->user()->name : 'System',
                    'author' => [
                        'name' => 'Cantin',
                    ],
                    'data' => json_encode($data),
                ]
            ]);
        }
    }
}
