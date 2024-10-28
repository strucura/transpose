<?php

namespace Strucura\TypeGenerator\Enums;

enum PrimitivesEnum: string
{
    case Integer = 'integer';
    case Boolean = 'boolean';
    case DateTime = 'datetime';
    case Date = 'date';
    case Float = 'float';
    case String = 'string';
    case Point = 'point';
    case Time = 'time';
    case Geometry = 'geometry';
    case GeometryCollection = 'geometrycollection';
    case LineString = 'linestring';
    case MultiLineString = 'multilinestring';
    case MultiPoint = 'multipoint';
    case MultiPolygon = 'multipolygon';
    case Polygon = 'polygon';
    case Any = 'any';

    public static function tryFromDatabaseColumnType(string $columnType): PrimitivesEnum
    {
        return match ($columnType) {
            'bigint', 'integer', 'mediumint', 'smallint', 'tinyint', 'year' => self::Integer,
            'binary', 'boolean', 'bit' => self::Boolean,
            'char', 'mediumtext', 'longtext', 'text', 'tinytext', 'varchar' => self::String,
            'date' => self::Date,
            'datetime', 'timestamp' => self::DateTime,
            'decimal', 'double', 'float' => self::Float,
            'time' => self::Time,

            // Geometry types
            'point' => self::Point,
            'geometry' => self::Geometry,
            'geometrycollection' => self::GeometryCollection,
            'linestring' => self::LineString,
            'multilinestring' => self::MultiLineString,
            'multipoint' => self::MultiPoint,
            'multipolygon' => self::MultiPolygon,
            'polygon' => self::Polygon,

            // Unused types
            //'tinyblob' =>
            //'blob' =>
            //'mediumblob' =>
            //'longblob' =>
            //'enum' =>
            //'json' =>
            //'jsonb' =>

            default => self::Any,
        };
    }
}
