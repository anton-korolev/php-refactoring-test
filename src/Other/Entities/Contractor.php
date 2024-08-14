<?php

declare(strict_types=1);

namespace Other\Entities;

/**
 * Base class for all Contractors.
 *
 * @property-read int $id Contractor id.
 * @property-read int $type Contractor type.
 * @property string $name Contractor name.
 * @property string $email Contractor email.
 * @property string $mobile Contractor mobile.
 */
abstract class Contractor
{
    /**
     * Type of all Contractors of this class.
     *
     * You must override this constant in the child class to define a specific Contractor type.
     * All available contractor types listed in the `ContractorType` class.
     */
    public const TYPE_CUSTOMER = ContractorType::NONE;

    public readonly int $id;
    public readonly int $type;

    protected const FAKE_CONTRACTOR = [
        'id' => 0,
        'name' => '',
        'email' => '',
        'mobile' => '',
    ];

    /**
     * Initializes a new Contractor and sets its type (see the `TYPE_CUSTOMER` constant).
     *
     * @param int $id Contractor id.
     * @param int $name Contractor name.
     * @param int $email Contractor email.
     * @param int $mobile Contractor mobile.
     */
    public function __construct(
        // public readonly int $id,
        int $id,
        public string $name = '',
        public string $email = '',
        public string $mobile = '',
    ) {
        $this->type = static::TYPE_CUSTOMER;
        // ...

        // Initializes a fake Contractor from the `FAKE_CONTRACTOR` constant.
        $this->id = static::FAKE_CONTRACTOR['id'];
        $this->name = static::FAKE_CONTRACTOR['name'];
        $this->email = static::FAKE_CONTRACTOR['email'];
        $this->mobile = static::FAKE_CONTRACTOR['mobile'];

        $this->init();
    }

    /**
     * Initializes additional Contractor parameters.
     */
    protected function init(): void {}

    /**
     * Finds the Contractor by condition.
     *
     * @param array $condition search terms.
     * @return static|null Contractor instance or null if not found.
     */
    protected static function findOne(array $condition): static|null
    {
        // Add current Contractor type to the condition.
        $condition['type'] = static::TYPE_CUSTOMER;
        // ...

        // return new static(0); // fakes the findOne method
        // fakes the findOne method
        return $condition['id'] === static::FAKE_CONTRACTOR['id']
            ? new static($condition['id'])
            : null;
    }

    /**
     * Finds the Contractor by id.
     *
     * @param int $contractorId Contractor id for the search.
     * @return static|null Contractor instance or null if not found.
     */
    public static function getById(int $contractorId): static|null
    {
        // return new self($resellerId); // fakes the getById method
        return static::findOne(['id' => $contractorId]);
    }

    /**
     * Returns the Contractor's full name.
     *
     * @return string Contractor's full name.
     */
    public function getFullName(): string
    {
        return $this->name . ' ' . $this->id;
    }
}
