<?php
namespace Spaaleks\Laragistry\Models\Base;

use \Jenssegers\Mongodb\Eloquent\SoftDeletes;
use Jenssegers\Mongodb\Eloquent\Model;

class LaragistryMongodb extends Model
{
    use SoftDeletes;
}
