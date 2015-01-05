<?php

namespace Blog\APIBundle\DataFixtures\ORM;

use Faker;

/**
 * This is a Faker data provider that can help generate Blog-specific fixture data.
 */
class BlogDataProvider extends Faker\Provider\Base
{
    protected function chars($length, $chars)
    {
        $out = '';

        for ($i = 0; $i < $length; $i++) {
            $out .= $chars[ rand(0, strlen($chars) - 1) ];
        }

        return $out;
    }

    /**
     * @example k34j5hkj4h53
     * @param int $length
     * @return string
     */
    public function alphanum($length = 128)
    {
        return $this->chars($length, "abcdefghijklmnopqrstuvwxyz0123456789");
    }

    /**
     * @example ladsfndfjksd
     * @param int $length
     * @return string
     */
    public function letters($length = 128)
    {
        return $this->chars($length, "abcdefghijklmnopqrstuvwxyz");
    }

    /**
     * @example ABC1234
     * @return string
     */
    public function protoDesignator()
    {
        $num_letters = rand(1, 4);
        $num_digits = rand(1, 4);
        $out = $this->alphanum($num_letters);

        return static::toUpper($out . static::randomNumber($num_digits));
    }

    /**
     * @example Blabla Lorem/Ipsum
     * @return string
     */
    public function customName()
    {
        $words = Faker\Provider\Lorem::words(rand(1, 3));
        $out = ucfirst($words[0]);

        while (!empty($words)) {
            $out .= static::randomElement(array('/', ' ')) . ucfirst(array_pop($words));
        }

        return $out;
    }

    /**
     * @example K17
     * @return string
     */
    public function systemCode()
    {
        return static::toUpper(static::randomLetter()) . static::randomNumber(2);
    }

    /**
     * @example K17, K17 Lorem/Ipsum, K17 ABC123
     * @return string
     */
    public function projectName()
    {
        $destiny = rand(1, 20);

        if ($destiny === 1) {
            return static::systemCode();
        } elseif ($destiny < 15) {
            return static::systemCode() . ' ' . static::customName();
        } else {
            return static::systemCode() . ' ' . static::protoDesignator();
        }
    }

    /**
     * @example Blabla Lorem/Ipsum
     * @return string
     */
    public function categoryName()
    {
        return static::customName();
    }


}
