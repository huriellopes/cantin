<?php

namespace App\Traits;

use App\Models\User;
use DateTime;
use Dompdf\Dompdf;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use JsonException;
use Spatie\DiscordAlerts\Facades\DiscordAlert;
use Telegram\Bot\Laravel\Facades\Telegram;
use Throwable;

trait Utils
{
    /**
     * Functions to clear scripts
     *
     * @return string|string[]|null
     */
    public function clear_tags(string $variavel): array|string|null
    {
        return preg_replace('(<(/?[^\>]+)>)', '', $variavel);
    }

    public static function clearMask(string $variable): array|string|null
    {
        return preg_replace('/[^0-9]/', '', $variable);
    }

    /**
     * @throws Exception
     */
    public function intervalDate(string $start_date, string $end_date, string $format = 'Y-m-d', string $slep = '+1day'): array
    {
        $dateStart = new DateTime($start_date);
        $dateEnd = new DateTime($end_date);

        $rangeDate = [];
        while ($dateStart <= $dateEnd) {
            $rangeDate[] = $dateStart->format($format);
            $dateStart = $dateStart->modify($slep);
        }

        return $rangeDate;
    }

    public function maskPhone(string $phone, $type = 'cel'): array|string
    {
        $formatedPhone = preg_replace('/[^0-9]/', '', $phone);
        $matches = [];

        if ($type !== 'cel') {
            preg_match('/^(\d{2})(\d{4,5})(\d{4})$/', (string) $formatedPhone, $matches);

            if ($matches !== []) {
                return '('.$matches[1].') '.$matches[2].'-'.$matches[3];
            }
        }

        preg_match('/^(\d{2})(\d{4,5})(\d{4})$/', (string) $formatedPhone, $matches);

        if ($matches !== []) {
            return '('.$matches[1].') 9 '.$matches[2].'-'.$matches[3];
        }

        return $phone;
    }

    public function validateInt($param): bool
    {
        return ! is_int($param);
    }

    /**
     * Api Return Pattern
     */
    public function returnResponse(bool $success, ?string $message, array|object|null $data, int $status, ?Throwable $exception = null, ?int $total = null): JsonResponse
    {
        $response['success'] = $success;
        $response['status'] = $status;
        if ($total) {
            $response['total'] = $total;
        }
        empty($data) ? $response['message'] = $message : $response['data'] = $data;

        if ($exception instanceof Throwable && config('app.debug')) {
            $response['line'] = $exception->getLine();
            $response['file'] = $exception->getFile();
            $response['trace'] = $exception->getTrace();
            $response['msg'] = $exception->getMessage();
            $response['code'] = $exception->getCode();
        }

        return response()->json($response, $status);
    }

    /**
     * @throws ValidationException
     */
    public function validateEmail(string $email): bool
    {
        $user = new User;

        $pattern = '/^[0-9a-z]([-_.]?[0-9a-z])*@[0-9a-z]([-.]?[0-9a-z])*\\.[a-z]{2,3}$/';

        $getUserName = $this->getOFModel($user, 'username', '=', $email)->first();
        $getUserEmail = $this->getOFModel($user, 'email', '=', $email)->first();

        if (! (empty($getUserName) || empty($getUserEmail) || (bool) preg_match($pattern, $email))) {
            throw ValidationException::withMessages([
                'username' => 'Credenciais inválidas, por favor verifique novamente.',
            ]);
        }

        return true;
    }

    public function getOFModel(Model $model, string $field, string $conditional, string $param): Collection
    {
        return $model->where($field, $conditional, $param)->get();
    }

    /**
     * Logging in file
     */
    public function logSystem(string $channel, string $message, string $type = 'info', array|object|null $data = null, $exception = null): void
    {
        $response['Message'] = $message;
        $response['Type'] = $type;
        $response['data'] = $data;

        if ((auth()->user() & ($type === 'info' || $type === 'error')) !== 0) {
            $response['user'] = 'User: '.auth()->user()->id;
        }

        if ($type === 'info') {
            Log::channel($channel)
                ->info(response()->json([$response,
                    'DateTime' => Date::now()->format('Y-m-d H:i:s')]).PHP_EOL);
        }

        if ($exception && $type === 'error') {
            if (config('app.debug')) {
                $response['line'] = $exception->getLine();
                $response['file'] = $exception->getFile();
                $response['trace'] = $exception->getTrace();
                $response['msg'] = $exception->getMessage();
                $response['code'] = $exception->getCode();
                $response['dateTime'] = Date::now()->format('Y-m-d H:i:s');
            }

            Log::channel($channel)->error(response()->json($response).PHP_EOL);
        }
    }

