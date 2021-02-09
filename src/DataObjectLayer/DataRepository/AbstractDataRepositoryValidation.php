<?php
/*
 * This file is part of the MagmaCore package.
 *
 * (c) Ricardo Miller <ricardomiller@lava-studio.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace MagmaCore\DataObjectLayer\DataRepository;

use MagmaCore\Session\SessionTrait;

Abstract class AbstractDataRepositoryValidation implements DataRepositoryValidationInterface
{

    protected const FIRST = 0;
    protected const LAST = 1;

    /**
     * @inheritdoc
     * 
     * @param object $cleanData - the incoming data
     * @param object|null $dataRepository - the repository for the entity
     * @return mixed
     */
    abstract public function validateBeforePersist(Object $cleanData, ?Object $dataRepository = null);

    /**
     * @inheritdoc
     * 
     * @return array
     */
    abstract public function getErrors() : array;

    /**
     * @inheritdoc
     *
     * @return array
     */
    abstract public function fields() : array;

    /**
     * Allows controller to supply and key/value pair to splice from an array
     * 
     * @param array $elements
     * @return array
     */
    public function splice(array $elements = []) : array
    {
        if (count($elements) > 0) {
            return $elements;
        } else {
            return [];
        }
    }

    /**
     * Whilst this is not absolutely necessary as our newCleanData array would
     * have emitted this to prevent it persisting to the database and causing an
     * error. We will however just remove it from here
     *
     * @param array $cleanData
     * @return array
     */
    public function getCsrf(array $cleanData)
    {
        $csrf = [
            '_CSRF_INDEX' => $cleanData['_CSRF_INDEX'],
            '_CSRF_TOKEN' => $cleanData['_CSRF_TOKEN'],
        ];

        return array_diff_key($cleanData, !empty($this->splice()) ? array_merge($csrf, $this->splice()) : $csrf);
    }

    /**
     * Undocumented function
     *
     * @param array $args
     * @param string $key
     * @param integer $flag
     * @return void
     */
    public function getArrayPosition(array $args, string $key, int $flag = self::FIRST)
    {
        if (isset($args[$key]) && $args[$key] !=='') {
            $parts = explode(' ', $args[$key]);
            if ($parts) {
                switch ($flag) {
                    case 0 :
                        $index = $parts[array_key_first($parts)];
                        break;
                    case 1 :
                        $index = $parts[array_key_last($parts)];
                        break;
                }
            }
        } else {
            $index = $args[$key];
        }

        return $index;
    }

    /**
     * Undocumented function
     *
     * @param array $cleanData
     * @param string $field
     * @param mixed $default
     * @return mixed
     */
    public function setDefaultValue(array $cleanData, string $field, $default)
    {
        $value = $default;
        if (isset($cleanData[$field]) && $cleanData[$field] !='') {
            $value = $cleanData[$field];
        }

        if ($value) {
            return $value;
        }
    }

    public function getCreatedBy(array $cleanData)
    {
        $createdById = $this->setDefaultValue($cleanData, 'created_byid', SessionTrait::sessionFromGlobal()->get('user_id') ?? 0);
        return $createdById;
    }
    
    /**
     * @param $cleanData
     * @return array
     */
    protected function getArr(array $cleanData): array
    {
        $cleanData = (!empty($this->fields()) ? array_merge($cleanData, $this->fields()) : $cleanData);
        return $cleanData;
    }



}