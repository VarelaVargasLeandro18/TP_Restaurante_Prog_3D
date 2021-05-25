<?php

interface SerializeWithJSON extends JsonSerializable {

    public static function decode( string $serialized ) : mixed;

}