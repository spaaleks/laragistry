<?php
namespace Spaaleks\Laragistry\Models\Traits;

use Illuminate\Support\Facades\Crypt;
use Spaaleks\Laragistry\LaragistryCollection;

/**
 * Laragistry Trait
 */
trait LaragistryTrait
{

    /**
     * Create or update a registry entry (in scope)
     * @param string $key
     * @param string $value
     * @param string|null $scope
     *
     * @return Spaaleks\Laragistry\Models\LaragistryModel
     */
    public static function createOrUpdate(string $key, string $value, bool $shouldCrypt = false, string $scope = null)
    {
        $isWildCard = preg_match('/\*/', $key);
        if ($isWildCard) {
            throw new \Exception("Wildcard in [$key] not allowed for \"set\" methode.", 1);
        }

        $el = self::where([
            ['key', '=', $key],
            ['scope', '=', self::getScope($scope)],
        ])->first();

        if ($el == null) {
            $el = self::buildItem($key, $value, $shouldCrypt, $scope);
        } else {
            $el->value = $shouldCrypt ? Crypt::encrypt($value) : $value;
            $el->crypt = $shouldCrypt;
        }
        $el->save();
        
        return $el->value;
    }
    /**
     * Get a single registry by wildcard (in scope)
     *
     * @param mixed $key
     * @param string|null $scope
     *
     * @return Spaaleks\Laragistry\Models\LaragistryModel
     */
    public static function getWildcard($key, string $scope = null, bool $returnAsObject = false)
    {
        $key = preg_replace('/\*/', '%', $key);

        $query = self::where([
            ['key', 'like', $key],
            ['scope', '=', self::getScope($scope)],
        ])->get();
        
        if($returnAsObject)
            return $query;

        return $query->pluck('value', 'key');
    }
    /**
     * Get a single registry entry by key (in scope)
     *
     * @param mixed $key
     * @param string|null $scope
     *
     * @return Spaaleks\Laragistry\Models\LaragistryModel
     */
    public static function getSingle($key, string $scope = null, bool $valueOnly = true, bool $returnAsObject = false)
    {
        $doWildCard = preg_match('/\*/', $key);
        if ($doWildCard) {
            return self::getWildcard($key, $scope, $returnAsObject);
        }

        $query = self::where([
            ['key', '=', $key],
            ['scope', '=', self::getScope($scope)],
        ])->first();

        if($returnAsObject)
            return $query;

        return !is_null($query) ? ($valueOnly ? $query->value : [$query->key => $query->value]) : null;

    }
    /**
     * Get multiple registry entries  by key (in scope)
     *
     * @param array $keys
     * @param string|null $scope
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getMulti($keys, string $scope = null, bool $returnAsObject = false)
    {
        $collection = new LaragistryCollection([]);
        // Must be looped because of wildcards
        foreach ($keys as $key) {
            $item = self::getSingle($key, $scope, false, $returnAsObject);
            $collection = is_null($item) ? $collection : ($returnAsObject ? $collection->push($item) : $collection->merge($item));
        }
        return $collection;
    }
    /**
     * Get all registry entries by scope
     *
     * @param string|null $scope
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getByScope(string $scope = null)
    {
        return self::where('scope', '=', self::getScope($scope))
            ->get();
    }
    /**
     * Build a registry entry item with scope
     *
     * @param string $key
     * @param string $value
     * @param string|null $scope
     *
     * @return Spaaleks\Laragistry\Models\LaragistryModel
     */
    public static function buildItem(string $key, string $value, bool $shouldCrypt = false, string $scope = null)
    {
        $el = new self();
        $el->key = $key;
        $el->value = $shouldCrypt ? Crypt::encrypt($value) : $value;
        $el->crypt = $shouldCrypt;
        $el->scope = self::getScope($scope);
        return $el;
    }
    /**
     * Get socpe by config if is null
     *
     * @param string|null $scope
     *
     * @return bool
     */
    protected static function getScope(string $scope = null)
    {
        return is_null($scope) ? config('laragistry.scope') : $scope;
    }

    /**
     * Get correct value if encryption is enabled
     * 
     * @param string $value
     * 
     */
    public function getValueAttribute(string $value) {
        return !$this->crypt ? $value : Crypt::decrypt($value);
    }
}
