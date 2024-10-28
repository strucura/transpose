<?php

// tests/PrimitiveObjectPropertyTypeEnumTest.php

use Strucura\TypeGenerator\Enums\PrimitiveObjectPropertyTypeEnum;

it('returns Integer for integer types', function () {
    expect(PrimitiveObjectPropertyTypeEnum::tryFromDatabaseColumnType('bigint'))->toBe(PrimitiveObjectPropertyTypeEnum::Integer)
        ->and(PrimitiveObjectPropertyTypeEnum::tryFromDatabaseColumnType('integer'))->toBe(PrimitiveObjectPropertyTypeEnum::Integer)
        ->and(PrimitiveObjectPropertyTypeEnum::tryFromDatabaseColumnType('mediumint'))->toBe(PrimitiveObjectPropertyTypeEnum::Integer)
        ->and(PrimitiveObjectPropertyTypeEnum::tryFromDatabaseColumnType('smallint'))->toBe(PrimitiveObjectPropertyTypeEnum::Integer)
        ->and(PrimitiveObjectPropertyTypeEnum::tryFromDatabaseColumnType('tinyint'))->toBe(PrimitiveObjectPropertyTypeEnum::Integer)
        ->and(PrimitiveObjectPropertyTypeEnum::tryFromDatabaseColumnType('year'))->toBe(PrimitiveObjectPropertyTypeEnum::Integer);
});

it('returns Boolean for boolean types', function () {
    expect(PrimitiveObjectPropertyTypeEnum::tryFromDatabaseColumnType('binary'))->toBe(PrimitiveObjectPropertyTypeEnum::Boolean)
        ->and(PrimitiveObjectPropertyTypeEnum::tryFromDatabaseColumnType('boolean'))->toBe(PrimitiveObjectPropertyTypeEnum::Boolean)
        ->and(PrimitiveObjectPropertyTypeEnum::tryFromDatabaseColumnType('bit'))->toBe(PrimitiveObjectPropertyTypeEnum::Boolean);
});

it('returns String for string types', function () {
    expect(PrimitiveObjectPropertyTypeEnum::tryFromDatabaseColumnType('char'))->toBe(PrimitiveObjectPropertyTypeEnum::String)
        ->and(PrimitiveObjectPropertyTypeEnum::tryFromDatabaseColumnType('mediumtext'))->toBe(PrimitiveObjectPropertyTypeEnum::String)
        ->and(PrimitiveObjectPropertyTypeEnum::tryFromDatabaseColumnType('longtext'))->toBe(PrimitiveObjectPropertyTypeEnum::String)
        ->and(PrimitiveObjectPropertyTypeEnum::tryFromDatabaseColumnType('text'))->toBe(PrimitiveObjectPropertyTypeEnum::String)
        ->and(PrimitiveObjectPropertyTypeEnum::tryFromDatabaseColumnType('tinytext'))->toBe(PrimitiveObjectPropertyTypeEnum::String)
        ->and(PrimitiveObjectPropertyTypeEnum::tryFromDatabaseColumnType('varchar'))->toBe(PrimitiveObjectPropertyTypeEnum::String);
});

it('returns Date for date type', function () {
    expect(PrimitiveObjectPropertyTypeEnum::tryFromDatabaseColumnType('date'))->toBe(PrimitiveObjectPropertyTypeEnum::Date);
});

it('returns DateTime for datetime types', function () {
    expect(PrimitiveObjectPropertyTypeEnum::tryFromDatabaseColumnType('datetime'))->toBe(PrimitiveObjectPropertyTypeEnum::DateTime)
        ->and(PrimitiveObjectPropertyTypeEnum::tryFromDatabaseColumnType('timestamp'))->toBe(PrimitiveObjectPropertyTypeEnum::DateTime);
});

it('returns Float for float types', function () {
    expect(PrimitiveObjectPropertyTypeEnum::tryFromDatabaseColumnType('decimal'))->toBe(PrimitiveObjectPropertyTypeEnum::Float)
        ->and(PrimitiveObjectPropertyTypeEnum::tryFromDatabaseColumnType('double'))->toBe(PrimitiveObjectPropertyTypeEnum::Float)
        ->and(PrimitiveObjectPropertyTypeEnum::tryFromDatabaseColumnType('float'))->toBe(PrimitiveObjectPropertyTypeEnum::Float);
});

it('returns Time for time type', function () {
    expect(PrimitiveObjectPropertyTypeEnum::tryFromDatabaseColumnType('time'))->toBe(PrimitiveObjectPropertyTypeEnum::Time);
});

it('returns correct enum for geometry types', function () {
    expect(PrimitiveObjectPropertyTypeEnum::tryFromDatabaseColumnType('point'))->toBe(PrimitiveObjectPropertyTypeEnum::Point)
        ->and(PrimitiveObjectPropertyTypeEnum::tryFromDatabaseColumnType('geometry'))->toBe(PrimitiveObjectPropertyTypeEnum::Geometry)
        ->and(PrimitiveObjectPropertyTypeEnum::tryFromDatabaseColumnType('geometrycollection'))->toBe(PrimitiveObjectPropertyTypeEnum::GeometryCollection)
        ->and(PrimitiveObjectPropertyTypeEnum::tryFromDatabaseColumnType('linestring'))->toBe(PrimitiveObjectPropertyTypeEnum::LineString)
        ->and(PrimitiveObjectPropertyTypeEnum::tryFromDatabaseColumnType('multilinestring'))->toBe(PrimitiveObjectPropertyTypeEnum::MultiLineString)
        ->and(PrimitiveObjectPropertyTypeEnum::tryFromDatabaseColumnType('multipoint'))->toBe(PrimitiveObjectPropertyTypeEnum::MultiPoint)
        ->and(PrimitiveObjectPropertyTypeEnum::tryFromDatabaseColumnType('multipolygon'))->toBe(PrimitiveObjectPropertyTypeEnum::MultiPolygon)
        ->and(PrimitiveObjectPropertyTypeEnum::tryFromDatabaseColumnType('polygon'))->toBe(PrimitiveObjectPropertyTypeEnum::Polygon);
});

it('returns Any for unknown types', function () {
    expect(PrimitiveObjectPropertyTypeEnum::tryFromDatabaseColumnType('unknown'))->toBe(PrimitiveObjectPropertyTypeEnum::Any);
});
