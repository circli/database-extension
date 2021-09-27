<?php
declare(strict_types=1);

namespace Circli\Database\Tests\Atlas\DataSource\People;

use Atlas\Mapper\Mapper;
use Atlas\Table\Row;

/**
 * @method PeopleTable getTable()
 * @method PeopleRelationships getRelationships()
 * @method PeopleRecord|null fetchRecord($primaryVal, array $with = [])
 * @method PeopleRecord|null fetchRecordBy(array $whereEquals, array $with = [])
 * @method PeopleRecord[] fetchRecords(array $primaryVals, array $with = [])
 * @method PeopleRecord[] fetchRecordsBy(array $whereEquals, array $with = [])
 * @method PeopleRecordSet fetchRecordSet(array $primaryVals, array $with = [])
 * @method PeopleRecordSet fetchRecordSetBy(array $whereEquals, array $with = [])
 * @method PeopleSelect select(array $whereEquals = [])
 * @method PeopleRecord newRecord(array $fields = [])
 * @method PeopleRecord[] newRecords(array $fieldSets)
 * @method PeopleRecordSet newRecordSet(array $records = [])
 * @method PeopleRecord turnRowIntoRecord(Row $row, array $with = [])
 * @method PeopleRecord[] turnRowsIntoRecords(array $rows, array $with = [])
 */
class People extends Mapper
{
}
