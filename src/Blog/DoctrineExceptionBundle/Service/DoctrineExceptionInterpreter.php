<?php
/**
 * Created by PhpStorm.
 * User: Artur
 * Date: 9/29/14
 * Time: 7:05 PM
 */

namespace Blog\DoctrineExceptionBundle\Service;

use Doctrine\DBAL\DBALException;

/**
 * Class DoctrineExceptionInterpreter
 *
 * @package Blog\DoctrineExceptionBundle\Service
 */
class DoctrineExceptionInterpreter
{
    const ER_CONNECTION = 1;
    const ER_FEATURE_NOT_SUPPORTED = 2;
    const ER_DATA = 3;
    const ER_INTEGRITY_CONSTRAINT = 4;
    const ER_SYNTAX = 5;
    const ER_UNKNOWN = -1;

    /**
     * Translate a DBALException into a more informative error code
     *
     * @param DBALException $exception a DBALException object
     *
     * @return int One of the error code defined in this class, or ER_UNKNOWN
     */
    public function getErrorCode(DBALException $exception)
    {
        $pdo_exception = $exception->getPrevious();
        if (!is_subclass_of($pdo_exception, '\Exception')) {
            return self::ER_UNKNOWN;
        }

        $sql_state_class = substr((string)$pdo_exception->getCode(), 0, 2);
        switch ($sql_state_class) {
            case '08':
                return self::ER_CONNECTION;

            case '0A':
                return self::ER_FEATURE_NOT_SUPPORTED;

            case '22':
                return self::ER_DATA;

            case '23':
                return self::ER_INTEGRITY_CONSTRAINT;

            case '37':
            case '42':
                return self::ER_SYNTAX;

            default:
                return self::ER_UNKNOWN;
        }
    }

    /**
     * Determine whether a DBALException is an integrity constraint violation
     *
     * @param DBALException $exception a DBALException object
     *
     * @return bool True if the DBALException
     */
    public function isIntegrityConstraintViolation(DBALException $exception)
    {
        return $this->getErrorCode($exception) == self::ER_INTEGRITY_CONSTRAINT;
    }
}
