<?php
namespace Spaaleks\Laragistry\Models;

$connection = config('database.default');
$driver = config("database.connections.{$connection}.driver");

if ($driver == 'mongodb') {
    class LaragistryModel extends Base\LaragistryMongodb
    {
        use Traits\LaragistryTrait;
        /**
         * @var string
         */
        protected $table = 'laragistry';
    }
} else {
    class LaragistryModel extends Base\LaragistryDefault
    {
        use Traits\LaragistryTrait;
        /**
         * @var string
         */
        protected $table = 'laragistry';
    }
}
