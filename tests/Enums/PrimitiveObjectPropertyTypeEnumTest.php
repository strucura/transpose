<?php

// tests/PrimitiveObjectPropertyTypeEnumTest.php

use Strucura\TypeGenerator\Enums\PrimitivesEnum;

it('returns Integer for integer types', function () {
    expect(PrimitivesEnum::tryFromDatabaseColumnType('bigint'))->toBe(PrimitivesEnum::Integer)
        ->and(PrimitivesEnum::tryFromDatabaseColumnType('integer'))->toBe(PrimitivesEnum::Integer)
        ->and(PrimitivesEnum::tryFromDatabaseColumnType('mediumint'))->toBe(PrimitivesEnum::Integer)
        ->and(PrimitivesEnum::tryFromDatabaseColumnType('smallint'))->toBe(PrimitivesEnum::Integer)
        ->and(PrimitivesEnum::tryFromDatabaseColumnType('tinyint'))->toBe(PrimitivesEnum::Integer)
        ->and(PrimitivesEnum::tryFromDatabaseColumnType('year'))->toBe(PrimitivesEnum::Integer);
});

it('returns Boolean for boolean types', function () {
    expect(PrimitivesEnum::tryFromDatabaseColumnType('binary'))->toBe(PrimitivesEnum::Boolean)
        ->and(PrimitivesEnum::tryFromDatabaseColumnType('boolean'))->toBe(PrimitivesEnum::Boolean)
        ->and(PrimitivesEnum::tryFromDatabaseColumnType('bit'))->toBe(PrimitivesEnum::Boolean);
});

it('returns String for string types', function () {
    expect(PrimitivesEnum::tryFromDatabaseColumnType('char'))->toBe(PrimitivesEnum::String)
        ->and(PrimitivesEnum::tryFromDatabaseColumnType('mediumtext'))->toBe(PrimitivesEnum::String)
        ->and(PrimitivesEnum::tryFromDatabaseColumnType('longtext'))->toBe(PrimitivesEnum::String)
        ->and(PrimitivesEnum::tryFromDatabaseColumnType('text'))->toBe(PrimitivesEnum::String)
        ->and(PrimitivesEnum::tryFromDatabaseColumnType('tinytext'))->toBe(PrimitivesEnum::String)
        ->and(PrimitivesEnum::tryFromDatabaseColumnType('varchar'))->toBe(PrimitivesEnum::String);
});

it('returns Date for date type', function () {
    expect(PrimitivesEnum::tryFromDatabaseColumnType('date'))->toBe(PrimitivesEnum::Date);
});

it('returns DateTime for datetime types', function () {
    expect(PrimitivesEnum::tryFromDatabaseColumnType('datetime'))->toBe(PrimitivesEnum::DateTime)
        ->and(PrimitivesEnum::tryFromDatabaseColumnType('timestamp'))->toBe(PrimitivesEnum::DateTime);
});

it('returns Float for float types', function () {
    expect(PrimitivesEnum::tryFromDatabaseColumnType('decimal'))->toBe(PrimitivesEnum::Float)
        ->and(PrimitivesEnum::tryFromDatabaseColumnType('double'))->toBe(PrimitivesEnum::Float)
        ->and(PrimitivesEnum::tryFromDatabaseColumnType('float'))->toBe(PrimitivesEnum::Float);
});

it('returns Time for time type', function () {
    expect(PrimitivesEnum::tryFromDatabaseColumnType('time'))->toBe(PrimitivesEnum::Time);
});

it('returns correct enum for geometry types', function () {
    expect(PrimitivesEnum::tryFromDatabaseColumnType('point'))->toBe(PrimitivesEnum::Point)
        ->and(PrimitivesEnum::tryFromDatabaseColumnType('geometry'))->toBe(PrimitivesEnum::Geometry)
        ->and(PrimitivesEnum::tryFromDatabaseColumnType('geometrycollection'))->toBe(PrimitivesEnum::GeometryCollection)
        ->and(PrimitivesEnum::tryFromDatabaseColumnType('linestring'))->toBe(PrimitivesEnum::LineString)
        ->and(PrimitivesEnum::tryFromDatabaseColumnType('multilinestring'))->toBe(PrimitivesEnum::MultiLineString)
        ->and(PrimitivesEnum::tryFromDatabaseColumnType('multipoint'))->toBe(PrimitivesEnum::MultiPoint)
        ->and(PrimitivesEnum::tryFromDatabaseColumnType('multipolygon'))->toBe(PrimitivesEnum::MultiPolygon)
        ->and(PrimitivesEnum::tryFromDatabaseColumnType('polygon'))->toBe(PrimitivesEnum::Polygon);
});

it('returns Any for unknown types', function () {
    expect(PrimitivesEnum::tryFromDatabaseColumnType('unknown'))->toBe(PrimitivesEnum::Any);
});
