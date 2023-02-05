<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property \Illuminate\Support\Carbon|mixed $date_time
 * @property mixed|string $request_method
 * @property float|int|mixed $execution_time
 * @property mixed|string $request_url
 * @property mixed $response_code
 * @property mixed $response_body
 */

class ParserLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_time', 'request_method', 'request_url', 'response_code',
        'response_body', 'execution_time'
    ];
}
