<?php
namespace Spaaleks\Laragistry;

use Spaaleks\Laragistry\Models\LaragistryModel;

class Laragistry
{

    /**
     * Create or update a registry entry (in scope)
     *
     * @param array $data
     * @param string|null $scope
     *
     * @return \Illuminate\Support\Collection|Spaaleks\Laragistry\Models\LaragistryModel
     */
    public static function set(array $data, string $scope = null)
    {
        if (!self::isMultiArray($data)) {
            return LaragistryModel::createOrUpdate($data[0], $data[1], (isset($data[2]) ? $data[2] : false), $scope);
        }
        $result = new LaragistryCollection([]);
        foreach ($data as $item) {
            $result = $result->merge([
                $item[0] => LaragistryModel::createOrUpdate($item[0], $item[1], (isset($item[2]) ? $item[2] : false), $scope)
            ]);
        }
        return $result;
    }
    /**
     * Get a registry entry (in scope)
     *
     * @param mixed $data
     * @param string|null $scope
     *
     * @return \Illuminate\Support\Collection|Spaaleks\Laragistry\Models\LaragistryModel
     */
    public static function get($data, string $scope = null, bool $returnAsObject = false)
    {
        if (!is_array($data)) {
            return LaragistryModel::getSingle($data, $scope, $returnAsObject);
        }
        return LaragistryModel::getMulti($data, $scope, $returnAsObject);
    }
    /**
     * Check if key is present (in scope)
     *
     * @param string $key
     * @param string|null $scope
     *
     * @return bool
     */
    public static function check(string $key, string $scope = null)
    {
        $isWildCard = preg_match('/\*/', $key);
        if ($isWildCard) {
            throw new \Exception("Wildcard in [$key] not allowed for \"check\" methode.", 1);
        }

        return !is_null(LaragistryModel::getSingle($key, $scope)) ? true : false;
    }

    /**
     * Get all registry entries by scope
     *
     * @param null $scope
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getByScope($scope = null)
    {
        return LaragistryModel::getByScope($scope);
    }

    /**
     * Return a single or multiple entries by keys (in scope)
     *
     * @param mixed $data
     * @param string|null $scope
     *
     * @return boolean
     */
    public static function remove($data, string $scope = null)
    {
        $items = self::get($data, $scope, true);

        if (is_null($items)) {
            return false;
        }
        if ($items instanceof LaragistryModel) {
            $items->delete();
            return true;
        }
        if ($items instanceof LaragistryCollection) {
            foreach ($items as $item) {
                $item->delete();
            }
            return true;
        }
        return false;
    }

    /**
     * Check if data is a multidimensional array or list
     *
     * @param array $array
     *
     * @return bool
     */
    protected static function isMultiArray(array $array)
    {
        return is_array($array[0]);
    }

}
