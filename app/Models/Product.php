<?php
namespace App\Models;


 /**
 * @property int       $id
 * @property string    $name
 * @property double    $price
 * @property int       $status
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property string    $slug
 * @property int       $category_id
 */
class Product extends BaseModel
{
    protected $table = 'products';
    protected $fillable = [
    'name',
    'price',
    'status',
    'slug',
    'category_id',
];
}