    public function generateHash(int $length = 10, ?string $NumberOrString = null): string
    {
        if (in_array($NumberOrString, [null, '', '0'], true)) {
            $string = implode('', range('A', 'Z')); // ABCDEFGHIJKLMNOPQRSTUVWXYZ
            $nums = implode('', range(0, 9)); // 0123456789

            $password = $string.$nums.$string.$nums; // ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789
        }

        if ($NumberOrString === 'string') {
            $string = implode('', range('A', 'Z'));

            $password = $string;
        } elseif ($NumberOrString === 'integer') {
            $nums = implode('', range(0, 9));

            $password = $nums;
        }

        $pass = '';

        for ($i = 0; $i < $length; $i++) {
            $pass .= $password[random_int(0, strlen($password) - 1)];
        }

        return $pass; // ex: q02TAq3
    }

    /**
     * Function to set username
     */
    public function setUserNameUser(string $name): JsonResponse|string
    {
        $parts = explode(' ', $name);
        $firstName = array_shift($parts);
        $lastName = array_pop($parts);

        return strtolower($firstName.' '.$lastName); // ex.: fulanosilva
    }

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
                $content['dateTime'] = Date::now()->format('Y-m-d H:i:s');
            }

            $content['msg'] = $exception->getMessage();
            $content['code'] = $exception->getCode();
            $content['file'] = $exception->getFile();
            $content['dateTime'] = Date::now()->format('Y-m-d H:i:s');
        }

        return DB::table('logs')->insert([
            'action' => $action,
            'ip' => request()->ip(),
            'type' => $type,
            'content' => json_encode($content),
            'user_id' => auth()->check() ? auth()->user()->id : null,
            'created_at' => Date::now()->format('Y-m-d H:i:s'),
            'updated_at' => Date::now()->format('Y-m-d H:i:s'),
        ]);
    }

    public function verifyDate(string $data): false|int
    {
        return preg_match('/(^\d{4}-\d{2}-\d{2}$)/', $data);
    }

    /**
     * @return \Barryvdh\DomPDF\PDF|Dompdf
     */
    public function PDFGenerate(string $view, object|array $data)
    {
        $pdf = PDF::loadView($view, $data);

        return $pdf
            ->setPaper('a4', 'landscape')
            ->setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);
    }

    /**
     * @return array|mixed
     *
     * @throws ConnectionException
     */
    public function consultAPI(string $endpoint, string $params, ?array $data = null, string $method = 'GET'): mixed
    {
        if ($method === 'GET') {
            return Http::acceptJson()->withHeaders([
                'Content-Type' => 'application/json',
            ])->get($endpoint.$params)->json();
        }

        return Http::acceptJson()->withHeaders([
            'Content-Type' => 'application/json',
        ])->post($endpoint.$params, $data)->json();
    }

    /**
     * @param  Exception|Throwable|null  $e
     */
    public static function webhook(string $type, Throwable $e, string $message, ?array $data = null): void
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
                ],
            ]);
        }
    }

    /**
     * @throws JsonException
     */
    public static function botCantinbr(Throwable $e, ?array $data = null): void
    {
        $chatId = config('telegram.bots.cantinbrBot.chatID');

        $message = "🚨 **Erro na Aplicação Laravel** 🚨\n\n";
        $message .= 'Caminho: '.request()->fullUrl()."\n";
        $message .= 'Mensagem: '.$e->getMessage()."\n";
        $message .= 'Usuário logado: '.(auth()->check() ? auth()->user()->id.'-'.auth()->user()->name : 'Não foi usuário logado')."\n";
        $message .= 'Data e hora: '.Date::now()->format('Y-m-d H:i:s')."\n";
        $message .= 'Dados: '.json_encode($data, JSON_THROW_ON_ERROR)."\n";
        $message .= 'Arquivo: '.$e->getFile().' (Linha: '.$e->getLine().")\n";

        try {
            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => $message,
                'parse_mode' => 'Markdown',
            ]);
        } catch (Exception $e) {
            Log::channel('telegram')->error('Erro ao enviar mensagem para o Telegram:', [
                'message' => $message,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
