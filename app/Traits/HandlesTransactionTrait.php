<?php 
namespace App\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Constants\Define\HttpCode;
use App\Constants\Define\HttpStatus;
use Illuminate\Http\JsonResponse;

trait HandlesTransactionTrait
{
    /**
     * Handle exceptions and return a JSON response
     */
    private function handleException(\Exception $e): JsonResponse
    {
        DB::rollBack();
        
        Log::error($e->getMessage());
        return responder()
            ->error(HttpCode::SERVER_ERROR, $e->getMessage())
            ->respond(HttpStatus::SERVER_ERROR);
    }

    /**
     * Run a callback within a transaction and handle exceptions
     */
    private function runInTransaction(callable $callback): JsonResponse
    {
        DB::beginTransaction();
        try {
            $result = $callback();
            DB::commit();
            return $result;
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Run a callback without a transaction and handle exceptions
     */
    private function runWithoutTransaction(callable $callback): JsonResponse
    {
        try {
            return $callback();
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }
}
