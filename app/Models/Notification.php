<?php
namespace App\Models;


 /**
 * @property int       $id
 * @property string    $type
 * @property string    $url
 * @property string    $channel
 * @property string    $status
 * @property string    $content
 * @property string    $title
 * @property \DateTime $sent_at
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 */
class Notification extends BaseModel
{
    protected $table = 'notifications';
    protected $fillable = [
    'type',
    'url',
    'channel',
    'status',
    'content',
    'title',
    'sent_at',
];
}
