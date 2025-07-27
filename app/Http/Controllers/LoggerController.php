<?php

namespace App\Http\Controllers;

use App\Data\LoggerData;
use Illuminate\Support\Facades\Log;

class LoggerController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(LoggerData $data)
    {
        $logger = Log::channel(
            config('logging.channels.' . $data->parseChannelFromContext())
            ? $data->parseChannelFromContext()
            : config('logging.default')
        );

        try {
            $logger->log(
                level: $data->level_name ?? 'info',
                message: $data->message ?? 'No message provided',
                context: array_merge(
                    $data->context,
                    $data->extra ?: []
                ) ?? [],
            );
        } catch (\Throwable $th) {
            report($th);
        }
    }
}
