<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Xlogger extends Model
{
    protected $table = 'xlogger';
    protected $fillable = [
        'username',
        'request_uri',
        'http_method',
        'http_code',
        'parameters',
        'response',
        'request_headers',
        'response_headers',
        'user_agent',
        'ip',
        'time',
        'event_type',
        'message',
        'exception',
        'stack_trace',
        'query',
        'execution_time',



        ];
}
