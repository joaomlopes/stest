<?php

namespace Uniplaces\STest\Requirement;

/**
 * Some landlords just want certain types of tenants, for instance PHD's.
 */
class TenantTypes
{
    const UNDERGRADUATE = 'undergraduate';
    const POSTGRADUATE = 'postgraduate';
    const RESEARCHER = 'researcher';
    const WORKER = 'worker';
    const TEACHER = 'teacher';

    /**
     * @var array
     */
    protected $types = [];

    /**
     * @param array $types
     */
    public function __construct(array $types)
    {
        $this->types = $types;
    }

    /***
     * @return array
     */
    public function toArray()
    {
        return $this->types;
    }

    /**
     * @return array
     */
    public static function getRecognized()
    {
        return array(
            static::UNDERGRADUATE,
            static::POSTGRADUATE,
            static::RESEARCHER,
            static::WORKER,
            static::TEACHER
        );
    }
}
