<?php

namespace interfaces;

interface SerializeWithJSON extends \JsonSerializable {

    public static function decode( string $serialized ) : mixed;

}